<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class InternReport extends Model
{
    /** @use HasFactory<\Database\Factories\InternReportFactory> */
    use HasFactory;
    protected $table = 'intern_reports';
    protected $fillable = [
        'reported_by',
        'report_date',
        'report_content',
        'status',
        'reviewed_by',
        'reviewed_at',
    ];
    protected $casts = [
        'report_date' => 'date',
        'reviewed_at' => 'datetime',
    ];
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
