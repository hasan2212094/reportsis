<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('project_manager_tasks', function (Blueprint $table) {

        for ($i = 1; $i <= 20; $i++) {

            $table->integer('unit_'.$i)
                ->default(0);

        }

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_manager_tasks', function (Blueprint $table) {
            //
        });
    }
};
