<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // D'abord, créez la table SANS la clé étrangère
        Schema::create('commande', function (Blueprint $table) {
            $table->id();
            $table->string('numero_commande')->unique();
            $table->unsignedBigInteger('fournisseur_id')->nullable();
            $table->dateTime('date_commande');
            $table->dateTime('date_reception')->nullable();
            $table->decimal('montant_total', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->enum('statut', ['en_attente', 'recue', 'annulee'])->default('en_attente');
            $table->timestamps();
            
            // Index simple d'abord
            $table->index('fournisseur_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commande');
    }
};