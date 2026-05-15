<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\AcademiaSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ÉTAPE 1 : Créer les Rôles et Permissions d'abord !
        // C'est indispensable pour que les étapes suivantes puissent assigner des rôles.
        $this->call([
            AcademiaSeeder::class,
        ]);

        // ÉTAPE 2 : Créer les Filières
        $filieres = \App\Models\Filiere::factory(3)->create();

        $filieres->each(function ($filiere) {
            // ÉTAPE 3 : Créer les Niveaux
            $niveaux = \App\Models\Niveau::factory(3)->create(['filiere_id' => $filiere->id]);

            $niveaux->each(function ($niveau) use ($filiere) {
                
                // ÉTAPE 4 : Créer les Modules
                $modules = \App\Models\Module::factory(5)->create([
                    'filiere_id' => $filiere->id,
                    'niveau_id' => $niveau->id
                ]);

                // ÉTAPE 5 : Créer les Étudiants
                $etudiants = \App\Models\Etudiant::factory(10)->create([
                    'filiere_id' => $filiere->id,
                    'niveau_id' => $niveau->id
                ]);

                // ÉTAPE 6 : Notes et Suivis
                $etudiants->each(function ($etudiant) use ($modules) {
                    $modules->each(function ($module) use ($etudiant) {
                        
                        \App\Models\Note::factory()->create([
                            'etudiant_id' => $etudiant->id,
                            'module_id'   => $module->id,
                            'type'        => 'devoir'
                        ]);
                        
                        \App\Models\Note::factory()->create([
                            'etudiant_id' => $etudiant->id,
                            'module_id'   => $module->id,
                            'type'        => 'examen'
                        ]);

                        \App\Models\Suivi::factory(2)->create([
                            'etudiant_id' => $etudiant->id,
                            'module_id'   => $module->id
                        ]);
                    });
                });
            });
        });
    }
}