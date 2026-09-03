<?php

namespace Database\Seeders;

use App\Models\ICTCourse;
use App\Models\ICTCourseLearningPoint;
use Illuminate\Database\Seeder;

class ICTCourseLearningPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $points = [
            'Build real, portfolio-ready projects from scratch',
            'Understand the core concepts, not just the syntax',
            'Follow industry best practices used by working developers',
            'Get comfortable reading and debugging real-world code',
            'Learn how the pieces fit into a full project workflow',
        ];

        ICTCourse::orderBy('id')->each(function (ICTCourse $course) use ($points): void {
            foreach (array_slice($points, 0, 4) as $order => $content) {
                ICTCourseLearningPoint::updateOrCreate(
                    ['course_id' => $course->id, 'content' => $content],
                    ['order' => $order + 1, 'status' => true]
                );
            }
        });
    }
}
