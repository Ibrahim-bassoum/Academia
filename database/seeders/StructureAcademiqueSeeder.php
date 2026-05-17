<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Etudiant;
use Illuminate\Support\Facades\Schema;

class StructureAcademiqueSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Désactiver temporairement les clés étrangères pour vider les tables sans erreur MySQL
        Schema::disableForeignKeyConstraints();
        Etudiant::truncate();
        Niveau::truncate();
        Filiere::truncate();
        Schema::enableForeignKeyConstraints();

        // 2. Création des Niveaux Globaux (Conforme à ta migration épurée sans filiere_id)
        $niveauxData = [
            ['nom' => 'Licence 1'],
            ['nom' => 'Licence 2'],
            ['nom' => 'Licence 3'],
        ];

        $niveaux = [];
        foreach ($niveauxData as $data) {
            $niveaux[] = Niveau::create($data);
        }

        // 3. Création de 10 Filières avec le champ 'code' obligatoire
        $filieresData = [
            ['nom' => 'Génie Informatique et Télécommunications', 'code' => 'GIT'],
            ['nom' => 'Gestion des Entreprises et Administrations', 'code' => 'GEA'],
            ['nom' => 'Comptabilité, Contrôle et Audit', 'code' => 'CCA'],
            ['nom' => 'Management des Organisations', 'code' => 'MDO'],
            ['nom' => 'Marketing et Commerce International', 'code' => 'MCI'],
            ['nom' => 'Banque et Finance de Marché', 'code' => 'BFM'],
            ['nom' => 'Génie Logistique et Transport', 'code' => 'GLT'],
            ['nom' => 'Réseaux et Systèmes Informatiques', 'code' => 'RSI'],
            ['nom' => 'Génie Logiciel et Développement d\'Applications', 'code' => 'GLDA'],
            ['nom' => 'Communication Digitale et Multimédia', 'code' => 'CDM'],
        ];

        $filieres = [];
        foreach ($filieresData as $data) {
            $filieres[] = Filiere::create($data);
        }

        // 4. Génération de 50 étudiants de test liés aléatoirement aux 10 filières et aux 3 niveaux
        for ($i = 0; $i < 50; $i++) {
            $filiereAleatoire = collect($filieres)->random();
            $niveauAleatoire = collect($niveaux)->random();

            Etudiant::create([
                'matricule' => 'MAT-' . rand(100000, 999999),
                'nom' => fake()->lastName(),
                'prenom' => fake()->firstName(),
                'email' => fake()->unique()->safeEmail(),
                'telephone' => fake()->phoneNumber(),
                'filiere_id' => $filiereAleatoire->id,
                'niveau_id' => $niveauAleatoire->id,
                'statut' => 'EN ATTENTE', // Devient ACTIF après génération du paiement
            ]);
        }
    }
}