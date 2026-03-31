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
        Schema::create('teacher_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('i_c_t_courses')->onDelete('cascade');
            $table->foreignId('schedule_id')->constrained('i_c_t_schedules')->onDelete('cascade');

            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');

            // ✅ Just define normally (no after)
            $table->decimal('total_hours', 5, 2)->nullable();
            $table->decimal('actual_hours', 5, 2)->nullable();
            $table->string('room')->nullable();
            $table->string('signature')->nullable();

            $table->enum('status', ['present', 'absent', 'late']);
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_attendances');
    }
};
