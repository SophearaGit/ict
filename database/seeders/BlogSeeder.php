<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * A handful of posts covering every status (draft/scheduled/
     * published) and both author types — Blog::boot() requires exactly
     * one of admin_id / staff_id to be set, never both, never neither.
     */
    public function run(): void
    {
        $adminId = Admin::orderBy('id')->value('id');
        $staffId = User::where('role', 'staff')->value('id');

        if (! $adminId && ! $staffId) {
            $this->command?->warn('BlogSeeder: no admin/staff users found — run AdminSeeder and UserSeeder first.');
            return;
        }

        $posts = [
            [
                'title' => 'Welcome to the New ICT Center Website',
                'excerpt' => 'We\'ve relaunched our website with a new course catalog, student portal, and more.',
                'content' => "<p>We're excited to launch our redesigned website, built to make it easier to browse courses, track your progress, and stay in touch with instructors.</p><p>Take a look around and let us know what you think!</p>",
                'author' => 'admin',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now()->subMonths(3),
            ],
            [
                'title' => '5 Tips for Getting the Most Out of Your Laravel Course',
                'excerpt' => 'Practical advice from our instructors on how to actually retain what you learn.',
                'content' => "<p>Learning a framework like Laravel can feel overwhelming at first. Here are five habits that consistently help our top students:</p><ol><li>Code along, don't just watch.</li><li>Rebuild mini-projects from scratch without notes.</li><li>Read the official docs, not just tutorials.</li><li>Ask questions early — don't sit on confusion.</li><li>Ship something small every week.</li></ol>",
                'author' => 'staff',
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now()->subMonths(2),
            ],
            [
                'title' => 'New Batch: Advanced Laravel Starts Next Month',
                'excerpt' => 'Registration is now open for our next Advanced Laravel cohort.',
                'content' => "<p>Our next Advanced Laravel batch kicks off next month, covering queues, events, testing, and deployment. Seats are limited — register early.</p>",
                'author' => 'staff',
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now()->subMonth(),
            ],
            [
                'title' => 'Student Spotlight: From Beginner to Junior Developer',
                'excerpt' => 'How one of our graduates landed their first developer job after finishing our program.',
                'content' => "<p>We sat down with one of our recent graduates to talk about their journey from complete beginner to landing a junior developer role in under a year.</p>",
                'author' => 'admin',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now()->subWeeks(2),
            ],
            [
                'title' => 'Upcoming: Free Web Security Workshop',
                'excerpt' => 'Join our free one-day workshop on the OWASP Top 10 and how to defend against them.',
                'content' => "<p>Mark your calendar — we're running a free one-day workshop on common web vulnerabilities and how to defend against them, open to current and prospective students.</p>",
                'author' => 'staff',
                'status' => 'scheduled',
                'is_featured' => false,
                'published_at' => now()->addWeek(),
            ],
            [
                'title' => 'Draft: End-of-Year Center Update',
                'excerpt' => 'A recap of everything that happened this year — still being written.',
                'content' => "<p>Draft notes: enrollment numbers, new courses launched, staff additions, plans for next year...</p>",
                'author' => 'admin',
                'status' => 'draft',
                'is_featured' => false,
                'published_at' => null,
            ],
        ];

        foreach ($posts as $post) {
            $slug = Str::slug($post['title']);

            Blog::updateOrCreate(
                ['slug' => $slug],
                [
                    'admin_id' => $post['author'] === 'admin' ? $adminId : null,
                    'staff_id' => $post['author'] === 'staff' ? $staffId : null,
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'content' => $post['content'],
                    'thumbnail' => null,
                    'type' => 'article',
                    'embed_url' => null,
                    'status' => $post['status'],
                    'is_featured' => $post['is_featured'],
                    'views' => $post['status'] === 'published' ? rand(20, 800) : 0,
                    'published_at' => $post['published_at'],
                    'meta_title' => $post['title'],
                    'meta_description' => $post['excerpt'],
                ]
            );
        }
    }
}
