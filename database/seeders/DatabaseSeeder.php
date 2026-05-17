<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ÉTAPE 0 : Création des rôles de sécurité
        if (!Role::where('name', 'scolarite')->exists()) {
            Role::create(['name' => 'scolarite']);
        }

        // ÉTAPE 1 : Configuration initiale du compte Administrateur
        $this->call([
            AcademiaSeeder::class,
        ]);

        // Assigner le rôle scolarité à l'admin pour tes accès
        $admin = User::first();
        if ($admin) {
            $admin->assignRole('scolarite');
        }

        // ÉTAPE 2 : Structure Académique Centrale (Filières, Niveaux globale, 40 Étudiants par filière)
        $this->call([
            StructureAcademiqueSeeder::class,
        ]);

        // ÉTAPE 3 : Tarification (Une fois que les niveaux existent)
        $this->call([
            TarifSeeder::class,
        ]);

        // ÉTAPE 4 : Données financières et simulations de transactions
        \App\Models\Depense::factory(15)->create();
        
        $this->call([
            PaiementSeeder::class,
        ]);
    }
}