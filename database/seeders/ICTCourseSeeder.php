<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ICTCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $courses = [];

        $courseTitles = [
            'Laravel Fundamentals',
            'Advanced Laravel',
            'PHP for Beginners',
            'JavaScript Essentials',
            'React JS Complete Guide',
            'Vue JS Masterclass',
            'Node.js Backend Development',
            'MySQL Database Design',
            'REST API Development',
            'Git & GitHub Workflow',
            'Bootstrap 5 Crash Course',
            'Tailwind CSS Mastery',
            'Web Security Basics',
            'Docker for Developers',
            'Linux Administration',
            'UI/UX Design Principles',
            'Flutter Mobile Development',
            'Python Programming',
            'Data Structures & Algorithms',
            'Full Stack Web Development',
        ];

        foreach ($courseTitles as $index => $title) {
            $courses[] = [
                'id' => $index + 1,
                'instructor_id' => 1,
                'schedule_id' => rand(1, 5),
                'category_id' => rand(1, 5),
                'thumbnail' => '/uploads/courses/thumbnails/course_' . ($index + 1) . '.jpg',
                'title' => $title,
                'khmer_title' => $faker->name(),
                'slug' => Str::slug($title),
                'description' => $faker->paragraph(4),
                'short_description' => $faker->sentence(),
                'level' => $faker->randomElement([
                    'Beginner',
                    'Intermediate',
                    'Advanced'
                ]),
                'intro_video' => null,
                'is_featured' => rand(0, 1),
                'meta_title' => $title,
                'meta_description' => $faker->sentence(),
                'price' => rand(100, 1000),
                'price_per_session' => rand(5, 20),
                'duration' => rand(10, 50),
                'status' => 'active',
                'start_date' => $faker->dateTimeBetween('-1 year', '+1 month')->format('Y-m-d'),
                'end_date' => $faker->dateTimeBetween('+2 months', '+3 years')->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ];
        }

        DB::table('i_c_t_courses')->insert($courses);
    }
}
