<?php

namespace Database\Factories;

use App\Models\Suivi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Suivi>
 */
class SuiviFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
                    'etudiant_id' => \App\Models\Etudiant::factory(),
        'module_id' => \App\Models\Module::factory(),
        'date_cours' => $this->faker->dateTimeBetween('-1 month', 'now'),
        'statut' => $this->faker->randomElement(['present', 'absent', 'retard']),
        'est_justifie' => false,
        ];
    }
}
