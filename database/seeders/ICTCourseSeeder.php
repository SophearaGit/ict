<?php

namespace Database\Seeders;

use App\Models\ICTCourse;
use App\Models\ICTCourseCategory;
use App\Models\ICTSchedule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ICTCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Fixes vs. the original version of this seeder:
     *  - 'level' is now lowercase ('beginner'/'intermediate'/'advanced') to
     *    match the enum on i_c_t_courses.level — the old faker-random
     *    capitalised values ('Beginner', ...) would violate it.
     *  - instructor_id / category_id / schedule_id are looked up instead of
     *    hardcoded (instructor_id was fixed at 1, category_id/schedule_id
     *    used rand(1,5) even though 6 categories / 10 schedules now exist).
     *  - a few titles get a second "batch" (different schedule/instructor,
     *    same title) so the frontend's batch-grouping UI has something to
     *    group.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $instructors = User::where('role', 'instructor')->orderBy('id')->pluck('id')->all();
        $schedules = ICTSchedule::orderBy('id')->pluck('id')->all();
        $categoryIdsBySlug = ICTCourseCategory::pluck('id', 'slug');

        if (empty($instructors) || empty($schedules) || $categoryIdsBySlug->isEmpty()) {
            $this->command?->warn('ICTCourseSeeder: missing instructors/schedules/categories — run UserSeeder, ICTScheduleSeeder, and ICTCourseCategorySeeder first.');
            return;
        }

        // title => [category slug, level, base price, featured?, status]
        $courses = [
            'Laravel Fundamentals' => ['programming', 'beginner', 180, true, 'active'],
            'Advanced Laravel' => ['programming', 'advanced', 220, false, 'active'],
            'PHP for Beginners' => ['programming', 'beginner', 120, false, 'draft'],
            'JavaScript Essentials' => ['website', 'beginner', 140, false, 'active'],
            'React JS Complete Guide' => ['website', 'intermediate', 200, true, 'active'],
            'Vue JS Masterclass' => ['website', 'intermediate', 190, false, 'active'],
            'Node.js Backend Development' => ['programming', 'intermediate', 200, false, 'active'],
            'MySQL Database Design' => ['data', 'beginner', 150, false, 'active'],
            'REST API Development' => ['programming', 'intermediate', 170, false, 'active'],
            'Git & GitHub Workflow' => ['programming', 'beginner', 80, false, 'active'],
            'Bootstrap 5 Crash Course' => ['website', 'beginner', 90, false, 'active'],
            'Tailwind CSS Mastery' => ['website', 'beginner', 100, false, 'active'],
            'Web Security Basics' => ['networking', 'intermediate', 160, false, 'active'],
            'Docker for Developers' => ['networking', 'intermediate', 180, false, 'active'],
            'Linux Administration' => ['networking', 'intermediate', 170, false, 'draft'],
            'UI/UX Design Principles' => ['design', 'beginner', 150, true, 'active'],
            'Flutter Mobile Development' => ['website', 'advanced', 220, false, 'active'],
            'Python Programming' => ['programming', 'beginner', 130, false, 'active'],
            'Data Structures & Algorithms' => ['data', 'advanced', 210, true, 'active'],
            'Full Stack Web Development' => ['website', 'advanced', 260, false, 'inactive'],
        ];

        // Titles that get a second batch — different schedule & instructor,
        // same title, so the "choose a batch" UI on the course details page
        // has more than one option.
        $extraBatches = ['Advanced Laravel', 'Tailwind CSS Mastery', 'Data Structures & Algorithms'];

        $index = 0;
        foreach ($courses as $title => [$categorySlug, $level, $price, $featured, $status]) {
            $categoryId = $categoryIdsBySlug[$categorySlug] ?? $categoryIdsBySlug->first();

            $this->seedCourse($title, $index, $categoryId, $level, $price, $featured, $status, $instructors, $schedules, $faker);
            $index++;

            if (in_array($title, $extraBatches, true)) {
                $this->seedCourse($title, $index, $categoryId, $level, $price, false, $status, $instructors, $schedules, $faker, batch: 2);
                $index++;
            }
        }
    }

    /**
     * @param int[] $instructors
     * @param int[] $schedules
     */
    private function seedCourse(
        string $title,
        int $index,
        int $categoryId,
        string $level,
        float $price,
        bool $featured,
        string $status,
        array $instructors,
        array $schedules,
        \Faker\Generator $faker,
        int $batch = 1,
    ): void {
        $slug = Str::slug($title) . ($batch > 1 ? '-batch-' . $batch : '');
        $duration = $faker->numberBetween(20, 48); // hours
        $startDate = $faker->dateTimeBetween('-4 months', '+1 month');
        $endDate = (clone $startDate)->modify('+' . $duration . ' hours')->modify('+' . intdiv($duration, 6) . ' days');

        $course = ICTCourse::updateOrCreate(
            ['slug' => $slug],
            [
                'instructor_id' => $instructors[$index % count($instructors)],
                'schedule_id' => $schedules[$index % count($schedules)],
                'category_id' => $categoryId,
                'thumbnail' => null,
                'title' => $title,
                'khmer_title' => null,
                'description' => $faker->paragraphs(3, true),
                'price' => $price,
                'price_per_session' => round($price / max(1, intdiv($duration, 3)), 2),
                'status' => $status,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'duration' => $duration,
                'capacity' => $faker->numberBetween(15, 30),
                'telegram_group_link' => 'https://t.me/ict_' . Str::slug($title, '_'),
                'featured' => $featured,
            ]
        );

        // Columns that exist on the table but aren't in ICTCourse::$fillable.
        $course->forceFill([
            'short_description' => $faker->sentence(12),
            'level' => $level,
            'is_featured' => $featured,
            'meta_title' => $title,
            'meta_description' => $faker->sentence(),
        ])->save();
    }
}
