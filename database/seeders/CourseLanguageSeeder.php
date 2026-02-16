<?php

namespace Database\Seeders;

use App\Models\CourseLanguage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseLanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $course_languages = [
            [
                'name' => 'English',
                'slug' => 'english',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Khmer',
                'slug' => 'khmer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        CourseLanguage::insert($course_languages);
    }
}
