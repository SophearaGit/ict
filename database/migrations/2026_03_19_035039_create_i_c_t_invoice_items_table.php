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
        Schema::create('i_c_t_invoice_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invoice_id')->constrained('i_c_t_invoices')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('i_c_t_courses');

            $table->double('price');
            $table->double('discount')->default(0);
            $table->double('extra_charge')->default(0);

            $table->double('total');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('i_c_t_invoice_items');
    }
};
