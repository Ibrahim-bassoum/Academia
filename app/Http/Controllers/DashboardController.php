<?php

namespace App\Http\Controllers; // Namespace mis à jour

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Note;
use App\Models\Suivi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class DashboardController extends Controller
{
    use HasRoles;

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // --- LOGIQUE POUR LE PROMOTEUR ---
        if ($user->hasRole('promoteur')) {
            $totalEtudiants = Etudiant::count();
            $totalFilieres = Filiere::count();
            $moyenneGenerale = Note::avg('valeur') ?? 0;

            $totalAppels = Suivi::count();
            $presences = Suivi::where('statut', 'present')->count();
            $tauxPresence = $totalAppels > 0 ? ($presences / $totalAppels) * 100 : 0;

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

        // --- LOGIQUE POUR L'AGENT ADMISSIONS (Scolarité) ---
        if ($user->hasRole('scolarite')) {
            $stats = [
                'total_etudiants' => Etudiant::count(),
                'inscrits_aujourdhui' => Etudiant::whereDate('created_at', today())->count(),
                'dernieres_inscriptions' => Etudiant::with(['filiere', 'niveau'])
                    ->latest()
                    ->take(5)
                    ->get(),
            ];

            return view('admissions.dashboard', compact('stats'));
        }


    // 1. Si c'est le comptable, on ne charge aucune vue Dashboard : on le redirige directement !
        if ($user->hasRole('comptable')) {
        return redirect()->to('finance/suivi');
         }

        // Vue par défaut si aucun rôle ne correspond
        return view('dashboard');
    }
}