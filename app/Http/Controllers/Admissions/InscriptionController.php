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
$dernier = \App\Models\Etudiant::where('matricule', 'LIKE', "ADM-{$annee}-%")
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

// 1. Affiche l'interface avec la carte et le bouton de téléchargement
public function viewCard(Etudiant $etudiant)
{
    return view('admissions.show_card', compact('etudiant'));
}

// 2. Génère le PDF pour l'affichage dans l'iframe
public function streamCard(Etudiant $etudiant)
{
    // On charge la vue de la carte
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admissions.card_pdf', compact('etudiant'));
    
    // On configure la taille de la carte
    $pdf->setPaper([0, 0, 242.65, 153], 'portrait');
    
    // On récupère le contenu brut du PDF généré
    $content = $pdf->output();

    // On renvoie une réponse HTTP propre pour l'iframe avec le statut 200
    return response($content, 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="carte.pdf"');
}

// 3. Action de téléchargement forcé
public function downloadCard(Etudiant $etudiant)
{
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admissions.card_pdf', compact('etudiant'));
    return $pdf->setPaper([0, 0, 242.65, 153], 'portrait')->download("Carte_{$etudiant->matricule}.pdf");
}
}