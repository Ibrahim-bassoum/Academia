@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">Tableau de Bord - Admissions</h1>
    <p class="text-gray-500 text-sm">Bienvenue dans l'espace de gestion scolaire UniLink.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div>
                <div class="text-xs text-gray-400 uppercase font-bold tracking-wider">Total Étudiants</div>
                <div class="text-2xl font-black text-gray-800">{{ $stats['total_etudiants'] }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-user-plus text-xl"></i>
            </div>
            <div>
                <div class="text-xs text-gray-400 uppercase font-bold tracking-wider">Inscrits ce jour</div>
                <div class="text-2xl font-black text-gray-800">{{ $stats['inscrits_aujourdhui'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="mt-10 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
    <h3 class="text-lg font-bold text-gray-800 mb-4">Actions Rapides</h3>
    <div class="flex gap-4">
        <a href="{{ route('admissions.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-indigo-700 transition flex items-center">
            <i class="fas fa-plus-circle mr-2"></i> Nouvelle Inscription
        </a>
        <a href="{{ route('admissions.index') }}" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-200 transition flex items-center">
            <i class="fas fa-list mr-2"></i> Voir le Registre
        </a>
    </div>
</div>
@endsection