<?php

namespace Database\Factories;

use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Module>
 */
class ModuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
                  'nom' => $this->faker->randomElement([
            'Algorithmique', 'Base de Données', 'Laravel', 'Réseaux IP', 'Java Swing', 'Anglais Technique'
        ]),
        'code' => strtoupper($this->faker->unique()->bothify('??###')), // ex: IF302
        'filiere_id' => \App\Models\Filiere::factory(),
        'niveau_id' => \App\Models\Niveau::factory(),
        ];
    }
}
