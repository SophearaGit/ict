<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        DB::statement("
            ALTER TABLE i_c_t_invoices
            MODIFY staff_id BIGINT UNSIGNED NULL
        ");
    }
    public function down(): void
    {
        DB::statement("
            ALTER TABLE i_c_t_invoices
            MODIFY staff_id BIGINT UNSIGNED NOT NULL
        ");
    }
};
