<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ICTCourseCategory extends Model
{
    /** @use HasFactory<\Database\Factories\ICTCourseCategoryFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $table = 'i_c_t_course_categories';

    protected $fillable = [
        // Basic Information
        'name',
        'slug',
        'description',

        // Media
        'icon',
        'thumbnail',

        // Category Structure
        'parent_id',

        // Status & Display
        'is_active',
        'is_featured',
        'sort_order',

        // SEO
        'meta_title',
        'meta_description',

        // Tracking
        'created_by',
        'updated_by',
    ];

    public function parent()
    {
        return $this->belongsTo(ICTCourseCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ICTCourseCategory::class, 'parent_id')
            ->where('is_active', 1)
            ->orderBy('sort_order');
    }

    public function courses()
    {
        return $this->hasMany(ICTCourse::class, 'category_id')
            ->where('status', 1);
    }

}
