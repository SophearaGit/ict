<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    public function instructor(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'instructor_id');
    }

    public function level(): HasOne
    {
        return $this->hasOne(CourseLevel::class, 'id', 'course_level_id');
    }


}
