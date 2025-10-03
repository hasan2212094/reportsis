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
        Schema::create('ppnas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppns_id')->constrained('ppns')->cascadeOnDelete();
            $table->string('Qty');
            $table->string('Unit');
            $table->date('Date_actual');
            $table->string('Toko');
            $table->integer('Transaksi')->default(0);
            $table->string('Total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppnas');
    }
};
