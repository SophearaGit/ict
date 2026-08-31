<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('i_c_t_invoices', function (Blueprint $table) {
            // PayWay transaction reference — generated once per invoice and
            // reused on retry so the tran_id sent to PayWay stays stable.
            $table->string('payway_tran_id')->nullable()->unique()->after('invoice_code');

            // Which gateway settled this invoice. Defaults to payway going
            // forward; existing rows paid via Bakong will just keep whatever
            // is already in payment_status / paid_at.
            $table->string('payment_gateway')->nullable()->after('payway_tran_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('i_c_t_invoices', function (Blueprint $table) {
            $table->dropColumn(['payway_tran_id', 'payment_gateway']);
        });
    }
};
