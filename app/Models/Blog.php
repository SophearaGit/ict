<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class Blog extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'admin_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'type',
        'embed_url',
        'status',
        'is_featured',
        'views',
        'published_at',
        'meta_title',
        'meta_description',
    ];
    protected $casts = [
        'is_featured' => 'boolean',
        'views' => 'integer',
        'published_at' => 'datetime',
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function (Blog $blog) {
            if (empty($blog->slug)) {
                $blog->slug = static::generateUniqueSlug($blog->title);
            }
        });
    }
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
            ->where('published_at', '<=', now());
    }

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled' && $this->published_at?->isFuture();
    }
    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;
        $query = fn($s) => static::withTrashed()
            ->where('slug', $s)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists();
        while ($query($slug)) {
            $slug = "{$original}-{$count}";
            $count++;
        }
        return $slug;
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail ? asset(ltrim($this->thumbnail, '/')) : null;
    }
    public function getIsEmbedHtmlAttribute(): bool
    {
        if (empty($this->embed_url)) {
            return false;
        }
        return str_contains($this->embed_url, '<iframe')
            || str_contains($this->embed_url, 'tiktok-embed')
            || str_contains($this->embed_url, 'fb-video')
            || str_contains($this->embed_url, 'fb-post')
            || str_contains($this->embed_url, 'instagram-media');
    }
    public function getIsTiktokEmbedAttribute(): bool
    {
        return $this->embed_url && str_contains($this->embed_url, 'tiktok-embed');
    }
    public function getIsPortraitEmbedAttribute(): bool
    {
        if (empty($this->embed_url)) {
            return false;
        }
        // TikTok embeds are always vertical
        if ($this->is_tiktok_embed) {
            return true;
        }
        if (!str_contains($this->embed_url, '<iframe')) {
            return false;
        }
        preg_match('/(?:width|data-width)="(\d+)"/', $this->embed_url, $widthMatch);
        preg_match('/(?:height|data-height)="(\d+)"/', $this->embed_url, $heightMatch);
        $width = isset($widthMatch[1]) ? (int) $widthMatch[1] : null;
        $height = isset($heightMatch[1]) ? (int) $heightMatch[1] : null;
        if ($width && $height) {
            return $height > $width;
        }
        // Fallback: URL pattern check if attributes weren't found
        return str_contains($this->embed_url, '/reel/')
            || str_contains($this->embed_url, '/reels/');
    }
    // app/Models/Blog.php

    public function getEmbedUrlForDisplayAttribute(): ?string
    {
        if (empty($this->embed_url)) {
            return null;
        }

        // Force Facebook embeds to always show the caption/text
        return str_replace('show_text=false', 'show_text=true', $this->embed_url);
    }
}
