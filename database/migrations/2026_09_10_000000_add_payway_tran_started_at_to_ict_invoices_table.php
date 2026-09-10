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
            // When the current payway_tran_id's purchase request was sent to
            // PayWay. Needed because we now send a short `lifetime` (see
            // CourseEnrollmentController::PAYWAY_LIFETIME_MINUTES) on the
            // purchase payload — once that many minutes pass, PayWay
            // permanently rejects that tran_id ("Transaction is expired.
            // Please re-initiate the transaction.", Error Code: 68), even
            // though its own "Try Again" button just retries the same dead
            // tran_id and fails identically. paymentPage() reads this
            // column to know when it must mint a fresh tran_id instead of
            // reusing the expired one.
            $table->timestamp('payway_tran_started_at')->nullable()->after('payway_tran_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('i_c_t_invoices', function (Blueprint $table) {
            $table->dropColumn('payway_tran_started_at');
        });
    }
};
