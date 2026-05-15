<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Promoteur\DashboardController; // Importation du nouveau contrôleur
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Database\Seeders\AcademiaSeeder;

Route::get('/', function () {
    return view('welcome');
});

// Redirection intelligente du dashboard par défaut
Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user=Auth::user();
    if ($user && $user->hasRole('promoteur')) {
        return redirect()->route('promoteur.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- SECTION ACADEMIA : PROMOTEUR ---
Route::middleware(['auth', 'role:promoteur'])->prefix('promoteur')->group(function () {
    // Route vers le dashboard stratégique
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('promoteur.dashboard');
});

// --- SECTION PROFILS (Breeze) ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';