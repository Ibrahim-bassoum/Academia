<?php

namespace Database\Factories;

use App\Models\Etudiant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Etudiant>
 */
class EtudiantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
                  'matricule' => $this->faker->unique()->bothify('2026-####'),
        'nom' => $this->faker->lastName(),
        'prenom' => $this->faker->firstName(),
        'date_naissance' => $this->faker->date('Y-m-d', '-18 years'),
        'telephone' => $this->faker->phoneNumber(),
        'filiere_id' => \App\Models\Filiere::factory(),
        'niveau_id' => \App\Models\Niveau::factory(),
        ];
    }
}
