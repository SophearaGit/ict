<?php
namespace App\Console\Commands;
use App\Models\ICTCourse;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
class UpdateExpiredCoursesStatus extends Command
{
    protected $signature = 'courses:update-expired-status';
    protected $description = 'Set courses to draft once their end_date has passed';
    public function handle(): int
    {
        $expired = ICTCourse::where('status', 'active')
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<', now()->toDateString())
            ->get(['id']);
        if ($expired->isEmpty()) {
            $this->info('No expired courses found.');
            return self::SUCCESS;
        }
        ICTCourse::whereIn('id', $expired->pluck('id'))
            ->update(['status' => 'draft']);
        Log::info('Auto-drafted expired courses.', ['ids' => $expired->pluck('id')->toArray()]);
        $this->info("Updated {$expired->count()} course(s) to draft.");
        return self::SUCCESS;
    }
}
