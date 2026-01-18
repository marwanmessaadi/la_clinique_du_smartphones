<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Vérifiez d'abord si la table fournisseur existe
        if (Schema::hasTable('fournisseur')) {
            Schema::table('commande', function (Blueprint $table) {
                $table->foreign('fournisseur_id')
                      ->references('id')
                      ->on('fournisseur')
                      ->onDelete('set null');
            });
        }
        // Ou si la table s'appelle fournisseurs
        elseif (Schema::hasTable('fournisseurs')) {
            Schema::table('commande', function (Blueprint $table) {
                $table->foreign('fournisseur_id')
                      ->references('id')
                      ->on('fournisseurs')
                      ->onDelete('set null');
            });
        }
    }

    public function down()
    {
        Schema::table('commande', function (Blueprint $table) {
            $table->dropForeign(['fournisseur_id']);
        });
    }
};