<?php

namespace Database\Factories;

use App\Models\Filiere;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Filiere>
 */
class FiliereFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
  public function definition(): array
{
    $nom = $this->faker->randomElement([
        'Génie Informatique et Télécommunications', 
        'Management des Entreprises', 
        'Réseaux et Sécurité',
        'Comptabilité'
    ]);

    // On génère un code court basé sur le nom (ex: GIT, MAN, RES, COM)
    $code = strtoupper(substr($nom, 0, 3)); 

    return [
        'nom' => $nom,
        'code' => $code, 
    ];
}
}
