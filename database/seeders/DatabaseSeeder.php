<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\AcademiaSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\PaiementSeeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ÉTAPE 0 : Création des rôles supplémentaires
        if (!Role::where('name', 'scolarite')->exists()) {
            Role::create(['name' => 'scolarite']);
        }

        // ÉTAPE 1 : Configuration de base (Rôles existants, Admin, etc.)
        $this->call([
            AcademiaSeeder::class,
        ]);

        // OPTIONNEL : Assigner le rôle scolarité à l'admin pour tes tests
        $admin = User::first();
        if ($admin) {
            $admin->assignRole('scolarite');
        }

        // ÉTAPE 2 : Création de la structure académique
        $filieres = \App\Models\Filiere::factory(3)->create();

        foreach ($filieres as $filiere) {
            $niveaux = \App\Models\Niveau::factory(3)->create(['filiere_id' => $filiere->id]);

            foreach ($niveaux as $niveau) {
                $modules = \App\Models\Module::factory(5)->create([
                    'filiere_id' => $filiere->id,
                    'niveau_id' => $niveau->id
                ]);

                $etudiants = \App\Models\Etudiant::factory(10)->create([
                    'filiere_id' => $filiere->id,
                    'niveau_id' => $niveau->id
                ]);

                // ÉTAPE 3 : Notes et Suivis
                foreach ($etudiants as $etudiant) {
                    foreach ($modules as $module) {
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
                    }
                }
            }
        }

        // ÉTAPE 4 : Finances
        \App\Models\Depense::factory(15)->create();
        
        $this->call([
            PaiementSeeder::class,
        ]);
    }
}