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
          Schema::table('workorders', function (Blueprint $table) {

        // ================= LEFT SIDE =================
        $table->string('customer_name')->nullable();
        $table->text('address')->nullable();
        $table->string('phone_fax')->nullable();
        $table->string('contact_person')->nullable();
        $table->string('customer_po_no')->nullable();
        $table->date('customer_po_date')->nullable();
        $table->string('end_user')->nullable();
        $table->string('quotation_ref_no')->nullable();

        // ================= RIGHT SIDE =================
        $table->string('variation_no')->nullable();
        $table->date('wo_date')->nullable();
        $table->integer('quantity')->nullable();
        $table->date('chassis_available_date')->nullable();
        $table->date('delivery_date')->nullable();
        $table->string('delivery_point')->nullable();
        $table->text('delivery_instruction')->nullable();

        // ================= WORK DESCRIPTION =================
        $table->string('nama_produk')->nullable();
        $table->string('type_unit')->nullable();
        $table->text('pekerjaan_selesai')->nullable();
        $table->text('pekerjaan_termasuk')->nullable();
        $table->text('pekerjaan_tidak_termasuk')->nullable();
        $table->text('garansi')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workorders', function (Blueprint $table) {
            //
        });
    }
};
