@extends('layouts.admin')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Gestion Financière</h1>
        <p class="text-gray-500 text-sm">Suivi des flux de trésorerie Academia</p>
    </div>
    <div class="flex space-x-3">
        <button class="bg-white border border-gray-300 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
            <i class="fas fa-file-export mr-2"></i> Exporter
        </button>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 shadow-md transition">
            <i class="fas fa-plus mr-2"></i> Nouveau Paiement
        </button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-500">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-400 font-medium text-xs uppercase tracking-wider">Total Recettes</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalRecettes, 0, ',', ' ') }} <small class="text-xs">FCFA</small></p>
            </div>
            <div class="bg-green-100 p-2 rounded-lg text-green-600">
                <i class="fas fa-arrow-up"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-red-500">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-400 font-medium text-xs uppercase tracking-wider">Total Dépenses</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalDepenses, 0, ',', ' ') }} <small class="text-xs">FCFA</small></p>
            </div>
            <div class="bg-red-100 p-2 rounded-lg text-red-600">
                <i class="fas fa-arrow-down"></i>
            </div>
        </div>
    </div>

    <div class="bg-indigo-700 p-6 rounded-2xl shadow-lg border-l-4 border-indigo-900 text-white">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-indigo-200 font-medium text-xs uppercase tracking-wider">Solde Actuel</p>
                <p class="text-2xl font-bold mt-1">{{ number_format($solde, 0, ',', ' ') }} <small class="text-xs">FCFA</small></p>
            </div>
            <div class="bg-indigo-600 p-2 rounded-lg text-white">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-50 flex justify-between items-center">
        <h3 class="font-bold text-gray-700">Derniers encaissements</h3>
        <a href="#" class="text-indigo-600 text-sm font-medium hover:underline">Voir tout</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                <tr>
                    <th class="p-4 font-semibold">Étudiant</th>
                    <th class="p-4 font-semibold">Référence</th>
                    <th class="p-4 font-semibold">Type</th>
                    <th class="p-4 font-semibold">Montant</th>
                    <th class="p-4 font-semibold">Mode</th>
                    <th class="p-4 font-semibold text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($derniersPaiements as $p)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4">
                        <div class="font-medium text-gray-800">{{ $p->etudiant->nom ?? 'Inconnu' }}</div>
                        <div class="text-xs text-gray-400">ID: #{{ $p->etudiant_id }}</div>
                    </td>
                    <td class="p-4 text-sm text-gray-600">{{ $p->recu_numero }}</td>
                    <td class="p-4">
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase bg-gray-100 text-gray-600">
                            {{ $p->type_paiement }}
                        </span>
                    </td>
                    <td class="p-4 font-bold text-green-600">+ {{ number_format($p->montant, 0) }}</td>
                    <td class="p-4 text-sm text-gray-500">{{ $p->mode_paiement }}</td>
                    <td class="p-4 text-center">
                        <button class="text-gray-400 hover:text-indigo-600 transition">
                            <i class="fas fa-print"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection