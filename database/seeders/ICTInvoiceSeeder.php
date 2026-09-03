<?php

namespace Database\Seeders;

use App\Models\ICTCourseEnrollments;
use App\Models\ICTInvoice;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ICTInvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * One invoice per seeded enrollment, with a realistic mix of payment
     * states (paid in full / half-paid / unpaid / free) and payment
     * methods (cash collected in person, Bakong KHQR, PayWay) so the
     * staff invoice list and student payment pages both have real
     * variety to show instead of everything being "paid".
     */
    public function run(): void
    {
        $staffIds = User::where('role', 'staff')->pluck('id')->all();

        if (empty($staffIds)) {
            $this->command?->warn('ICTInvoiceSeeder: no staff users found — run UserSeeder first.');
            return;
        }

        $sequence = 1;

        ICTCourseEnrollments::with('course')
            ->where('status', '!=', 'dropped')
            ->orderBy('id')
            ->each(function (ICTCourseEnrollments $enrollment) use (&$sequence, $staffIds): void {
                $course = $enrollment->course;
                if (! $course) {
                    return;
                }

                $price = (float) ($course->price ?: 150);
                $roll = $sequence % 20;

                // ~5% fully waived, ~55% paid in full, ~20% half paid, ~20% unpaid.
                [$paymentOption, $paymentStatus, $paidFraction] = match (true) {
                    $roll === 0 => ['free', 'free', 0.0],
                    $roll < 11 => ['full', 'paid', 1.0],
                    $roll < 15 => ['half', 'half_paid', 0.5],
                    default => ['normal', 'unpaid', 0.0],
                };

                $discount = $paymentStatus === 'free' ? $price : ($sequence % 7 === 0 ? round($price * 0.1, 2) : 0);
                $extraCharge = $sequence % 11 === 0 ? 10 : 0;
                $totalAmount = max(0, round($price - $discount + $extraCharge, 2));
                $paidAmount = round($totalAmount * $paidFraction, 2);
                $remainingAmount = max(0, round($totalAmount - $paidAmount, 2));

                $paidAt = $paidAmount > 0
                    ? \Illuminate\Support\Carbon::parse($enrollment->enrolled_at ?? now())->addDays(rand(0, 3))
                    : null;

                // Alternate how a paid/half-paid invoice was settled: cash
                // at the office, Bakong KHQR, or PayWay — only relevant
                // when something was actually paid.
                $gateway = null;
                $bakongTxnRef = null;
                $bakongHash = null;
                $paywayTranId = null;

                if ($paidAmount > 0) {
                    $method = $sequence % 3;
                    if ($method === 1) {
                        $gateway = 'bakong';
                        $bakongTxnRef = 'FT' . str_pad((string) $sequence, 10, '0', STR_PAD_LEFT);
                        $bakongHash = hash('sha256', 'bakong-' . $sequence);
                    } elseif ($method === 2) {
                        $gateway = 'payway';
                        $paywayTranId = 'ICT' . now()->format('ymd') . str_pad((string) $sequence, 6, '0', STR_PAD_LEFT);
                    }
                    // $method === 0 => paid in cash, no gateway fields.
                }

                ICTInvoice::updateOrCreate(
                    ['course_id' => $course->id, 'student_id' => $enrollment->student_id],
                    [
                        'staff_id' => $enrollment->enrolled_by ?? $staffIds[array_rand($staffIds)],
                        'price' => $price,
                        'discount' => $discount,
                        'extra_charge' => $extraCharge,
                        'total_amount' => $totalAmount,
                        'paid_amount' => $paidAmount,
                        'remaining_amount' => $remainingAmount,
                        'payment_option' => $paymentOption,
                        'payment_status' => $paymentStatus,
                        'invoice_code' => 'INV-' . now()->format('Y') . '-' . str_pad((string) $sequence, 6, '0', STR_PAD_LEFT),
                        'paid_at' => $paidAt,
                        'bakong_txn_ref' => $bakongTxnRef,
                        'bakong_hash' => $bakongHash,
                        'payway_tran_id' => $paywayTranId,
                        'payment_gateway' => $gateway,
                    ]
                );

                $sequence++;
            });
    }
}
