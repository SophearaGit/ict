<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')->constrained('i_c_t_courses')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();

            // Attendance raw data
            $table->integer('present')->default(0);
            $table->integer('absent')->default(0);
            $table->integer('permission')->default(0);

            // Scores (0–100)
            $table->decimal('attendance_score', 5, 2)->default(0);
            $table->decimal('assignment_score', 5, 2)->default(0);
            $table->decimal('mini_project_score', 5, 2)->default(0);
            $table->decimal('final_project_score', 5, 2)->default(0);

            // Final result
            $table->decimal('total_score', 5, 2)->nullable();
            $table->enum('result', ['pass', 'fail'])->nullable();

            $table->string('remark')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_reports');
    }
};
