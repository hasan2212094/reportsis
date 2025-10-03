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
        Schema::create('ppns', function (Blueprint $table) {
            $table->id();
            $table->string('item_id');
            $table->string('Item');
            $table->string('Qty');
            $table->string('Unit');
            $table->string('Needed_by');
            $table->date('Date_pengajuan');
            $table->string('Total');
            $table->text('Notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppns');
    }
};
