<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseLanguage;
use App\Models\CourseLevel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This is the separate self-paced "online course" builder
     * (App\Http\Controllers\Frontend\CourseController's multi-step
     * wizard, and Admin\CourseController's listing) — distinct from the
     * real-time/cohort ICTCourse system. It was previously seeded with
     * zero actual courses even though languages/levels/categories were
     * seeded for it, so the admin "Courses" and instructor "My Courses"
     * pages had nothing to show.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $instructors = User::where('role', 'instructor')->pluck('id')->all();
        $languageIds = CourseLanguage::pluck('id')->all();
        $levelIds = CourseLevel::pluck('id')->all();
        $categoryId = CourseCategory::where('slug', 'full-stack-web-development')->value('id')
            ?? CourseCategory::value('id');

        if (empty($instructors)) {
            $this->command?->warn('CourseSeeder: no instructor users found — run UserSeeder first.');
            return;
        }

        $courses = [
            ['title' => 'Complete Web Development Bootcamp', 'status' => 'active', 'is_approved' => 'approved'],
            ['title' => 'Modern JavaScript from Zero to Hero', 'status' => 'active', 'is_approved' => 'approved'],
            ['title' => 'Introduction to Databases', 'status' => 'inactive', 'is_approved' => 'approved'],
            ['title' => 'Building REST APIs with Laravel', 'status' => 'draft', 'is_approved' => 'pending'],
            ['title' => 'Freelancing for Developers', 'status' => 'draft', 'is_approved' => 'rejected'],
        ];

        foreach ($courses as $index => $data) {
            $instructorId = $instructors[$index % count($instructors)];
            $slug = Str::slug($data['title']);

            // Course has no $fillable/$guarded override, so it defaults to
            // fully guarded — Course::updateOrCreate()/::create() would
            // throw MassAssignmentException. The app itself works around
            // this by setting properties one at a time (see
            // Frontend\CourseController::storeBasicInfo); forceFill() here
            // does the same thing without listing every property by hand.
            $course = Course::where('slug', $slug)->first() ?? new Course();
            $course->forceFill([
                'instructor_id' => $instructorId,
                'category_id' => $categoryId,
                'course_type' => 'course',
                'title' => $data['title'],
                'slug' => $slug,
                'seo_description' => $faker->sentence(),
                'duration' => rand(10, 40) . ' hours',
                'thumbnail' => null,
                'demo_video_storage' => 'youtube',
                'demo_video_source' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'description' => $faker->paragraphs(3, true),
                'capacity' => rand(20, 100),
                'price' => $faker->randomElement([0, 19.99, 29.99, 49.99, 79.99]),
                'discount' => $index % 3 === 0 ? 10 : 0,
                'certificate' => true,
                'qna' => true,
                'message_for_reviewer' => $data['is_approved'] === 'pending' ? 'Please review — ready for publishing.' : null,
                'is_approved' => $data['is_approved'],
                'status' => $data['status'],
                'course_level_id' => $levelIds[$index % max(1, count($levelIds))] ?? null,
                'course_language_id' => $languageIds[$index % max(1, count($languageIds))] ?? null,
            ])->save();
        }
    }
}
