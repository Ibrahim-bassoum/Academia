<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Paiement;
use App\Models\Etudiant;

class PaiementSeeder extends Seeder
{
    public function run(): void
    {
        // 1. On récupère une partie des étudiants pour simuler des inscrits
        $etudiantsPourTest = Etudiant::inRandomOrder()->take(15)->get();

        foreach ($etudiantsPourTest as $etudiant) {
            // CORRECTION : Plus de Tarif::where('niveau_id'). On applique directement un montant par défaut
            $montantInscription = 50000;

            // Étape A : On lui crée d'office son paiement d'inscription
            Paiement::create([
                'etudiant_id' => $etudiant->id,
                'montant' => $montantInscription,
                'type_paiement' => 'Inscription',
                'date_paiement' => now()->subMonths(2),
                'mode_paiement' => collect(['Espèces', 'Orange Money', 'Moov Money'])->random(),
                'recu_numero' => 'REC-' . rand(1000, 5000),
            ]);

            // L'étudiant devient officiellement ACTIF puisqu'il a payé l'inscription
            $etudiant->update(['statut' => 'ACTIF']);

            // Étape B : Optionnel - On lui ajoute un versement de scolarité au hasard
            if (rand(0, 1) === 1) {
                Paiement::create([
                    'etudiant_id' => $etudiant->id,
                    'montant' => collect([50000, 100000, 150000])->random(),
                    'type_paiement' => 'Scolarité',
                    'date_paiement' => now()->subMonth(),
                    'mode_paiement' => collect(['Espèces', 'Orange Money', 'Virement'])->random(),
                    'recu_numero' => 'REC-' . rand(5001, 9999),
                ]);
            }
        }
    }
}