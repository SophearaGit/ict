<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->foreignId('staff_id')->nullable()->after('admin_id')
                ->constrained('users')->restrictOnDelete();

            $table->foreignId('admin_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('staff_id');
            $table->foreignId('admin_id')->nullable(false)->change();
        });
    }
};
