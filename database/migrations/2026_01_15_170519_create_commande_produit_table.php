<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandeProduitTable extends Migration
{
    public function up()
    {
        Schema::create('commande_produit', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('commande_id')->constrained('commande')->onDelete('cascade');
            $table->foreignId('produits_id')->constrained('produits')->onDelete('cascade');

            $table->integer('quantite')->default(1);
            $table->decimal('prix_achat', 10, 2);
            $table->decimal('prix_vente', 10, 2);

            $table->timestamps();

            $table->unique(['commande_id', 'produits_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('commande_produit');
    }
}