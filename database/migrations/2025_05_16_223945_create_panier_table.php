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
        Schema::create('panier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')  // Au lieu de 'client_id'
                ->constrained('utilisateurs')
                ->onDelete('cascade');
            $table->foreignId('produit_id')
                ->constrained('produits')
                ->onDelete('cascade');
            $table->integer('quantite')
                ->default(1);
            $table->decimal('prix_unitaire', 10, 2)
                ->nullable(false);
            $table->decimal('prix_total', 10, 2)
                ->virtualAs('quantite * prix_unitaire');
            $table->timestamps();

            // Add unique constraint to prevent duplicate items for same client
            $table->unique(['utilisateur_id', 'produit_id']);  // Mettez à jour la contrainte unique
            
            // Add indexes for better query performance
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panier');
    }
};
