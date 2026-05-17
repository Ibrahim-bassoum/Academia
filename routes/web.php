<?php

use App\Http\Controllers\Admissions\InscriptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController; 
use App\Models\Etudiant;
use Illuminate\Support\Facades\Route;

// Importation des deux contrôleurs de finance avec des alias distincts
use App\Http\Controllers\Promoteur\FinanceController as PromoteurFinanceController;
use App\Http\Controllers\Finance\FinanceController as ComptableFinanceController;

Route::get('/', function () {
    return view('welcome');
});

// LE DASHBOARD CENTRAL
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// --- SECTION ACADEMIA : PROMOTEUR ---
Route::middleware(['auth', 'role:promoteur'])->prefix('promoteur')->group(function () {
    Route::get('/dashboard-stats', [DashboardController::class, 'index'])->name('promoteur.dashboard');
    // Utilise le contrôleur du dossier Promoteur
    Route::get('/finance', [PromoteurFinanceController::class, 'index'])->name('promoteur.finance');
});

// --- SECTION ACADEMIA : ADMISSIONS (Scolarité) ---
Route::middleware(['auth', 'role:scolarite|promoteur'])->prefix('admissions')->group(function () {
    // Inscription (Création)
    Route::get('/inscription', [InscriptionController::class, 'create'])->name('admissions.create');
    Route::post('/inscription', [InscriptionController::class, 'store'])->name('admissions.store');
    
    // Registre (Liste)
    Route::get('/liste', [InscriptionController::class, 'index'])->name('admissions.index');

    // --- AJOUTS : MODIFICATION ET SUPPRESSION ---
    Route::get('/etudiant/{etudiant}/edit', [InscriptionController::class, 'edit'])->name('admissions.edit');
    Route::put('/etudiant/{etudiant}', [InscriptionController::class, 'update'])->name('admissions.update');
    Route::delete('/etudiant/{etudiant}', [InscriptionController::class, 'destroy'])->name('admissions.destroy');
    
    // --- GESTION DE LA CARTE ÉTUDIANT ---
    Route::get('/etudiant/{etudiant}/view-card', [InscriptionController::class, 'viewCard'])->name('admissions.card.view');
    Route::get('/etudiant/{etudiant}/stream-card', [InscriptionController::class, 'streamCard'])->name('admissions.card.stream');
    Route::get('/etudiant/{etudiant}/download-card', [InscriptionController::class, 'downloadCard'])->name('admissions.card.download');
});

// --- SECTION ACADEMIA : COMPTABILITÉ (Nouveau bloc) ---
Route::middleware(['auth', 'role:comptable|promoteur'])->prefix('finance')->group(function () {
    // Tableau de bord comptable (Liste des étudiants) -> Utilise le contrôleur du dossier Finance
    Route::get('/suivi', [ComptableFinanceController::class, 'index'])->name('finance.index');
    
    // Fiche financière individuelle (Formulaire + Historique)
    Route::get('/etudiant/{id}', [ComptableFinanceController::class, 'etudiant'])->name('finance.etudiant');
    
    // Traitement de l'encaissement (Validation du paiement)
    Route::post('/etudiant/{id}/payer', [ComptableFinanceController::class, 'payer'])->name('finance.payer');
});

// --- SECTION PROFILS (Breeze) ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';