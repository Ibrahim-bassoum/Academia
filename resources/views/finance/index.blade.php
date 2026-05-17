@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Suivi des Paiements & Comptabilité</h1>
            <p class="text-gray-500 text-sm">Gestion des versements et validation des statuts étudiants</p>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('finance.index') }}" method="GET" class="flex gap-2">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500" 
                       placeholder="Rechercher un étudiant par nom ou matricule...">
            </div>
            <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 transition text-sm font-semibold">
                <i class="fas fa-search"></i> Filtrer
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-400 uppercase text-[10px] font-bold tracking-wider">
                <tr>
                    <th class="px-6 py-4">Étudiant / Matricule</th>
                    <th class="px-6 py-4">Filière & Niveau</th>
                    <th class="px-6 py-4">Statut Académique</th>
                    <th class="px-6 py-4 text-center">Encaisser / Historique</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($etudiants as $etudiant)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-gray-700 uppercase">{{ $etudiant->nom }}</div>
                        <div class="text-sm font-medium text-gray-500">{{ $etudiant->prenom }}</div>
                        <div class="text-[10px] text-green-700 font-mono mt-0.5">{{ $etudiant->matricule }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs font-semibold text-gray-600">{{ $etudiant->filiere->nom ?? 'N/A' }}</div>
                        <div class="text-[11px] text-gray-400 italic">{{ $etudiant->niveau->nom ?? 'N/A' }}</div>
                    </td>
                    
                    <td class="px-6 py-4">
                        @if(strtoupper($etudiant->statut) === 'ACTIF')
                            <span class="px-2 py-1 text-[9px] font-bold bg-green-100 text-green-600 rounded-full uppercase tracking-tighter">
                                ACTIF
                            </span>
                        @else
                            <span class="px-2 py-1 text-[9px] font-bold bg-yellow-100 text-yellow-600 rounded-full uppercase tracking-tighter">
                                EN ATTENTE
                            </span>
                        @endif
                    </td>
                    
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('finance.etudiant', $etudiant->id) }}" 
                           class="inline-flex items-center justify-center p-2.5 bg-green-50 text-green-700 hover:bg-green-600 hover:text-white rounded-lg transition shadow-sm" 
                           title="Prendre un versement / Voir l'historique">
                            <i class="fas fa-money-bill-wave mr-1.5 text-sm"></i> 
                            <span class="text-xs font-bold">Gérer</span>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic text-sm">
                        Aucun étudiant trouvé dans le registre.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $etudiants->links() }}
    </div>
</div>
@endsection