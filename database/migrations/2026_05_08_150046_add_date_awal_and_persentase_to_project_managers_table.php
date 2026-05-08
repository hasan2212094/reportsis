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
        Schema::table('project_managers', function (Blueprint $table) {
            $table->date('date_awal')->nullable();
            $table->date('date_akhir')->nullable();
            $table->integer('persentase_A')->nullable();
            $table->integer('persentase_B')->nullable();
            $table->integer('persentase_C')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_managers', function (Blueprint $table) {
            $table->dropColumn(['date_awal','date_akhir','persentase_A','persentase_B','persentase_C']);
        });
    }
};
