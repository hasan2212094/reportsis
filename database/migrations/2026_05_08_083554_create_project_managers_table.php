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
        Schema::create('project_managers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workorder_id')->constrained('workorders');
            $table->string('workarea');
            $table->string('project');
            $table->string('user_pm');
            $table->decimal('qty');
            $table->date('target_date');
            $table->date('actualfinish_date')->nullable();
            $table->integer('status_pekerjaan')->default(0);
            $table->integer('persentase');
            $table->string('keterangan')->nullable();
            $table->string('task_name')->nullable();
            $table->string('pic')->nullable();
            $table->longText('activity_detail')->nullable();
            $table->date('bl_start')->nullable();
            $table->date('bl_finish')->nullable();
            $table->date('actual_start')->nullable();
            $table->integer('duration')->nullable();
            $table->integer('unit')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_managers');
    }
};
