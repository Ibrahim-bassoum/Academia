<?php

namespace App\Http\Controllers\Promoteur;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\Depense;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
        // On calcule les totaux
        $totalRecettes = Paiement::sum('montant');
        $totalDepenses = Depense::sum('montant');
        $solde = $totalRecettes - $totalDepenses;

        // On récupère les 10 derniers paiements avec les infos de l'étudiant
        $derniersPaiements = Paiement::with('etudiant')->latest()->take(10)->get();

        return view('promoteur.finance.index', compact(
            'totalRecettes', 
            'totalDepenses', 
            'solde', 
            'derniersPaiements'
        ));
    }
}