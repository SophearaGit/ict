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
        Schema::create('i_c_t_course_enrollments', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('course_id')
                ->constrained('i_c_t_courses')
                ->cascadeOnDelete();

            $table->foreignId('enrolled_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('status', ['active', 'completed', 'dropped'])
                ->default('active');

            $table->timestamp('enrolled_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('i_c_t_course_enrollments');
    }
};
