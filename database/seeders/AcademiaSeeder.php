<?php

namespace Database\Seeders; // INDISPENSABLE pour corriger l'erreur de détection

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AcademiaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création des rôles de manière sécurisée
        $admin      = Role::firstOrCreate(['name' => 'admin']);
        $compta     = Role::firstOrCreate(['name' => 'comptable']);
        $scolarite  = Role::firstOrCreate(['name' => 'scolarite']);
        $prof       = Role::firstOrCreate(['name' => 'professeur']);
        $etudiant   = Role::firstOrCreate(['name' => 'etudiant']);
        $promoteur  = Role::firstOrCreate(['name' => 'promoteur']);
        $dirPedago  = Role::firstOrCreate(['name' => 'directeur_pedagogique']);
        $surveillant = Role::firstOrCreate(['name' => 'surveillant']);

        // 2. Gestion des permissions
        $permFinance = Permission::firstOrCreate(['name' => 'voir statistiques financieres']);
        $promoteur->givePermissionTo($permFinance);

        // 3. Création de l'Admin IT
        $userAdmin = User::firstOrCreate(
            ['email' => 'admin@unilink.com'],
            [
                'name' => 'Responsable IT',
                'password' => bcrypt('password'),
                'user_type' => 'staff',
            ]
        );
        $userAdmin->assignRole($admin);

        // 4. Création du Promoteur (Le Patron)
        $userPromoteur = User::firstOrCreate(
            ['email' => 'promoteur@unilink.com'],
            [
                'name' => 'Le Promoteur',
                'password' => bcrypt('password'),
                'user_type' => 'staff',
            ]
        );
        $userPromoteur->assignRole($promoteur);

        // 5. Création du Comptable
        $userPromoteur = User::firstOrCreate(
            ['email' => 'comptable@unilink.com'],
            [
                'name' => 'Le comptable',
                'password' => bcrypt('password'),
                'user_type' => 'staff',
            ]
        );
        $userPromoteur->assignRole($compta);

        // 6. Création d'un Étudiant de test
        $userStudent = User::firstOrCreate(
            ['email' => 'student@unilink.com'],
            [
                'name' => 'Jean Etudiant',
                'password' => bcrypt('password'),
                'user_type' => 'student',
            ]
        );
        $userStudent->assignRole($etudiant);
    }
}