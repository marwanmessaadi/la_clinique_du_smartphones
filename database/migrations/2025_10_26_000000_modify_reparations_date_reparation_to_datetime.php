<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Note: this migration uses ->change() and requires doctrine/dbal:
     * composer require doctrine/dbal
     */
    public function up(): void
    {
        Schema::table('reparations', function (Blueprint $table) {
            // change column to datetime (nullable)
            $table->dateTime('date_reparation')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reparations', function (Blueprint $table) {
            // revert back to date (nullable)
            $table->date('date_reparation')->nullable()->change();
        });
    }
};