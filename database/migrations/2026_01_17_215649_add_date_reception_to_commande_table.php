<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Vérifiez d'abord si la colonne n'existe pas déjà
        if (!Schema::hasColumn('commande', 'date_reception')) {
            Schema::table('commande', function (Blueprint $table) {
                $table->dateTime('date_reception')->nullable()->after('date_commande');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('commande', 'date_reception')) {
            Schema::table('commande', function (Blueprint $table) {
                $table->dropColumn('date_reception');
            });
        }
    }
};