<?php

namespace Database\Seeders;

use App\Models\CourseLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $course_levels = [
            [
                'name' => 'Beginner',
                'slug' => 'beginner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intermediate',
                'slug' => 'intermediate',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Advanced',
                'slug' => 'advanced',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        CourseLevel::insert($course_levels);
    }
}
