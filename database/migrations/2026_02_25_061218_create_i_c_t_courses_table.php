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
        Schema::create('i_c_t_courses', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('instructor_id')->constrained('users');
            $table->foreignId('schedule_id')->constrained('i_c_t_schedules');
            $table->string('thumbnail')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->double('price')->nullable();
            $table->enum('status', ['active', 'inactive', 'draft'])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('i_c_t_courses');
    }
};
