<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Paiement;
use App\Models\Tarif; // <-- N'oublie pas d'importer le modèle Tarif ici
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    /**
     * Liste tous les étudiants pour la comptabilité (avec recherche)
     */
    public function index(Request $request)
    {
        $query = Etudiant::with(['filiere', 'niveau']);

        // Gestion de la barre de recherche (Nom ou Matricule)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('matricule', 'LIKE', "%{$search}%");
            });
        }

        // On récupère les étudiants paginés par 10
        $etudiants = $query->latest()->paginate(10);

        return view('finance.index', compact('etudiants'));
    }

    /**
     * Fiche financière d'un étudiant (Formulaire + Historique + Suivi des tranches)
     */
public function etudiant($id)
{
    $etudiant = Etudiant::with(['filiere', 'niveau'])->findOrFail($id);
    
    // Historique des paiements de cet étudiant uniquement
    $paiements = Paiement::where('etudiant_id', $id)->latest()->get();

    // Recherche globale du tarif par niveau_id
    $tarif = Tarif::where('niveau_id', $etudiant->niveau_id)->first();

    // Détermination des montants (Fallback à 0 si non configuré)
    $scolarite_totale = $tarif ? $tarif->montant_scolarite : 0;
    $frais_inscription_tarif = $tarif ? $tarif->frais_inscription : 0;

    // Somme des versements pour la scolarité (Scolarité ou Scolarite)
    $total_scolarite_paye = Paiement::where('etudiant_id', $id)
        ->where(function($q) {
            $q->where('type_paiement', 'LIKE', 'Scolarité%')
              ->orWhere('type_paiement', 'LIKE', 'Scolarite%');
        })
        ->sum('montant');

    // Calcul du reste
    $reste_scolarite = $scolarite_totale - $total_scolarite_paye;

    return view('finance.etudiant', compact(
        'etudiant', 
        'paiements', 
        'scolarite_totale', 
        'frais_inscription_tarif', 
        'reste_scolarite'
    ));
}

    /**
     * Encaisser un versement avec validation selon les tarifs
     */
    public function payer(Request $request, $id)
    {
        $etudiant = Etudiant::findOrFail($id);

        // 1. Validation des données du formulaire étudiant.blade
        $request->validate([
            'montant' => 'required|numeric|min:1',
            'type_paiement' => 'required|string',
            'mode_paiement' => 'required|string',
            'recu_numero' => 'nullable|string',
            'date_paiement' => 'required|date',
        ]);

        // 2. On récupère le tarif de sa filière et de son niveau pour les contrôles
      $tarif = Tarif::where('niveau_id', $etudiant->niveau_id)->first();

        // 3. LA CONDITION D'ACTIVATION 💥
        // Si le comptable choisit "Frais d'Inscription"
        if ($request->type_paiement === 'Inscription') {
            
            // On vérifie si le montant payé correspond au minimum requis pour l'inscription (ex: 50 000 FCFA)
            if ($request->montant < $tarif->frais_inscription) {
                return redirect()->back()->withInput()->withErrors([
                    'montant' => "Montant insuffisant. Les frais d'inscription requis sont de : " . number_format($tarif->frais_inscription, 0, ',', ' ') . " FCFA."
                ]);
            }

            // Si le montant est bon, le statut passe à ACTIF
            $etudiant->update(['statut' => 'ACTIF']);
            $messageSuccess = 'Les frais d\'inscription ont été validés. L\'étudiant est désormais ACTIF !';
        } else {
            // C'est un paiement de tranche de scolarité classique
            $messageSuccess = 'Le versement pour la scolarité a bien été enregistré.';
        }

        // 4. Enregistrement du paiement en Base de Données
        Paiement::create([
            'etudiant_id' => $etudiant->id,
            'montant' => $request->montant,
            'type_paiement' => $request->type_paiement,
            'mode_payment' => $request->mode_paiement, // Note: attention si ta colonne s'appelle mode_payment ou mode_paiement
            'recu_numero' => $request->recu_numero,
            'date_paiement' => $request->date_paiement,
        ]);

        return redirect()->route('finance.etudiant', $etudiant->id)
            ->with('success', $messageSuccess . ' Montant encaissé : ' . number_format($request->montant, 0, ',', ' ') . ' FCFA.');
    }
}