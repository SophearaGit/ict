<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectObjective;
use App\Models\ProjectProcessStep;
use App\Models\ProjectScreenshot;
use App\Models\ProjectTechnology;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // Reuse whatever student/instructor already exist so this seeder doesn't
        // need to guess at your full Users schema. Projects stay valid either way
        // since student_id/instructor_id are nullable.
        $student = User::where('role', 'student')->inRandomOrder()->first();
        $instructor = User::where('role', 'instructor')->inRandomOrder()->first();

        if (! $student) {
            $this->command?->warn('No student user found — seeded projects will have no student assigned.');
        }
        if (! $instructor) {
            $this->command?->warn('No instructor user found — seeded projects will have no instructor assigned.');
        }

        $graphicDesign = ProjectCategory::where('slug', 'graphic-design')->first();
        $uxuiDesign = ProjectCategory::where('slug', 'ux-ui-design')->first();

        $this->seedProject([
            'category_id' => $graphicDesign?->id,
            'student_id' => $student?->id,
            'instructor_id' => $instructor?->id,
            'batch_label' => 'Batch 9',
            'title' => 'BrandForge — Identity Design System',
            'slug' => 'brandforge-identity-design-system',
            'excerpt' => 'Complete brand identity package for a startup.',
            'thumbnail' => 'https://placehold.co/400x250/1e293b/white?text=BrandForge',
            'cover_image' => 'https://placehold.co/1200x400/1e293b/white?text=BrandForge',
            'overview' => 'BrandForge delivers a cohesive brand identity: logo suite, typography, color, and guidelines for a fintech-adjacent startup entering a crowded market.',
            'problem_statement' => 'Startups often reinvent their identity across every channel, causing brand confusion and slowing time to market.',
            'challenges' => 'Creating a mark that scales cleanly from a favicon all the way up to a billboard.',
            'solutions' => 'Designed a modular geometric mark with responsive variants for every size and context.',
            'live_demo_url' => 'https://example.com/brandforge',
            'github_url' => null,
            'documentation_url' => null,
            'build_duration' => '4 month',
            'status' => 'published',
            'is_featured' => true,
            'featured_label' => 'First Place',
            'views' => 5290,
            'likes' => 803,
            'published_at' => now()->subMonths(2),
            'meta_title' => 'BrandForge — Identity Design System',
            'meta_description' => 'A cohesive brand identity system: logo suite, typography, color, and guidelines.',
        ], [
            'technologies' => ['Illustrator', 'Photoshop', 'Figma'],
            'objectives' => [
                'Reusable component library',
                'Accessible color system',
                'Dark & light themes',
            ],
            'process_steps' => [
                ['title' => 'Research', 'description' => 'Market & user research, requirement gathering.'],
                ['title' => 'Planning', 'description' => 'Scope, architecture, and sprint planning.'],
                ['title' => 'Design', 'description' => 'Wireframes, prototypes, and design system.'],
                ['title' => 'Development', 'description' => 'Core implementation and integrations.'],
            ],
            'screenshots' => [
                'https://placehold.co/800x600/334155/white?text=Screenshot+1',
                'https://placehold.co/800x600/334155/white?text=Screenshot+2',
                'https://placehold.co/800x600/334155/white?text=Screenshot+3',
            ],
        ]);

        $novaVariants = [
            ['tag' => 'Fintech Dashboard', 'color' => '4338ca'],
            ['tag' => 'Analytics Suite', 'color' => '0f766e'],
            ['tag' => 'Full Stack', 'color' => '9333ea'],
            ['tag' => 'Java Backend', 'color' => 'b91c1c'],
            ['tag' => 'Reports Module', 'color' => 'ca8a04'],
        ];

        foreach ($novaVariants as $index => $variant) {
            $n = $index + 1;

            $this->seedProject([
                'category_id' => $uxuiDesign?->id,
                'student_id' => $student?->id,
                'instructor_id' => $instructor?->id,
                'batch_label' => 'Batch 12',
                'title' => 'Nova Finance — Dashboard UI Kit',
                'slug' => "nova-finance-dashboard-ui-kit-{$n}",
                'excerpt' => "A modern fintech dashboard design system in Figma — {$variant['tag']} variant.",
                'thumbnail' => "https://placehold.co/400x250/{$variant['color']}/white?text=Nova+Finance",
                'cover_image' => "https://placehold.co/1200x400/{$variant['color']}/white?text=Nova+Finance",
                'overview' => 'Nova Finance is a complete design system and dashboard UI kit for fintech products, covering 40+ screens.',
                'problem_statement' => 'Fintech startups keep reinventing common dashboard patterns, slowing time to market.',
                'challenges' => 'Keeping a single design language consistent across 40+ dense data screens.',
                'solutions' => 'Built a token-based component library with light and dark themes from day one.',
                'live_demo_url' => 'https://example.com/nova-finance',
                'github_url' => 'https://github.com/example/nova-finance',
                'documentation_url' => null,
                'build_duration' => '3 month',
                'status' => 'published',
                'is_featured' => false,
                'featured_label' => null,
                'views' => 4800,
                'likes' => 612,
                'published_at' => now()->subMonths($n),
                'meta_title' => 'Nova Finance — Dashboard UI Kit',
                'meta_description' => 'A modern fintech dashboard design system in Figma.',
            ], [
                'technologies' => ['Figma', 'Illustrator'],
                'objectives' => [
                    'Reusable component library',
                    'Accessible color system',
                    'Dark & light themes',
                ],
                'process_steps' => [
                    ['title' => 'Research', 'description' => 'Competitive audit of fintech dashboards.'],
                    ['title' => 'Planning', 'description' => 'Information architecture and user flows.'],
                    ['title' => 'Design', 'description' => 'High-fidelity screens and interactive prototype.'],
                    ['title' => 'Development', 'description' => 'Design tokens handed off to engineering.'],
                ],
                'screenshots' => [
                    "https://placehold.co/800x600/{$variant['color']}/white?text=Dashboard+1",
                    "https://placehold.co/800x600/{$variant['color']}/white?text=Dashboard+2",
                ],
            ]);
        }

        $this->command?->info('Projects seeded: 6 total (1 BrandForge + 5 Nova Finance variants).');
    }

    /**
     * @param array<string, mixed> $attributes
     * @param array{technologies: string[], objectives: string[], process_steps: array<int, array{title: string, description: string}>, screenshots: string[]} $related
     */
    private function seedProject(array $attributes, array $related): void
    {
        $project = Project::updateOrCreate(
            ['slug' => $attributes['slug']],
            $attributes
        );

        ProjectTechnology::where('project_id', $project->id)->delete();
        foreach (array_values($related['technologies']) as $order => $name) {
            ProjectTechnology::create([
                'project_id' => $project->id,
                'name' => $name,
                'order' => $order,
            ]);
        }

        ProjectObjective::where('project_id', $project->id)->delete();
        foreach (array_values($related['objectives']) as $order => $content) {
            ProjectObjective::create([
                'project_id' => $project->id,
                'content' => $content,
                'order' => $order,
            ]);
        }

        ProjectProcessStep::where('project_id', $project->id)->delete();
        foreach (array_values($related['process_steps']) as $index => $step) {
            ProjectProcessStep::create([
                'project_id' => $project->id,
                'step_number' => $index + 1,
                'title' => $step['title'],
                'description' => $step['description'],
                'order' => $index + 1,
            ]);
        }

        ProjectScreenshot::where('project_id', $project->id)->delete();
        foreach (array_values($related['screenshots']) as $order => $imagePath) {
            ProjectScreenshot::create([
                'project_id' => $project->id,
                'image_path' => $imagePath,
                'order' => $order,
            ]);
        }
    }
}
