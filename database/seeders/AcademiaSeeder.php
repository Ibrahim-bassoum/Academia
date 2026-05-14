
<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AcademiaSeeder extends Seeder
{
    public function run(): void
    {
        // On crée les rôles
        $admin = Role::create(['name' => 'admin']);
        $compta = Role::create(['name' => 'comptable']);
        $scolarite = Role::create(['name' => 'scolarite']);
        $prof = Role::create(['name' => 'professeur']);
        $etudiant = Role::create(['name' => 'etudiant']);

        // Création d'un Admin (Fonctionnement Interne)
        $userAdmin = User::create([
            'name' => 'Responsable IT',
            'email' => 'admin@unilink.com',
            'password' => bcrypt('password'),
            'user_type' => 'staff',
        ]);
        $userAdmin->assignRole($admin);

        // Création d'un Étudiant (Environnement Extérieur)
        $userStudent = User::create([
            'name' => 'Jean Etudiant',
            'email' => 'student@unilink.com',
            'password' => bcrypt('password'),
            'user_type' => 'student',
        ]);
        $userStudent->assignRole($etudiant);
    }
}