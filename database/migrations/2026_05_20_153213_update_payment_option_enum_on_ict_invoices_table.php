<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE i_c_t_invoices
            MODIFY payment_option
            ENUM('full', 'half', 'multi', 'normal', 'free', 'other')
            NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE i_c_t_invoices
            MODIFY payment_option
            ENUM('full', 'half', 'multi', 'normal', 'free')
            NULL
        ");
    }

};
