<?php
namespace App\Console\Commands;
use App\Models\Blog;
use Illuminate\Console\Command;
class PublishScheduledBlogs extends Command
{
    protected $signature = 'blogs:publish-scheduled';
    protected $description = 'Publish blogs whose scheduled time has arrived';
    public function handle(): void
    {
        $count = Blog::scheduled()->update(['status' => 'published']);
        $this->info("Published {$count} scheduled blog(s).");
    }
}
