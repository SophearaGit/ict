<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\ICTCourseEnrollments;
use App\Models\ICTInvoice;
use App\Models\ICTPayments;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class StudentRegisterationController extends Controller
{
    public function studentRegistration()
    {
        $courses = ICTCourse::with('schedule')->get();
        $students = User::where('role', 'student')->get();

        return view('frontend.staff.pages.student-management.student-registration', compact('courses', 'students'));
    }

    public function studentRegistrationSubmit(Request $request): RedirectResponse
    {
        // =========================
        // VALIDATION
        // =========================
        if ($request->student_type === 'new') {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'phone' => ['required', 'string', 'max:20'],
            ]);
        } else {
            $request->validate([
                'student_id' => ['required', 'exists:users,id'],
            ]);
        }

        $request->validate([
            'course_ids' => ['required', 'array', 'min:1'],
            'course_ids.*' => ['exists:i_c_t_courses,id'],
            'payment_option' => ['required', 'in:normal,full,half,multi,free,other'],
        ]);

        // Extra validation for manual (other) option
        if ($request->payment_option === 'other') {
            $request->validate([
                'manual_total_amount' => ['required', 'numeric', 'min:0'],
                'manual_paid_amount' => ['required', 'numeric', 'min:0'],
                'manual_discount' => ['nullable', 'numeric', 'min:0'],
                'manual_extra_charge' => ['nullable', 'numeric', 'min:0'],
            ]);
        }

        DB::beginTransaction();

        try {
            $courses = ICTCourse::whereIn('id', $request->course_ids)->get();

            if ($courses->isEmpty()) {
                throw new \Exception('Please select at least one course.');
            }

            $courseCount = $courses->count();
            $totalPrice = round($courses->sum('price'), 2);

            // =========================
            // CALCULATE ADJUSTMENTS
            // =========================
            $discount = 0;
            $extraCharge = 0;

            if ($request->payment_option === 'other') {
                // Staff-provided manual values
                $discount = round((float) $request->input('manual_discount', 0), 2);
                $extraCharge = round((float) $request->input('manual_extra_charge', 0), 2);
                $totalAmount = round((float) $request->input('manual_total_amount', 0), 2);
                $paid = round((float) $request->input('manual_paid_amount', 0), 2);
                $remaining = round($totalAmount - $paid, 2);
            } elseif ($request->payment_option === 'free') {
                $discount = $totalPrice;
                $totalAmount = 0;
                $paid = 0;
                $remaining = 0;
            } else {
                if ($request->payment_option === 'multi' && $courseCount >= 2) {
                    $discount += 25;
                }

                if ($request->payment_option === 'full') {
                    $discount += 10;
                }

                if ($request->payment_option === 'half') {
                    $extraCharge += 20;
                }

                $totalAmount = round($totalPrice - $discount + $extraCharge, 2);

                $paid = match ($request->payment_option) {
                    'half' => round($totalAmount / 2, 2),
                    default => $totalAmount,
                };

                if ($paid > $totalAmount) {
                    $paid = $totalAmount;
                }

                $remaining = round($totalAmount - $paid, 2);
            }

            // =========================
            // STATUS
            // =========================
            $status = match (true) {
                $request->payment_option === 'free' => 'free',
                $paid == 0 => 'unpaid',
                $remaining == 0 => 'paid',
                default => 'half_paid',
            };

            // =========================
            // CREATE / GET STUDENT
            // =========================
            if ($request->student_type === 'existing') {
                $user = User::where('id', $request->student_id)->where('role', 'student')->firstOrFail();
            } else {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'student',
                    'approval_status' => 'approved',
                    'registered_by_staff_id' => Auth::id(),
                    'phone' => $request->phone,
                    'alternate_phone' => $request->alternate_phone ?? null,
                ]);
            }

            // =========================
            // ENROLLMENTS
            // =========================
            foreach ($courses as $course) {
                $exists = ICTCourseEnrollments::where([
                    'student_id' => $user->id,
                    'course_id' => $course->id,
                ])->exists();

                if ($exists) {
                    throw new \Exception("Student already enrolled in {$course->title}");
                }

                ICTCourseEnrollments::create([
                    'student_id' => $user->id,
                    'course_id' => $course->id,
                    'enrolled_by' => Auth::id(),
                    'status' => 'active',
                    'enrolled_at' => now(),
                ]);
            }

            // =========================
            // CREATE INVOICE
            // =========================
            $invoice = ICTInvoice::create([
                'staff_id' => Auth::id(),
                'student_id' => $user->id,
                'course_id' => $courses->first()->id,

                'price' => $totalPrice,
                'discount' => $discount,
                'extra_charge' => $extraCharge,

                'total_amount' => $totalAmount,
                'paid_amount' => $paid,
                'remaining_amount' => $remaining,

                'payment_option' => $request->payment_option,
                'payment_status' => $status,

                'invoice_code' => 'INV-' . now()->format('YmdHis') . '-' . mt_rand(100, 999),
                'paid_at' => $paid > 0 || $request->payment_option === 'free' ? now() : null,
            ]);

            // =========================
            // DISTRIBUTE ITEMS
            // =========================
            $distributedDiscount = 0;
            $distributedExtra = 0;

            foreach ($courses->values() as $index => $course) {
                $itemDiscount = 0;
                $itemExtra = 0;

                if ($request->payment_option === 'free') {
                    $itemDiscount = $course->price;
                } elseif ($request->payment_option === 'other') {
                    // Distribute manual discount/extra evenly across courses
                    if ($discount > 0) {
                        if ($index === $courseCount - 1) {
                            $itemDiscount = round($discount - $distributedDiscount, 2);
                        } else {
                            $itemDiscount = round($discount / $courseCount, 2);
                            $distributedDiscount += $itemDiscount;
                        }
                    }

                    if ($extraCharge > 0) {
                        if ($index === $courseCount - 1) {
                            $itemExtra = round($extraCharge - $distributedExtra, 2);
                        } else {
                            $itemExtra = round($extraCharge / $courseCount, 2);
                            $distributedExtra += $itemExtra;
                        }
                    }
                } else {
                    if ($discount > 0) {
                        if ($index === $courseCount - 1) {
                            $itemDiscount = round($discount - $distributedDiscount, 2);
                        } else {
                            $itemDiscount = round($discount / $courseCount, 2);
                            $distributedDiscount += $itemDiscount;
                        }
                    }

                    if ($extraCharge > 0) {
                        if ($index === $courseCount - 1) {
                            $itemExtra = round($extraCharge - $distributedExtra, 2);
                        } else {
                            $itemExtra = round($extraCharge / $courseCount, 2);
                            $distributedExtra += $itemExtra;
                        }
                    }
                }

                $itemTotal = round($course->price - $itemDiscount + $itemExtra, 2);

                $invoice->items()->create([
                    'course_id' => $course->id,
                    'price' => $course->price,
                    'discount' => $itemDiscount,
                    'extra_charge' => $itemExtra,
                    'total' => $itemTotal,
                ]);
            }

            // =========================
            // FINAL RECALCULATION
            // — skip for 'other' and 'free' since amounts are already correct
            // =========================
            if (!in_array($request->payment_option, ['other', 'free'])) {
                $itemTotalSum = round($invoice->items()->sum('total'), 2);

                $invoice->update([
                    'total_amount' => $itemTotalSum,
                    'remaining_amount' => round($itemTotalSum - $paid, 2),
                ]);
            }

            // =========================
            // PAYMENT RECORD
            // =========================
            if ($paid > 0) {
                ICTPayments::create([
                    'invoice_id' => $invoice->id,
                    'amount' => $paid,
                    'payment_method' => 'cash',
                    'paid_by' => Auth::id(),
                    'paid_at' => now(),
                    'note' => $request->payment_option === 'other' ? 'Manual entry payment during registration' : 'Initial payment during registration',
                ]);
            }

            DB::commit();

            return redirect()->route('staff.invoices')->with('success', 'Student registered successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
