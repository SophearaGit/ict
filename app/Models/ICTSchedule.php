<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ICTSchedule extends Model
{
    /** @use HasFactory<\Database\Factories\ICTScheduleFactory> */
    use HasFactory;

  

    public function courses(): HasMany
    {
        return $this->hasMany(ICTCourse::class, 'schedule_id');
    }

}
