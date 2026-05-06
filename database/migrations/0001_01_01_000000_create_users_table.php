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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('image')->default('no-img.jpg');
            $table->string('name');
            $table->string('khmer_name')->nullable();
            $table->string('email')->unique();
            $table->text(column: 'bio')->nullable()->comment('I am a student at ICT. I love learning new things and sharing my knowledge with others.');
            $table->enum('gender', ['male', 'female'])->nullable()->default('male');
            $table->date('dob')->nullable()->default('2000-01-01');
            $table->string('nationality')->nullable()->default('Khmer');
            $table->string('document')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['instructor', 'student', 'staff', 'unknown'])->default('student');
            $table->string(column: 'phone')->nullable();
            $table->string(column: 'alternate_phone')->nullable();
            $table->string(column: 'headline')->nullable();
            $table->string(column: 'facebook')->nullable();
            $table->string(column: 'x')->nullable();
            $table->string(column: 'linkedin')->nullable();
            $table->string(column: 'website')->nullable();
            $table->string(column: 'github')->nullable();
            $table->string(column: 'instagram')->nullable();
            $table->string(column: 'youtube')->nullable();
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('status', ['active', 'on_leave'])->default('active');
            $table->string(column: 'location')->nullable();
            $table->enum('login_as', ['student', 'instructor'])->nullable();
            $table->foreignId('registered_by_staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table): void {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table): void {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
