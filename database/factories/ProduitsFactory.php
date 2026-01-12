<?php

namespace Database\Factories;

use App\Models\Produits;
use App\Models\Categorie;
use App\Models\Fournisseur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produits>
 */
class ProduitsFactory extends Factory
{
    protected $model = Produits::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'prix_achat' => $this->faker->randomFloat(2, 10, 1000),
            'prix_vente' => $this->faker->randomFloat(2, 20, 2000),
            'prix_gros' => $this->faker->randomFloat(2, 15, 1500),
            'quantite' => $this->faker->numberBetween(0, 100),
            'image' => $this->faker->imageUrl(),
            'etat' => $this->faker->randomElement(['disponible', 'indisponible']),
            'type' => $this->faker->randomElement(['achat', 'vente']),
            'categorie_id' => Categorie::factory(),
            'fournisseur_id' => Fournisseur::factory(),
            'date_achat' => $this->faker->dateTime(),
        ];
    }
}