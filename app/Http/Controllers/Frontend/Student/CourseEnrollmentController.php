<?php
namespace App\Http\Controllers\Frontend\Student;
use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\ICTInvoice;
use App\Models\ICTCourseEnrollments;
use App\Models\ICTInvoiceItems;
use App\Services\PayWayService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class CourseEnrollmentController extends Controller
{
    // PayWay requires a short purchase `lifetime` (their minimum is 3
    // minutes) rather than letting a QR session sit open indefinitely.
    // Shared between the purchase payload below and the staleness check in
    // paymentPage() so both always agree on the same window.
    private const PAYWAY_LIFETIME_MINUTES = 5;

    // Flat, same-for-every-course amounts for the two payment plans a
    // student can pick at checkout (see setPlan()). Pay Full knocks $10 off
    // the course price in one payment; Pay Half adds a $20 installment
    // surcharge and splits the resulting total into two equal payments
    // (see buildPaymentContext()'s "amount due now" calculation).
    private const FULL_PLAN_DISCOUNT = 10.0;
    private const HALF_PLAN_SURCHARGE = 20.0;

    public function startEnrollment(ICTCourse $course)
    {
        $student = auth()->user();
        $alreadyEnrolled = ICTCourseEnrollments::where([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ])->exists();
        if ($alreadyEnrolled) {
            return back()->with('error', 'You are already enrolled.');
        }

        // A Pay Half invoice that's still sitting at half_paid means the
        // student owes a final installment on a DIFFERENT course — they're
        // not allowed to start a brand new course until that's settled.
        // (A half_paid invoice for THIS course would have made
        // $alreadyEnrolled true above already, since enrollment is granted
        // the moment the first installment lands — see
        // PayWayPaymentController::markInvoicePaid() — so this can only
        // ever match another course.)
        $blockingInvoice = ICTInvoice::with('course')
            ->where('student_id', $student->id)
            ->where('course_id', '!=', $course->id)
            ->where('payment_status', 'half_paid')
            ->latest()
            ->first();

        if ($blockingInvoice) {
            $blockingCourseTitle = $blockingInvoice->course->title ?? 'your other course';

            return back()->with(
                'error',
                "You still owe a remaining balance on \"{$blockingCourseTitle}\". Please finish paying for it before enrolling in a new course."
            );
        }

        // If this student already started (but never finished) checking
        // out for this exact course, send them back to that same invoice
        // instead of minting a brand new one every time they click
        // "Enroll" — otherwise they'd pile up duplicate unpaid invoices
        // for the same course.
        $existingInvoice = ICTInvoice::where([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ])->whereIn('payment_status', ['unpaid', 'half_paid'])
            ->latest()
            ->first();

        if ($existingInvoice) {
            // Pay Full is always what a student lands on when they (re-)select
            // a course — a Half plan picked on a previous, abandoned visit
            // doesn't carry over. Only safe to reset while nothing has been
            // paid yet; a half_paid invoice's plan is already locked anyway.
            if ($existingInvoice->payment_status === 'unpaid' && $existingInvoice->payment_option !== 'full') {
                $this->applyPlan($existingInvoice, 'full');
            }

            return redirect()->route('student.payment.page', $existingInvoice->id);
        }

        // Pay Full is the default plan — its $10 discount is the price
        // already advertised on every course card, so a freshly created
        // invoice should reflect it immediately rather than starting at
        // the undiscounted price.
        [$discount, $extraCharge, $totalAmount] = $this->planAmounts((float) $course->price, 'full');

        $invoice = ICTInvoice::create([
            'staff_id' => auth()->id(), // see note below
            'student_id' => auth()->id(),
            'course_id' => $course->id,
            'price' => $course->price,
            'discount' => $discount,
            'extra_charge' => $extraCharge,
            'total_amount' => $totalAmount,
            'paid_amount' => 0,
            'remaining_amount' => $totalAmount,
            'payment_option' => 'full',
            'payment_status' => 'unpaid',
            'invoice_code' => 'INV-' . now()->format('YmdHis') . rand(100, 999),
        ]);
        ICTInvoiceItems::create([
            'invoice_id' => $invoice->id,
            'course_id' => $course->id,
            'price' => $course->price,
            'discount' => $discount,
            'extra_charge' => $extraCharge,
            'total' => $totalAmount,
        ]);
        return redirect()->route(
            'student.payment.page',
            $invoice->id
        );
    }

    /**
     * Step 1 of checkout: "Select Schedule". Clicking Enroll on a course's
     * details page used to go straight to startEnrollment() against
     * whichever specific batch that page happened to represent — the
     * student never saw the other sections sharing the same title unless
     * they later found the small "Change" link on the checkout page
     * itself. This shows every section up front, with real detail (days,
     * time, instructor, start date, seats left), so the student picks
     * their batch deliberately before anything is created.
     *
     * Deliberately a GET with no side effects — each section's card is its
     * own tiny form that POSTs straight to the existing startEnrollment()
     * (see the view), so the actual enrollment/invoice-creation logic
     * (and its locking/validation) doesn't need to change at all here.
     */
    public function selectSchedule(ICTCourse $course): View|RedirectResponse
    {
        $student = auth()->user();
        $alreadyEnrolledHere = ICTCourseEnrollments::where([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ])->exists();

        // Same rule as startEnrollment() (see the comment there): a
        // half_paid invoice on a DIFFERENT course blocks starting a new
        // one. Guarding here too so a blocked student never even reaches
        // the schedule-picker page — they land straight back on the
        // invoice they still owe money on instead.
        if (!$alreadyEnrolledHere) {
            $blockingInvoice = ICTInvoice::with('course')
                ->where('student_id', $student->id)
                ->where('course_id', '!=', $course->id)
                ->where('payment_status', 'half_paid')
                ->latest()
                ->first();

            if ($blockingInvoice) {
                $blockingCourseTitle = $blockingInvoice->course->title ?? 'your other course';

                // ?pay=1 is the same flag the dashboard's "Pay Remaining"
                // link and the milestone panel's own button use — it skips
                // straight past the milestone screen to the actual
                // installment-2 pay state (see index.blade.php's
                // CONTINUE_PAYMENT_URL).
                return redirect()
                    ->to(route('student.payment.page', $blockingInvoice->id) . '?pay=1')
                    ->with('error', "Please finish paying for \"{$blockingCourseTitle}\" before enrolling in a new course.");
            }
        }

        $sections = ICTCourse::frontendVisible()
            ->with(['schedule', 'instructor'])
            ->withCount('students')
            ->where('title', $course->title)
            ->get();

        $enrolledSectionIds = ICTCourseEnrollments::where('student_id', auth()->id())
            ->whereIn('course_id', $sections->pluck('id'))
            ->pluck('course_id');

        $sections = $sections
            ->map(function (ICTCourse $section) use ($enrolledSectionIds, $course) {
                // Capacity defaults to 0 for older rows that predate the
                // capacity column — treat that as "not tracked" rather
                // than "zero seats", same convention the sibling-schedule
                // AJAX lookup (CourseController@schedules) uses.
                $hasCapacity = (int) $section->capacity > 0;
                $seatsTaken = (int) $section->students_count;

                $section->setAttribute('has_capacity', $hasCapacity);
                $section->setAttribute('seats_left', $hasCapacity ? max(0, $section->capacity - $seatsTaken) : null);
                $section->setAttribute('is_full', $hasCapacity && $seatsTaken >= $section->capacity);
                $section->setAttribute('already_enrolled', $enrolledSectionIds->contains($section->id));
                $section->setAttribute('is_origin', $section->id === $course->id);

                return $section;
            })
            ->sortBy(fn(ICTCourse $s) => match (strtolower($s->schedule->shift ?? '')) {
                'morning' => 0,
                'afternoon' => 1,
                'evening' => 2,
                default => 3,
            })
            ->values();

        return view('frontend.student.pages.payment.select-schedule', [
            'course' => $course,
            'sections' => $sections,
        ]);
    }

    public function paymentPage(ICTInvoice $invoice, PayWayService $payway): View|RedirectResponse
    {
        abort_unless($invoice->student_id === auth()->id(), 403);

        $invoice->load('course.schedule');

        $context = $this->buildPaymentContext($invoice, $payway);
        $display = $this->paymentDisplayFields($invoice, $context);

        return view('frontend.student.pages.payment.index', [
            'invoice' => $invoice,
            'paywayFields' => $context['paywayFields'],
            'paywayCheckoutJsUrl' => $payway->checkoutJsUrl(),
            'paywayExpiresAt' => $context['paywayExpiresAt'],
            'installmentLabel' => $display['installmentLabel'],
            'payButtonText' => $display['payButtonText'],
        ]);
    }

    /**
     * Lets the student switch between the "Pay in Full" and "Pay Half"
     * plans from the checkout page — recalculates discount/extra_charge/
     * total_amount for the invoice (and its line item) and clears
     * payway_tran_id so a fresh, correctly-signed PayWay payload gets
     * minted for the new amount. Locked once any payment has actually
     * landed on this invoice, same as switchSchedule().
     *
     * Called via fetch() from the checkout page (see the blade's script),
     * so the normal path returns JSON fragments the page swaps in without
     * a full reload. The redirect path only exists as a fallback for a
     * non-JS/non-AJAX request.
     */
    public function setPlan(Request $request, ICTInvoice $invoice, PayWayService $payway): JsonResponse|RedirectResponse
    {
        abort_unless($invoice->student_id === auth()->id(), 403);

        if ($invoice->payment_status !== 'unpaid') {
            $message = 'Your payment plan can no longer be changed once a payment has been made.';

            if ($request->wantsJson()) {
                return response()->json(['error' => $message], 422);
            }

            return redirect()
                ->route('student.payment.page', $invoice->id)
                ->with('error', $message);
        }

        $request->validate([
            'plan' => 'required|in:full,half',
        ]);

        $plan = $request->input('plan');

        if ($plan !== $invoice->payment_option) {
            $this->applyPlan($invoice, $plan);
        }

        if (!$request->wantsJson()) {
            return redirect()
                ->route('student.payment.page', $invoice->id)
                ->with('success', 'Payment plan updated.');
        }

        $invoice->refresh();
        $invoice->load('course.schedule');

        $context = $this->buildPaymentContext($invoice, $payway);
        $isHalfPlan = $invoice->payment_option === 'half';
        $planLocked = $invoice->payment_status !== 'unpaid';
        $display = $this->paymentDisplayFields($invoice, $context);

        return response()->json([
            'planToggleHtml' => view('frontend.student.pages.payment.partials.plan-toggle', [
                'invoice' => $invoice,
                'isHalfPlan' => $isHalfPlan,
                'planLocked' => $planLocked,
            ])->render(),
            'priceBreakdownHtml' => view('frontend.student.pages.payment.partials.price-breakdown', [
                'invoice' => $invoice,
                'isHalfPlan' => $isHalfPlan,
            ])->render(),
            'installmentLabel' => $display['installmentLabel'],
            'payButtonText' => $display['payButtonText'],
            'paywayFormHtml' => view('frontend.student.pages.payment.partials.payway-form-fields', [
                'paywayFields' => $context['paywayFields'],
            ])->render(),
            'paywayExpiresAt' => $context['paywayExpiresAt'],
        ]);
    }

    /**
     * Lets the student switch to a sibling batch/section (same course
     * title, different schedule_id) from the checkout page — re-targets
     * the invoice + its line item to the new course, recalculates the
     * amount, and clears payway_tran_id so a fresh, correctly-signed
     * PayWay payload gets minted for the new amount.
     *
     * Called via fetch() from the checkout page, same dual-mode pattern as
     * setPlan(): the normal path returns JSON fragments the page swaps in
     * without a full reload (no more "submit a hidden form and reload the
     * whole page" — that round trip was the one piece of this page that
     * still wasn't AJAX). The redirect path only exists as a fallback for
     * a non-JS/non-AJAX request.
     */
    public function switchSchedule(Request $request, ICTInvoice $invoice, PayWayService $payway): JsonResponse|RedirectResponse
    {
        abort_unless($invoice->student_id === auth()->id(), 403);

        if ($invoice->payment_status === 'paid') {
            $message = 'This invoice is already paid — schedule can no longer be changed.';

            if ($request->wantsJson()) {
                return response()->json(['error' => $message], 422);
            }

            return redirect()
                ->route('student.payment.page', $invoice->id)
                ->with('error', $message);
        }

        $request->validate([
            'course_id' => 'required|integer|exists:i_c_t_courses,id',
        ]);

        $newCourse = ICTCourse::findOrFail($request->course_id);

        if ($newCourse->id !== $invoice->course_id) {
            $alreadyEnrolled = ICTCourseEnrollments::where([
                'student_id' => $invoice->student_id,
                'course_id' => $newCourse->id,
            ])->exists();

            if ($alreadyEnrolled) {
                $message = 'You are already enrolled in that schedule.';

                if ($request->wantsJson()) {
                    return response()->json(['error' => $message], 422);
                }

                return redirect()
                    ->route('student.payment.page', $invoice->id)
                    ->with('error', $message);
            }

            $newTotal = (float) $newCourse->price - (float) $invoice->discount + (float) $invoice->extra_charge;

            $invoice->update([
                'course_id' => $newCourse->id,
                'price' => $newCourse->price,
                'total_amount' => $newTotal,
                'remaining_amount' => max(0, $newTotal - $invoice->paid_amount),
                'payway_tran_id' => null, // force a fresh signed payload for the new amount/course
            ]);

            $invoice->items()->update([
                'course_id' => $newCourse->id,
                'price' => $newCourse->price,
                'total' => $newTotal,
            ]);
        }

        if (!$request->wantsJson()) {
            return redirect()
                ->route('student.payment.page', $invoice->id)
                ->with('success', 'Schedule updated.');
        }

        $invoice->refresh();
        $invoice->load('course.schedule');

        $context = $this->buildPaymentContext($invoice, $payway);
        $isHalfPlan = $invoice->payment_option === 'half';
        $display = $this->paymentDisplayFields($invoice, $context);
        $schedule = $invoice->course->schedule;

        return response()->json([
            'scheduleLabel' => $schedule
                ? trim($schedule->short_days . ' · ' . $schedule->formatted_time)
                : 'Current schedule',
            'startDate' => $invoice->course->start_date?->format('F j, Y') ?? 'Depends on schedule',
            'priceBreakdownHtml' => view('frontend.student.pages.payment.partials.price-breakdown', [
                'invoice' => $invoice,
                'isHalfPlan' => $isHalfPlan,
            ])->render(),
            'installmentLabel' => $display['installmentLabel'],
            'payButtonText' => $display['payButtonText'],
            'paywayFormHtml' => view('frontend.student.pages.payment.partials.payway-form-fields', [
                'paywayFields' => $context['paywayFields'],
            ])->render(),
            'paywayExpiresAt' => $context['paywayExpiresAt'],
        ]);
    }

    /**
     * Shared by paymentPage() and setPlan(): mints/refreshes payway_tran_id
     * as needed and builds the signed PayWay purchase payload for the
     * invoice's current state (or null fields once it's fully paid).
     * Pulled out so both places compute "what's due right now" — including
     * Pay Half's special first-installment amount — identically.
     */
    private function buildPaymentContext(ICTInvoice $invoice, PayWayService $payway): array
    {
        // Reuse the same tran_id across page reloads/retries for this
        // invoice — as long as its purchase request hasn't expired — so
        // status polling and the PayWay dashboard stay consistent. But
        // since we send a short `lifetime` on the purchase payload below
        // (see PAYWAY_LIFETIME_MINUTES), PayWay permanently rejects that
        // same tran_id once the window passes ("Transaction is expired.
        // Please re-initiate the transaction.", Error Code: 68) — its own
        // "Try Again" button can't fix this because it just resubmits the
        // same dead tran_id. So a stale tran_id must be treated the same
        // as a missing one: mint a fresh one (also cleared on a schedule
        // switch, a plan switch, or right after an installment is paid —
        // see switchSchedule(), applyPlan() and PayWayPaymentController's
        // markInvoicePaid()) so simply reloading this page, or switching
        // plan, is enough to actually get a working, unexpired QR again.
        $tranExpired = $invoice->payway_tran_id
            && $invoice->payway_tran_started_at
            && $invoice->payway_tran_started_at->addMinutes(self::PAYWAY_LIFETIME_MINUTES)->isPast();

        if (empty($invoice->payway_tran_id) || $tranExpired) {
            $invoice->update([
                'payway_tran_id' => 'ICT' . $invoice->id . strtoupper(Str::random(8)),
                'payway_tran_started_at' => now(),
            ]);
        }

        if ($invoice->payment_status === 'paid') {
            // Already paid — most likely PayWay's continue_success_url
            // redirect landing back here right after a successful payment,
            // or a plan-switch request arriving after the invoice somehow
            // already settled. Nothing to build; the page's own
            // checkStatusOnLoad() JS shows the Step 4 success panel.
            return ['paywayFields' => null, 'paywayExpiresAt' => null, 'amountDue' => 0.0];
        }

        $student = auth()->user();
        [$firstName, $lastName] = $this->splitName($student->name);

        // Pay Half splits the invoice's total into two equal installments.
        // remaining_amount always tracks the FULL outstanding balance (so
        // the Order Summary can show an honest "Total" / "Remaining"), but
        // the very first Pay Half charge must only be for half of it — once
        // that installment is paid, remaining_amount naturally equals the
        // correct amount for the 2nd (and final) charge, so no special
        // case is needed beyond this first one.
        $isFirstHalfInstallment = $invoice->payment_option === 'half' && (float) $invoice->paid_amount <= 0;
        $amountDue = $isFirstHalfInstallment
            ? ((float) $invoice->total_amount) / 2
            : (float) $invoice->remaining_amount;

        $paywayFields = $payway->buildPurchasePayload([
            'tran_id' => $invoice->payway_tran_id,
            'amount' => number_format($amountDue, 2, '.', ''),
            'firstname' => $firstName,
            'lastname' => $lastName,
            'email' => $student->email,
            'phone' => $student->phone ?? '',
            'currency' => 'USD',
            'payment_option' => 'abapay_khqr', // skip PayWay's own "Choose way to pay" picker — KHQR is the only method offered
            // PayWay requires merchants to set a short lifetime so unpaid
            // QR sessions don't linger. After this many minutes PayWay
            // itself rejects the payment (and reverses any KHQR fund
            // transfer that arrives late) — see the staleness check above,
            // which re-mints tran_id once this window passes.
            'lifetime' => self::PAYWAY_LIFETIME_MINUTES,
            'return_url' => base64_encode(route('payway.callback')),
            'cancel_url' => route('student.payment.page', $invoice->id),
            'continue_success_url' => route('student.payment.page', $invoice->id),
            'skip_success_page' => '1',
        ]);

        return [
            'paywayFields' => $paywayFields,
            // Lets the page render a live "expires in mm:ss" countdown
            // against the same deadline the staleness check above enforces
            // server-side — computed here (not just PAYWAY_LIFETIME_MINUTES
            // from render time) so it's still correct if the student had
            // this tab open a while before the tran_id was actually minted.
            'paywayExpiresAt' => $invoice->payway_tran_started_at
                ->copy()
                ->addMinutes(self::PAYWAY_LIFETIME_MINUTES)
                ->toIso8601String(),
            'amountDue' => $amountDue,
        ];
    }

    /**
     * Recomputes and persists discount/extra_charge/total_amount for a
     * plan switch (or reset) and forces a fresh PayWay tran_id for the new
     * amount. Shared by startEnrollment()'s reset-to-Full and setPlan().
     */
    private function applyPlan(ICTInvoice $invoice, string $plan): void
    {
        [$discount, $extraCharge, $totalAmount] = $this->planAmounts((float) $invoice->price, $plan);

        $invoice->update([
            'payment_option' => $plan,
            'discount' => $discount,
            'extra_charge' => $extraCharge,
            'total_amount' => $totalAmount,
            'remaining_amount' => $totalAmount,
            'payway_tran_id' => null, // force a fresh signed payload for the new amount
        ]);

        $invoice->items()->update([
            'discount' => $discount,
            'extra_charge' => $extraCharge,
            'total' => $totalAmount,
        ]);
    }

    /**
     * Computes [discount, extra_charge, total_amount] for a given payment
     * plan against a course price. Both plans use the same flat $ amount
     * regardless of the course's price:
     *   - full: $10 off, paid in one go (discount is clamped so a very
     *     cheap course can never go negative).
     *   - half: $20 installment surcharge, total split into two equal
     *     payments (see buildPaymentContext()'s "amount due now" calculation).
     */
    private function planAmounts(float $price, string $plan): array
    {
        if ($plan === 'half') {
            $extraCharge = self::HALF_PLAN_SURCHARGE;
            return [0.0, $extraCharge, $price + $extraCharge];
        }

        $discount = min(self::FULL_PLAN_DISCOUNT, $price);
        return [$discount, 0.0, $price - $discount];
    }

    /**
     * Small presentation strings derived from the invoice's current state
     * and buildPaymentContext()'s amountDue — shared by paymentPage(),
     * setPlan() and switchSchedule() so the pay button's label and the
     * "Installment X of 2" badge are always computed the same way,
     * wherever the page (or an AJAX update) renders them.
     */
    private function paymentDisplayFields(ICTInvoice $invoice, array $context): array
    {
        $isHalfPlan = $invoice->payment_option === 'half';
        $isSecondInstallmentPending = $isHalfPlan && $invoice->payment_status === 'half_paid';

        return [
            'installmentLabel' => $isHalfPlan
                ? ($isSecondInstallmentPending ? 'Installment 2 of 2' : 'Installment 1 of 2')
                : null,
            // No "with ABA KHQR" suffix here — the pay button now has its
            // own static "ABA KHQR" title (see the blade's btn-aba-khqr
            // markup), so this string is just the subtitle line under it.
            'payButtonText' => 'Pay $' . number_format($context['amountDue'], 2),
        ];
    }

    private function splitName(?string $fullName): array
    {
        $fullName = trim((string) $fullName);

        if ($fullName === '') {
            return ['Student', ''];
        }

        $parts = preg_split('/\s+/', $fullName, 2);

        return [$parts[0], $parts[1] ?? ''];
    }
}
