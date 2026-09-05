<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the column that links a user to their Google account for
 * "Continue with Google" sign-in/registration.
 *
 * Existing avatar/profile-picture storage is already handled by the
 * `image` column (default 'no-img.jpg') — Google's profile photo URL is
 * simply stored there for a new account, so no separate avatar column
 * is added here.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->unique()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('google_id');
        });
    }
};

