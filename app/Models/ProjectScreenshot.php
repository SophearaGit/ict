<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectScreenshot extends Model
{
    protected $fillable = ['project_id', 'image_path', 'caption', 'order'];

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? asset(ltrim($this->image_path, '/')) : null;
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
