<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Admin;
class ICTStaffReport extends Model
{
    /** @use HasFactory<\Database\Factories\ICTStaffReportFactory> */
    use HasFactory;
    protected $fillable = [
        'reported_by',
        'report_content',
        'reviewed_by',
        'reviewed_at',
        'status',
    ];
    protected $casts = [
        'reviewed_at' => 'datetime',
    ];
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
    public function staff()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }
}
