<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectProcessStep extends Model
{
    protected $fillable = ['project_id', 'step_number', 'title', 'description', 'order'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
