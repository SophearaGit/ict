<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('i_c_t_course_learning_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')
                ->constrained('i_c_t_courses')
                ->cascadeOnDelete();
            $table->string('content');
            $table->unsignedInteger('order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['course_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('i_c_t_course_learning_points');
    }
};
