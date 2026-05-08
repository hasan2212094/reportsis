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
        Schema::create('rabpengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rab_id')->nullable()->constrained('pengajuannomors')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('workorder_id')->nullable()->constrained('workorders')->nullOnDelete();
            $table->enum('tipe_pengajuan', ['direct','indirect','luarrab'])->default('direct');
            $table->Text('kebutuhan');
            $table->string('nama_barang');
            $table->string('qty');
            $table->string('bank');
            $table->string('no_rek');
            $table->string('atas_nama');
            $table->string('nama_toko');
            $table->string('no_pr');
            $table->date('tanggal_pengajuan');
            $table->string('file_keterangan')->nullable();
            $table->string('invoice_file')->nullable();
            $table->date('tanggal_approved')->nullable();
            $table->string('image_buktibayar')->nullable();
            $table->string('nama_pt')->nullable();
            $table->string('note_reject')->nullable();
            $table->boolean('status_ppn');
            $table->integer('status_pengajuan')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rabpengajuans');
    }
};
