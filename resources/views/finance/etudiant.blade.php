@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto">
    
    <div class="mb-6">
        <a href="{{ route('finance.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Retour au suivi de comptabilité
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm rounded-r-lg">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Affichage des erreurs de validation (ex: montant d'inscription insuffisant) --}}
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 shadow-sm rounded-r-lg">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="text-[10px] uppercase font-mono bg-green-50 text-green-700 px-2 py-1 rounded-md font-bold">
                {{ $etudiant->matricule }}
            </span>
            <h1 class="text-2xl font-bold text-gray-800 uppercase mt-1">{{ $etudiant->nom }} {{ $etudiant->prenom }}</h1>
            <p class="text-gray-500 text-sm mt-0.5">
                <i class="fas fa-graduation-cap mr-1 text-gray-400"></i> {{ $etudiant->filiere->nom ?? 'N/A' }} — {{ $etudiant->niveau->nom ?? 'N/A' }}
            </p>
        </div>

        <div>
            <span class="text-xs font-semibold block text-gray-400 mb-1 md:text-right">Statut Actuel :</span>
            @if(strtoupper($etudiant->statut) === 'ACTIF')
                <span class="px-3 py-1.5 text-xs font-bold bg-green-100 text-green-700 rounded-full uppercase tracking-wider">
                    ACTIF (Inscrit)
                </span>
            @else
                <span class="px-3 py-1.5 text-xs font-bold bg-yellow-100 text-yellow-700 rounded-full uppercase tracking-wider">
                    EN ATTENTE (Non payé)
                </span>
            @endif
        </div>
    </div>

    {{-- CARTES RÉCAPITULATIVES DES TARIFS ET RESTE À PAYER --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-gray-400 font-bold uppercase block">Frais Inscription</span>
                <span class="text-md font-extrabold text-gray-700">{{ number_format($frais_inscription_tarif, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-id-card"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-gray-400 font-bold uppercase block">Scolarité Annuelle</span>
                <span class="text-md font-extrabold text-gray-700">{{ number_format($scolarite_totale, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-gray-400 font-bold uppercase block">Reste Scolarité</span>
                <span class="text-md font-extrabold {{ $reste_scolarite > 0 ? 'text-red-600' : 'text-green-600' }}">
                    {{ number_format($reste_scolarite, 0, ',', ' ') }} FCFA
                </span>
            </div>
            <div class="w-10 h-10 {{ $reste_scolarite > 0 ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }} rounded-lg flex items-center justify-center">
                <i class="fas fa-calculator"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-fit">
            <h3 class="text-md font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-plus-circle text-green-600 mr-2"></i> Enregistrer un Versement
            </h3>

            <form action="{{ route('finance.payer', $etudiant->id) }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Montant (FCFA) <span class="text-red-500">*</span></label>
                    <input type="number" name="montant" required min="1" placeholder="Ex: 50000" value="{{ old('montant') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Type de Frais <span class="text-red-500">*</span></label>
                    <select name="type_paiement" required class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm">
                        <option value="Inscription" {{ old('type_paiement') == 'Inscription' ? 'selected' : '' }}>Frais d'Inscription</option>
                        <option value="Scolarite_Tranche_1" {{ old('type_paiement') == 'Scolarite_Tranche_1' ? 'selected' : '' }}>Scolarité - Tranche 1</option>
                        <option value="Scolarite_Tranche_2" {{ old('type_paiement') == 'Scolarite_Tranche_2' ? 'selected' : '' }}>Scolarité - Tranche 2</option>
                        <option value="Scolarite_Tranche_3" {{ old('type_paiement') == 'Scolarite_Tranche_3' ? 'selected' : '' }}>Scolarité - Tranche 3</option>
                        <option value="Examen" {{ old('type_paiement') == 'Examen' ? 'selected' : '' }}>Frais d'Examen</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Mode de Paiement <span class="text-red-500">*</span></label>
                    <select name="mode_paiement" required class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm">
                        <option value="Especes">Espèces / Cash</option>
                        <option value="Orange_Money">Orange Money</option>
                        <option value="Moov_Money">Moov Money</option>
                        <option value="Cheque">Chèque Bancaire</option>
                        <option value="Versement_Bancaire">Versement/Virement</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">N° de Reçu (Papier) / Réf OM</label>
                    <input type="text" name="recu_numero" placeholder="Ex: OM12345 or RE-098" value="{{ old('recu_numero') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Date du Versement <span class="text-red-500">*</span></label>
                    <input type="date" name="date_paiement" value="{{ old('date_paiement', date('Y-m-d')) }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm">
                </div>

                <button type="submit" class="w-full bg-green-700 text-white py-2.5 rounded-lg font-bold hover:bg-green-800 transition shadow-sm flex items-center justify-center text-sm">
                    <i class="fas fa-check mr-2"></i> Valider l'encaissement
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-md font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-history text-gray-400 mr-2"></i> Historique des paiements reçus
            </h3>

            <div class="overflow-hidden border border-gray-100 rounded-xl">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-400 uppercase text-[10px] font-bold tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Reçu / Réf</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Mode</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3 text-right">Montant</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($paiements as $paiement)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-mono text-xs text-gray-600">
                                {{ $paiement->recu_numero ?? '-' }}
                            </td>
                            <td class="px-4 py-3 font-semibold text-gray-700">
                                {{ str_replace('_', ' ', $paiement->type_paiement) }}
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">
                                {{ str_replace('_', ' ', $paiement->mode_paiement) }}
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">
                                {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-green-700">
                                {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400 italic text-xs">
                                Aucun versement enregistré pour le moment.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($paiements->count() > 0)
                <div class="mt-4 flex justify-end items-center gap-2 p-3 bg-gray-50 rounded-xl">
                    <span class="text-xs font-bold text-gray-500 uppercase">Total encaissé :</span>
                    <span class="text-lg font-black text-green-700">
                        {{ number_format($paiements->sum('montant'), 0, ',', ' ') }} FCFA
                    </span>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection