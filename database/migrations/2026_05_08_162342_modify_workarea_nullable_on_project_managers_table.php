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
        $table->string('workarea')->nullable()->change();
        $table->string('project')->nullable()->change();
        $table->string('user_pm')->nullable()->change();
        $table->decimal('qty')->nullable()->change();
        $table->date('target_date')->nullable()->change();
        $table->integer('persentase')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_managers', function (Blueprint $table) {
        $table->string('workarea')->nullable(false)->change();
        $table->string('project')->nullable(false)->change();
        $table->string('user_pm')->nullable(false)->change();
        $table->decimal('qty')->nullable(false)->change();
        $table->date('target_date')->nullable(false)->change();
        $table->integer('persentase')->nullable(false)->change();
        });
    }
};
