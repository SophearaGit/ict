<?php
namespace App\Http\Controllers\Frontend\Student;
use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\ICTInvoice;
use App\Models\ICTCourseEnrollments;
use App\Models\ICTInvoiceItems;
use App\Services\PayWayService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class CourseEnrollmentController extends Controller
{
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
        $invoice = ICTInvoice::create([
            'staff_id' => auth()->id(), // see note below
            'student_id' => auth()->id(),
            'course_id' => $course->id,
            'price' => $course->price,
            'discount' => 0,
            'extra_charge' => 0,
            'total_amount' => $course->price,
            'paid_amount' => 0,
            'remaining_amount' => $course->price,
            'payment_option' => 'full',
            'payment_status' => 'unpaid',
            'invoice_code' => 'INV-' . now()->format('YmdHis') . rand(100, 999),
        ]);
        ICTInvoiceItems::create([
            'invoice_id' => $invoice->id,
            'course_id' => $course->id,
            'price' => $course->price,
            'discount' => 0,
            'extra_charge' => 0,
            'total' => $course->price,
        ]);
        return redirect()->route(
            'student.payment.page',
            $invoice->id
        );
    }

    public function paymentPage(ICTInvoice $invoice, PayWayService $payway): View|RedirectResponse
    {
        abort_unless($invoice->student_id === auth()->id(), 403);

        $invoice->load('course.schedule');

        // Reuse the same tran_id across page reloads/retries for this
        // invoice so status polling and the PayWay dashboard stay
        // consistent — only mint a new one the first time (or after a
        // schedule switch, which clears it — see switchSchedule()).
        if (empty($invoice->payway_tran_id)) {
            $invoice->update([
                'payway_tran_id' => 'ICT' . $invoice->id . strtoupper(Str::random(8)),
            ]);
        }

        $student = auth()->user();

        if ($invoice->payment_status === 'paid') {
            // Already paid — most likely PayWay's continue_success_url
            // redirect landing back here right after a successful payment.
            // Render the same view with no PayWay payload; the page's own
            // checkStatusOnLoad() JS will call /status and show the Step 4
            // success panel immediately, no polling needed.
            return view('frontend.student.pages.payment.index', [
                'invoice' => $invoice,
                'paywayFields' => null,
                'paywayCheckoutJsUrl' => $payway->checkoutJsUrl(),
            ]);
        }

        [$firstName, $lastName] = $this->splitName($student->name);

        $paywayFields = $payway->buildPurchasePayload([
            'tran_id' => $invoice->payway_tran_id,
            'amount' => number_format((float) $invoice->remaining_amount, 2, '.', ''),
            'firstname' => $firstName,
            'lastname' => $lastName,
            'email' => $student->email,
            'phone' => $student->phone ?? '',
            'currency' => 'USD',
            'payment_option' => 'abapay_khqr', // skip PayWay's own "Choose way to pay" picker — KHQR is the only method offered
            'return_url' => base64_encode(route('payway.callback')),
            'cancel_url' => route('student.payment.page', $invoice->id),
            'continue_success_url' => route('student.payment.page', $invoice->id),
            'skip_success_page' => '1',
        ]);

        return view('frontend.student.pages.payment.index', [
            'invoice' => $invoice,
            'paywayFields' => $paywayFields,
            'paywayCheckoutJsUrl' => $payway->checkoutJsUrl(),
        ]);
    }

    /**
     * Lets the student switch to a sibling batch/section (same course
     * title, different schedule_id) from the checkout page — re-targets
     * the invoice + its line item to the new course, recalculates the
     * amount, and clears payway_tran_id so paymentPage() mints a fresh,
     * correctly-signed PayWay payload for the new amount on next load.
     */
    public function switchSchedule(Request $request, ICTInvoice $invoice): RedirectResponse
    {
        abort_unless($invoice->student_id === auth()->id(), 403);

        if ($invoice->payment_status === 'paid') {
            return redirect()
                ->route('student.payment.page', $invoice->id)
                ->with('error', 'This invoice is already paid — schedule can no longer be changed.');
        }

        $request->validate([
            'course_id' => 'required|integer|exists:i_c_t_courses,id',
        ]);

        $newCourse = ICTCourse::findOrFail($request->course_id);

        if ($newCourse->id === $invoice->course_id) {
            return redirect()->route('student.payment.page', $invoice->id);
        }

        $alreadyEnrolled = ICTCourseEnrollments::where([
            'student_id' => $invoice->student_id,
            'course_id' => $newCourse->id,
        ])->exists();

        if ($alreadyEnrolled) {
            return redirect()
                ->route('student.payment.page', $invoice->id)
                ->with('error', 'You are already enrolled in that schedule.');
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

        return redirect()
            ->route('student.payment.page', $invoice->id)
            ->with('success', 'Schedule updated.');
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
