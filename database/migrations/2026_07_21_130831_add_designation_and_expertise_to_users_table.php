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
        Schema::table('users', function (Blueprint $table) {
            // e.g. "Senior Frontend Developer & UI/UX Specialist"
            $table->string('designation')->nullable()->after('headline');
            // e.g. ["HTML5 & Semantic Web", "CSS3, Flexbox & Grid", "React.js"]
            $table->json('expertise')->nullable()->after('designation');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['designation', 'expertise']);
        });
    }
};
