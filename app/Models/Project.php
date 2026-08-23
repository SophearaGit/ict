<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'student_id',
        'instructor_id',
        'batch_label',
        'title',
        'slug',
        'excerpt',
        'thumbnail',
        'cover_image',
        'overview',
        'problem_statement',
        'challenges',
        'solutions',
        'live_demo_url',
        'github_url',
        'documentation_url',
        'build_duration',
        'status',
        'is_featured',
        'featured_label',
        'views',
        'likes',
        'published_at',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'views' => 'integer',
        'likes' => 'integer',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Project $project) {
            if (empty($project->slug)) {
                $project->slug = static::generateUniqueSlug($project->title);
            }
        });
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;
        $query = fn ($s) => static::withTrashed()
            ->where('slug', $s)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();
        while ($query($slug)) {
            $slug = "{$original}-{$count}";
            $count++;
        }
        return $slug;
    }

    // ---------------------------------------------------------------
    // Relations
    // ---------------------------------------------------------------

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function technologies(): HasMany
    {
        return $this->hasMany(ProjectTechnology::class)->orderBy('order');
    }

    public function objectives(): HasMany
    {
        return $this->hasMany(ProjectObjective::class)->orderBy('order');
    }

    public function processSteps(): HasMany
    {
        return $this->hasMany(ProjectProcessStep::class)->orderBy('step_number');
    }

    public function screenshots(): HasMany
    {
        return $this->hasMany(ProjectScreenshot::class)->orderBy('order');
    }

    // ---------------------------------------------------------------
    // Scopes & accessors
    // ---------------------------------------------------------------

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->thumbnail);
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->cover_image);
    }

    private function resolveImageUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        return str_starts_with($path, 'http://') || str_starts_with($path, 'https://')
            ? $path
            : asset(ltrim($path, '/'));
    }

    // e.g. "5,290" for the views counter on the detail page
    public function getFormattedViewsAttribute(): string
    {
        return number_format($this->views);
    }

    // e.g. "4.8k" for the compact card counter
    public function getShortViewsAttribute(): string
    {
        return $this->views >= 1000
            ? round($this->views / 1000, 1) . 'k'
            : (string) $this->views;
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }
}
