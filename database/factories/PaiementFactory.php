<?php

namespace Database\Factories;

use App\Models\Etudiant;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaiementFactory extends Factory
{
    public function definition(): array
    {
        return [
            // On prend un étudiant au hasard parmi ceux existants
            'etudiant_id' => Etudiant::inRandomOrder()->first()?->id ?? Etudiant::factory(), 
            'montant' => $this->faker->randomElement([25000, 50000, 100000, 150000]),
            'type_paiement' => $this->faker->randomElement(['Inscription', 'Scolarité', 'Examen']),
            'date_paiement' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'mode_paiement' => $this->faker->randomElement(['Espèces', 'Virement', 'Mobile Money']),
            'recu_numero' => 'REC-' . $this->faker->unique()->numberBetween(1000, 9999),
        ];
    }
}