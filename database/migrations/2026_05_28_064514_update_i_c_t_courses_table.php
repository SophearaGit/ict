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
        Schema::table('i_c_t_courses', function (Blueprint $table): void {
            /*
            |--------------------------------------------------------------------------
            | Extra Course Information
            |--------------------------------------------------------------------------
            */
            $table->string('short_description')->nullable()->after('description');
            $table
                ->enum('level', ['beginner', 'intermediate', 'advanced'])
                ->nullable()
                ->after('short_description');
            // intro/trailer video only
            $table->string('intro_video')->nullable()->after('level');
            $table->boolean('is_featured')->default(false)->after('intro_video');
            /*
            |--------------------------------------------------------------------------
            | SEO
            |--------------------------------------------------------------------------
            */
            $table->string('meta_title')->nullable()->after('is_featured');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('i_c_t_courses', function (Blueprint $table): void {
            $table->dropColumn(['category_id', 'short_description', 'level', 'intro_video', 'is_featured', 'meta_title', 'meta_description', 'deleted_at']);
        });
    }
};
