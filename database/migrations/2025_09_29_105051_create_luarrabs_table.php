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
        Schema::create('luarrabs', function (Blueprint $table) {
            $table->id();
            $table->string('luarrabps_id');
            $table->string('Item');
            $table->string('Needed_by')->nullable(); // ✅ bisa dikosongkan
            $table->unsignedBigInteger('workorder_id')->nullable(); // bisa dikosongkan juga
            $table->string('Qty');
            $table->string('Unit');
            $table->date('Date_actual');
            $table->string('Toko');
            $table->integer('Transaksi')->default(0);
            $table->string('Total');
            $table->timestamps();
            $table->foreign('workorder_id')->references('id')->on('workorders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('luarrabs');
    }
};
