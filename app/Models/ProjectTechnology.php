<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTechnology extends Model
{
    protected $fillable = ['project_id', 'name', 'order'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
