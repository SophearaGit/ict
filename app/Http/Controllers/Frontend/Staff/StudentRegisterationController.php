<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ICTCourse;
use App\Models\ICTInvoice;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Symfony\Component\HttpFoundation\RedirectResponse;

class StudentRegisterationController extends Controller
{

    public function studentRegistration(): View
    {
        $data = [
            'page_title' => 'ICT Center | Student Registration',
            'students' => User::where('role', 'student')->get(),
            'courses' => ICTCourse::latest()->get(),
        ];
        return view('frontend.staff.pages.student-management.student-registration', $data);
    }

    public function studentRegistrationSubmit(Request $request): RedirectResponse
    {


        if ($request->student_type === 'new') {

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

        } else {

            $request->validate([
                'student_id' => ['required', 'exists:users,id'],
            ]);
        }

        $request->validate([
            'course_id' => ['required', 'exists:i_c_t_courses,id'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
        ]);


        DB::beginTransaction();

        try {
            $course = ICTCourse::findOrFail($request->course_id);

            $price = $course->price;
            $discount = $request->discount ?? 0;
            $paid = $request->paid_amount ?? 0;

            // Ensure discount does not exceed price
            if ($discount > $price) {
                $discount = $price;
            }

            // Total after fixed discount
            $totalAmount = $price - $discount;

            // Prevent overpayment
            if ($paid > $totalAmount) {
                $paid = $totalAmount;
            }

            $remaining = $totalAmount - $paid;

            // Determine payment status
            if ($paid == 0) {
                $status = 'unpaid';
            } elseif ($remaining == 0) {
                $status = 'paid';
            } else {
                $status = 'half_paid';
            }

            // Create student
            if ($request->student_type === 'existing') {
                $user = User::where('id', $request->student_id)
                    ->where('role', 'student')
                    ->firstOrFail();
            } else {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'role' => 'student',
                    'approval_status' => 'approved',
                    'registered_by_staff_id' => Auth::id(),
                ]);
            }

            // Create invoice
            ICTInvoice::create([
                'staff_id' => Auth::id(),
                'student_id' => $user->id,
                'course_id' => $course->id,
                'price' => $price,
                'discount' => $discount,
                'total_amount' => $totalAmount,
                'paid_amount' => $paid,
                'remaining_amount' => $remaining,
                'invoice_code' => '#' . strtoupper(uniqid()),
                'payment_status' => $status,
            ]);

            DB::commit();

            return redirect()->route('staff.invoices')->with('success', 'Student registered successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

}
