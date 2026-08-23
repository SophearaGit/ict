<?php

namespace Database\Seeders;

use App\Models\ProjectCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Web Development',
            'Mobile App',
            'UX/UI Design',
            'Graphic Design',
        ];

        foreach ($categories as $index => $name) {
            ProjectCategory::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'is_active' => true,
                    'sort_order' => $index,
                ]
            );
        }

        $this->command?->info('Project categories seeded.');
    }
}
