<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\InternReport;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class InternReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * A few weekly reports per intern. Reviewed ones alternate between
     * being reviewed by an admin and by a staff member, since the model
     * supports either (reviewed_by_admin_id / reviewed_by_staff_id).
     */
    public function run(): void
    {
        $interns = User::where('role', 'intern')->pluck('id')->all();
        $adminId = Admin::orderBy('id')->value('id');
        $staffId = User::where('role', 'staff')->value('id');

        if (empty($interns)) {
            $this->command?->warn('InternReportSeeder: no intern users found — run UserSeeder first.');
            return;
        }

        $highlights = [
            'Shadowed the instructor during the Laravel Fundamentals session.',
            'Helped debug student assignments during the practice lab.',
            'Documented setup steps for the new development environment.',
            'Attended the weekly team sync and took notes.',
            'Worked on a small internal tool under staff supervision.',
        ];

        foreach ($interns as $index => $internId) {
            for ($w = 3; $w >= 1; $w--) {
                $periodStart = Carbon::now()->subWeeks($w)->startOfWeek();
                $periodEnd = $periodStart->copy()->endOfWeek();
                $isReviewed = $w > 1;
                $reviewedByAdmin = $isReviewed && $index % 2 === 0;
                $reviewedByStaff = $isReviewed && $index % 2 !== 0;

                InternReport::updateOrCreate(
                    ['reported_by' => $internId, 'period_start' => $periodStart->format('Y-m-d')],
                    [
                        // NOTE: InternReport::$fillable lists 'report_date', but no
                        // migration actually adds that column to intern_reports —
                        // inserting it throws "no such column" on both SQLite and
                        // MySQL. Left out here; flagged separately for the app code.
                        'report_content' => implode(' ', array_slice($highlights, 0, rand(2, 3))),
                        'period_end' => $periodEnd->format('Y-m-d'),
                        'status' => $isReviewed ? 'reviewed' : 'pending',
                        'reviewed_by_admin_id' => $reviewedByAdmin ? $adminId : null,
                        'reviewed_by_staff_id' => $reviewedByStaff ? $staffId : null,
                        'reviewed_at' => $isReviewed ? $periodEnd->copy()->addDays(2) : null,
                    ]
                );
            }
        }
    }
}
