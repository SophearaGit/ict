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
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('course_id')->constrained('i_c_t_courses')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();

            // Attendance Info
            $table->date('date');

            $table->enum('status', ['present', 'absent', 'late'])
                ->default('present');

            $table->string('note')->nullable();

            $table->timestamps();

            // 🚀 Prevent duplicate attendance
            $table->unique(['course_id', 'student_id', 'date']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_attendances');
    }
};
