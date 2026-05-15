<?php

namespace App\Http\Controllers\Promoteur;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Note;
use App\Models\Suivi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Effectif Global (Nombre total d'étudiants)
        $totalEtudiants = Etudiant::count();

        // 2. Nombre de filières actives
        $totalFilieres = Filiere::count();

        // 3. Performance Académique : Moyenne de toutes les notes saisies
        // On arrondit à 2 chiffres après la virgule
        $moyenneGenerale = Note::avg('valeur') ?? 0;

        // 4. Assiduité : Taux de présence global
        $totalAppels = Suivi::count();
        $presences = Suivi::where('statut', 'present')->count();
        
        $tauxPresence = $totalAppels > 0 
            ? ($presences / $totalAppels) * 100 
            : 0;

        // 5. Récupérer les 5 dernières absences signalées pour le flux d'activité
        $dernieresAbsences = Suivi::where('statut', 'absent')
            ->with(['etudiant', 'module'])
            ->latest()
            ->take(5)
            ->get();

        return view('promoteur.dashboard', compact(
            'totalEtudiants',
            'totalFilieres',
            'moyenneGenerale',
            'tauxPresence',
            'dernieresAbsences'
        ));
    }
}