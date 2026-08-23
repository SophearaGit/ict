<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('category_id')->nullable()
                ->constrained('project_categories')->nullOnDelete();
            $table->foreignId('student_id')->nullable()
                ->constrained('users')->nullOnDelete(); // project owner/author
            $table->foreignId('instructor_id')->nullable()
                ->constrained('users')->nullOnDelete(); // reviewing/credited instructor
            $table->string('batch_label')->nullable(); // e.g. "Batch 12"

            // Card / hero
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt')->nullable(); // short card blurb
            $table->string('thumbnail')->nullable();
            $table->string('cover_image')->nullable();

            // Detail page content
            $table->text('overview')->nullable();
            $table->text('problem_statement')->nullable();
            $table->text('challenges')->nullable();
            $table->text('solutions')->nullable();

            // Links
            $table->string('live_demo_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('documentation_url')->nullable();

            // Meta / display
            $table->string('build_duration')->nullable(); // e.g. "4 month"
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->string('featured_label')->nullable(); // e.g. "First Place"
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('likes')->default(0);
            $table->timestamp('published_at')->nullable();

            // SEO
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
