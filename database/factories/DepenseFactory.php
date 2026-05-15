<?php

namespace Database\Factories;

use App\Models\Depense;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Depense>
 */
class DepenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
          return [
        'libelle' => $this->faker->randomElement(['Salaire Professeur', 'Facture EDM', 'Achat Fournitures', 'Entretien Clim']),
        'montant' => $this->faker->numberBetween(5000, 500000), // Montants en FCFA
        'categorie' => $this->faker->randomElement(['Salaire', 'Fonctionnement', 'Infrastructure']),
        'date_depense' => $this->faker->dateTimeBetween('-1 month', 'now'),
    ];
    }
}
