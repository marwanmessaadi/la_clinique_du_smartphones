<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->decimal('prix_achat', 10, 2);
            $table->decimal('prix_vente', 10, 2);
            $table->decimal('prix_gros', 10, 2);
            $table->integer('quantite')->default(0)->unsigned();
            $table->string('image')->nullable();
            $table->enum('etat', ['disponible', 'indisponible'])->default('disponible');
            $table->enum('type', ['achat', 'vente'])->default('achat');
            $table->foreignId('categorie_id')
                ->constrained('categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('fournisseur_id')
                ->nullable()
                ->constrained('fournisseurs')  // Correction : 'fournisseurs' au lieu de 'fournisseur'
                ->onDelete('set null')        // Correction : 'set null' au lieu de 'cascade'
                ->onUpdate('cascade');
            $table->timestamp('date_ajout')->useCurrent();
            $table->timestamps();

            // Add indexes for better performance
            $table->index('nom');
            $table->index('etat');
            $table->index('type');
            $table->index('date_ajout');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
