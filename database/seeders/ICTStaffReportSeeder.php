<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\ICTStaffReport;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ICTStaffReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * A few weekly reports per staff member, mostly reviewed (by an
     * admin — reviewed_by is a FK to `admins`, not `users`, per the
     * 2026_06_27_000001 migration) with a couple left pending so the
     * "reports.student/staff/intern" review screens (report.grant
     * middleware) have something waiting.
     */
    public function run(): void
    {
        $staff = User::where('role', 'staff')->pluck('id')->all();
        $adminId = Admin::orderBy('id')->value('id');

        if (empty($staff)) {
            $this->command?->warn('ICTStaffReportSeeder: no staff users found — run UserSeeder first.');
            return;
        }

        $highlights = [
            'Followed up with prospective students who inquired about upcoming batches.',
            'Assisted with student registration and invoice processing.',
            'Coordinated schedule changes with instructors for the new intake.',
            'Prepared materials for the open house event.',
            'Handled payment confirmations and receipt printing for enrolled students.',
            'Reviewed and updated course category listings on the website.',
        ];

        foreach ($staff as $staffId) {
            $weeksBack = 4;
            for ($w = $weeksBack; $w >= 1; $w--) {
                $periodStart = Carbon::now()->subWeeks($w)->startOfWeek();
                $periodEnd = $periodStart->copy()->endOfWeek();
                $isReviewed = $w > 1; // most recent week still pending review

                // ICTStaffReport::$fillable doesn't include 'period_start' /
                // 'period_end' (added by a later migration, never added to
                // $fillable) — updateOrCreate() would silently drop both,
                // including from the lookup key itself. forceFill() instead.
                $report = ICTStaffReport::where('reported_by', $staffId)
                    ->where('period_start', $periodStart->format('Y-m-d'))
                    ->first() ?? new ICTStaffReport();

                $report->forceFill([
                    'reported_by' => $staffId,
                    'report_content' => implode(' ', array_slice($highlights, 0, rand(2, 4))),
                    'period_start' => $periodStart->format('Y-m-d'),
                    'period_end' => $periodEnd->format('Y-m-d'),
                    'status' => $isReviewed ? 'reviewed' : 'pending',
                    'reviewed_by' => $isReviewed ? $adminId : null,
                    'reviewed_at' => $isReviewed ? $periodEnd->copy()->addDays(2) : null,
                ])->save();
            }
        }
    }
}
