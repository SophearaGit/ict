<?php

namespace Database\Seeders;

use App\Models\ICTCourse;
use App\Models\ICTCourseRequirement;
use Illuminate\Database\Seeder;

class ICTCourseRequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requirements = [
            'A laptop or desktop computer (Windows, macOS, or Linux)',
            'A stable internet connection for online resources',
            'Basic computer literacy — no prior programming experience assumed unless noted',
            'Willingness to practice outside of class hours',
        ];

        ICTCourse::orderBy('id')->each(function (ICTCourse $course) use ($requirements): void {
            foreach ($requirements as $order => $content) {
                ICTCourseRequirement::updateOrCreate(
                    ['course_id' => $course->id, 'content' => $content],
                    ['order' => $order + 1, 'status' => true]
                );
            }
        });
    }
}
