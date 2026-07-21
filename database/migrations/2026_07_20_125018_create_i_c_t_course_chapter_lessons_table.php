<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('i_c_t_course_chapter_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('i_c_t_course_chapters')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('i_c_t_courses')->onDelete('cascade');
            $table->string('title');
            $table->unsignedInteger('order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('i_c_t_course_chapter_lessons');
    }
};
