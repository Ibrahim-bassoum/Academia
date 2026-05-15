<?php

namespace App\Http\Controllers\Admissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

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

        // On cherche le matricule le plus grand commencé par ADM-2026
        $dernier = Etudiant::where('matricule', 'LIKE', "ADM-{$annee}-%")
            ->orderByRaw('CAST(SUBSTRING(matricule, -4) AS UNSIGNED) DESC')
            ->first();

        if ($dernier) {
            $dernierNumero = (int) substr($dernier->matricule, -4);
            $prochainNumero = $dernierNumero + 1;
        } else {
            $prochainNumero = 1;
        }

        $matricule = "ADM-{$annee}-" . str_pad($prochainNumero, 4, '0', STR_PAD_LEFT);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('etudiants/photos', 'public');
        }

        // Création de l'étudiant bloqué par défaut pour la comptabilité
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
            'statut' => 'EN ATTENTE' // Modification ici
        ]);

        return redirect()->route('admissions.index')
            ->with('success', "L'étudiant {$etudiant->prenom} {$etudiant->nom} a été inscrit avec succès et envoyé à la comptabilité.");
    }

    /**
     * Affiche le formulaire de modification
     */
    public function edit(Etudiant $etudiant)
    {
        $filieres = Filiere::all();
        $niveaux = Niveau::with('filiere')->get();
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
        if ($etudiant->photo) {
            Storage::disk('public')->delete($etudiant->photo);
        }

        $etudiant->delete();

        return redirect()->back()
            ->with('success', "L'étudiant a été retiré du registre.");
    }

    /**
     * 1. Affiche l'interface HTML globale pour voir la carte
     */
    public function viewCard(Etudiant $etudiant)
    {
        return view('admissions.show_card', compact('etudiant'));
    }

    /**
     * 2. Génère le flux PDF pour l'affichage de l'iframe (Paysage)
     */
    public function streamCard(Etudiant $etudiant)
    {
        $pdf = Pdf::loadView('admissions.card_pdf', compact('etudiant'));
        
        // Correction ici : On utilise 'landscape' pour correspondre au format carte
        return $pdf->setPaper([0, 0, 242.65, 153], 'landscape')
                   ->stream("Carte_{$etudiant->matricule}.pdf", ['Attachment' => false]);
    }

    /**
     * 3. Action de téléchargement forcé
     */
    public function downloadCard(Etudiant $etudiant)
    {
        $pdf = Pdf::loadView('admissions.card_pdf', compact('etudiant'));
        
        return $pdf->setPaper([0, 0, 242.65, 153], 'landscape')
                   ->download("Carte_{$etudiant->matricule}.pdf");
    }
}