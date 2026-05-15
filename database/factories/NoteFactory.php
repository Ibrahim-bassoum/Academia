<?php

namespace Database\Factories;

use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             //'etudiant_id' => \App\Models\Etudiant::factory(),
        'module_id' => \App\Models\Module::factory(),
        'valeur' => $this->faker->randomFloat(2, 5, 18), // Des notes entre 5 et 18
        'type' => $this->faker->randomElement(['devoir', 'examen', 'rattrapage']),
        'coefficient' => $this->faker->numberBetween(1, 4)
        ];
    }
}
