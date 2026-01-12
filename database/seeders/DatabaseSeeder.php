<?php

namespace Database\Seeders;

use App\Models\Utilisateur;
use App\Models\Categorie;
use App\Models\Fournisseur;
use App\Models\Produits;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Utilisateurs;::factory(10)->create();

        Utilisateur::factory()->create([
            'nom' => 'Test',
            'prenom' => 'Utilisateur',
            'email' => 'test@example.com',
        ]);

        // Seed categories
        Categorie::factory(5)->create();

        // Seed fournisseurs
        Fournisseur::factory(10)->create();

        // Seed produits
        Produits::factory(20)->create();
    }
}
