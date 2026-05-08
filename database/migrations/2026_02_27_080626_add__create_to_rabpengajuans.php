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
        Schema::table('rabpengajuans', function (Blueprint $table) {
            $table->string('nama_pt_partial')->nullable();
            $table->string('keterangan_partial')->nullable();
            $table->string('qty_partial')->nullable();
            $table->decimal('total_partial', 15, 2)->nullable()->after('qty_partial');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rabpengajuans', function (Blueprint $table) {
            //
        });
    }
};
