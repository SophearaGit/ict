<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\ICTCourseCategory;

class ICTCourseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Website',
                'slug' => 'website',
                'description' => 'Learn to build modern websites using HTML, CSS, JavaScript, and popular frameworks.',
                'icon' => 'ti ti-world-www',
                'thumbnail' => null,
                'parent_id' => null,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
                'meta_title' => 'Website Development Courses',
                'meta_description' => 'Explore our website development courses and build real-world web projects.',
            ],
            [
                'name' => 'Design',
                'slug' => 'design',
                'description' => 'Master UI/UX design, graphic design, and visual communication with industry tools.',
                'icon' => 'ti ti-palette',
                'thumbnail' => null,
                'parent_id' => null,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
                'meta_title' => 'Design Courses',
                'meta_description' => 'Learn UI/UX, graphic design, and creative skills from professional designers.',
            ],
            [
                'name' => 'Programming',
                'slug' => 'programming',
                'description' => 'From beginner to advanced — learn programming fundamentals, algorithms, and software development.',
                'icon' => 'ti ti-code',
                'thumbnail' => null,
                'parent_id' => null,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 3,
                'meta_title' => 'Programming Courses',
                'meta_description' => 'Start your programming journey with structured, hands-on coding courses.',
            ],
            [
                'name' => 'Data',
                'slug' => 'data',
                'description' => 'Explore data analysis, data science, business intelligence, and machine learning fundamentals.',
                'icon' => 'ti ti-chart-bar',
                'thumbnail' => null,
                'parent_id' => null,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 4,
                'meta_title' => 'Data Science & Analytics Courses',
                'meta_description' => 'Turn raw data into insights with our data science and analytics courses.',
            ],
            [
                'name' => 'Office',
                'slug' => 'office',
                'description' => 'Boost your productivity with Microsoft Office, Google Workspace, and essential office software skills.',
                'icon' => 'ti ti-file-text',
                'thumbnail' => null,
                'parent_id' => null,
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 5,
                'meta_title' => 'Office Skills Courses',
                'meta_description' => 'Master Microsoft Office and Google Workspace for workplace productivity.',
            ],
            [
                'name' => 'Networking',
                'slug' => 'networking',
                'description' => 'Understand computer networks, protocols, cybersecurity, and IT infrastructure from the ground up.',
                'icon' => 'ti ti-network',
                'thumbnail' => null,
                'parent_id' => null,
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 6,
                'meta_title' => 'Networking & IT Courses',
                'meta_description' => 'Learn networking fundamentals, protocols, and cybersecurity concepts.',
            ],
        ];

        foreach ($categories as $category) {
            ICTCourseCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
