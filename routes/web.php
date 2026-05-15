<?php

use App\Http\Controllers\Admissions\InscriptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\Promoteur\FinanceController;
use App\Models\Etudiant;
use Illuminate\Support\Facades\Route;

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
    Route::get('/finance', [FinanceController::class, 'index'])->name('promoteur.finance');
});

// --- SECTION ACADEMIA : ADMISSIONS (Scolarité) ---
Route::middleware(['auth', 'role:scolarite|promoteur'])->prefix('admissions')->group(function () {
    // Inscription (Création)
    Route::get('/inscription', [InscriptionController::class, 'create'])->name('admissions.create');
    Route::post('/inscription', [InscriptionController::class, 'store'])->name('admissions.store');
    
    // Registre (Liste)
    Route::get('/liste', [InscriptionController::class, 'index'])->name('admissions.index');

    // --- AJOUTS : MODIFICATION ET SUPPRESSION ---
    // Affiche le formulaire de modification
    Route::get('/etudiant/{etudiant}/edit', [InscriptionController::class, 'edit'])->name('admissions.edit');
    // Enregistre les modifications (PUT ou PATCH)
    Route::put('/etudiant/{etudiant}', [InscriptionController::class, 'update'])->name('admissions.update');
    // Supprime l'étudiant
    Route::delete('/etudiant/{etudiant}', [InscriptionController::class, 'destroy'])->name('admissions.destroy');
    
    // --- GESTION DE LA CARTE ÉTUDIANT (SÉPARÉE) ---
    // 1. Page HTML globale pour voir la carte et avoir le bouton de téléchargement
    Route::get('/etudiant/{etudiant}/view-card', [InscriptionController::class, 'viewCard'])->name('admissions.card.view');
    
    // 2. Flux brut du PDF (utilisé à l'intérieur de l'iframe de la page d'aperçu)
    Route::get('/etudiant/{etudiant}/stream-card', [InscriptionController::class, 'streamCard'])->name('admissions.card.stream');
    
    // 3. Action de téléchargement forcé (appelé par le bouton de téléchargement)
    Route::get('/etudiant/{etudiant}/download-card', [InscriptionController::class, 'downloadCard'])->name('admissions.card.download');
});

// --- SECTION PROFILS (Breeze) ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';