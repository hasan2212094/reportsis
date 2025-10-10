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
       Schema::table('directas', function (Blueprint $table) {
        $table->unsignedBigInteger('workorder_id')->nullable()->after('id');
      });

       Schema::table('ppnas', function (Blueprint $table) {
        $table->unsignedBigInteger('workorder_id')->nullable()->after('id');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          Schema::table('directas', function (Blueprint $table) {
        $table->dropColumn('workorder_id');
    });

    Schema::table('ppnas', function (Blueprint $table) {
        $table->dropColumn('workorder_id');
    });
    }
};
