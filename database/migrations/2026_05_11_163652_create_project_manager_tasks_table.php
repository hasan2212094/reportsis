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
        Schema::create('project_manager_tasks', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('project_manager_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('task_name')->nullable();

            $table->string('pic')->nullable();

            $table->longText('activity_detail')->nullable();

            $table->date('bl_start')->nullable();

            $table->date('bl_finish')->nullable();

            $table->date('act_start')->nullable();

            $table->date('act_finish')->nullable();

            $table->integer('duration')->default(0);

            $table->string('priority')->nullable();

            $table->integer('percentage')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_manager_tasks');
    }
};
