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
        Schema::create('direct_p_s', function (Blueprint $table) {
            $table->id();
            $table->string('item_id');
            $table->string('Item');
            $table->string('Qty');
            $table->string('Unit');
            $table->string('Needed_by')->nullable(); // ✅ bisa dikosongkan
            $table->unsignedBigInteger('workorder_id')->nullable(); // bisa dikosongkan juga
            $table->date('Date_pengajuan');
            $table->string('Total');
            $table->text('Notes')->nullable();
            $table->timestamps();
            $table->foreign('workorder_id')->references('id')->on('workorders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direct_p_s');
    }
};
