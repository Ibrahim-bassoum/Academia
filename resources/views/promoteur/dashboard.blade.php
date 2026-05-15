@extends('layouts.admin')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Tableau de Bord Stratégique</h1>
        <p class="text-gray-600">Aperçu global de l'établissement</p>
    </div>
    <div class="bg-indigo-100 text-indigo-700 px-4 py-2 rounded-lg font-semibold shadow-sm">
        Session : {{ now()->format('Y') }}
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase">Effectif</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalEtudiants }}</p>
            </div>
            <i class="fas fa-user-graduate text-blue-200 text-3xl"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase">Filières</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalFilieres }}</p>
            </div>
            <i class="fas fa-graduation-cap text-green-200 text-3xl"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase">Moyenne Gén.</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($moyenneGenerale, 2) }}</p>
            </div>
            <i class="fas fa-chart-bar text-yellow-200 text-3xl"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-red-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase">Assiduité</p>
                <p class="text-2xl font-bold text-gray-800">{{ round($tauxPresence) }}%</p>
            </div>
            <i class="fas fa-clock text-red-200 text-3xl"></i>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-bold text-gray-700">Dernières Absences Signalées</h2>
        <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded-full">Urgent</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm">
                    <th class="p-4 font-semibold">Étudiant</th>
                    <th class="p-4 font-semibold">Module</th>
                    <th class="p-4 font-semibold">Date / Heure</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($dernieresAbsences as $absence)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 text-gray-700 font-medium">{{ $absence->etudiant->nom ?? 'Inconnu' }}</td>
                    <td class="p-4 text-gray-600">{{ $absence->module->nom ?? 'N/A' }}</td>
                    <td class="p-4 text-gray-500 text-sm">{{ $absence->created_at->format('d/m/Y à H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="p-8 text-center text-gray-400">Aucune absence signalée.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection