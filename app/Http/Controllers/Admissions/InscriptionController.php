<?php

namespace App\Http\Controllers\Admissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Support\Facades\Storage;

class InscriptionController extends Controller
{
    public function create()
    {
        $filieres = Filiere::all();
        $niveaux = Niveau::with('filiere')->get();
        return view('admissions.create', compact('filieres', 'niveaux'));
    }

    public function index(Request $request)
    {
        $query = Etudiant::with(['filiere', 'niveau'])->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('matricule', 'LIKE', "%{$search}%");
        }

        $etudiants = $query->paginate(15);
        return view('admissions.index', compact('etudiants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'nullable|email|unique:etudiants,email',
            'telephone' => 'required|string|max:20',
            'date_naissance' => 'required|date',
            'filiere_id' => 'required|exists:filieres,id',
            'niveau_id' => 'required|exists:niveaux,id',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $annee = date('Y');
        $dernierEtudiant = Etudiant::whereYear('created_at', $annee)->count();
        $numero = str_pad($dernierEtudiant + 1, 4, '0', STR_PAD_LEFT);
        $matricule = "ADM-{$annee}-{$numero}";

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('etudiants/photos', 'public');
        }

        $etudiant = Etudiant::create([
            'matricule' => $matricule,
            'nom' => strtoupper($request->nom),
            'prenom' => ucfirst(strtolower($request->prenom)),
            'email' => $request->email,
            'telephone' => $request->telephone,
            'date_naissance' => $request->date_naissance,
            'filiere_id' => $request->filiere_id,
            'niveau_id' => $request->niveau_id,
            'photo' => $photoPath,
            'statut' => 'actif'
        ]);

        return redirect()->route('admissions.index')
            ->with('success', "L'étudiant {$etudiant->prenom} {$etudiant->nom} a été inscrit avec succès.");
    }

    // --- NOUVELLES MÉTHODES AJOUTÉES ---

    /**
     * Affiche le formulaire de modification
     */
    public function edit(Etudiant $etudiant)
    {
        $filieres = Filiere::all();
        $niveaux = Niveau::with('filiere')->get();
        
        // On retourne une vue spécifique pour l'édition (ex: admissions.edit)
        return view('admissions.edit', compact('etudiant', 'filieres', 'niveaux'));
    }

    /**
     * Met à jour les informations d'un étudiant
     */
    public function update(Request $request, Etudiant $etudiant)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'nullable|email|unique:etudiants,email,' . $etudiant->id,
            'telephone' => 'required|string|max:20',
            'date_naissance' => 'required|date',
            'filiere_id' => 'required|exists:filieres,id',
            'niveau_id' => 'required|exists:niveaux,id',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo du stockage si elle existe
            if ($etudiant->photo) {
                Storage::disk('public')->delete($etudiant->photo);
            }
            $validated['photo'] = $request->file('photo')->store('etudiants/photos', 'public');
        }

        $validated['nom'] = strtoupper($request->nom);
        $validated['prenom'] = ucfirst(strtolower($request->prenom));

        $etudiant->update($validated);

        return redirect()->route('admissions.index')
            ->with('success', "Le profil de {$etudiant->prenom} a été mis à jour.");
    }

    /**
     * Supprime définitivement un étudiant
     */
    public function destroy(Etudiant $etudiant)
    {
        // Supprimer la photo physiquement avant de supprimer la ligne en BDD
        if ($etudiant->photo) {
            Storage::disk('public')->delete($etudiant->photo);
        }

        $etudiant->delete();

        return redirect()->back()
            ->with('success', "L'étudiant a été retiré du registre.");
    }
}