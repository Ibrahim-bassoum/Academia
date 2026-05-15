@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Aperçu de la Carte</h1>
        <div class="flex gap-3">
            <a href="{{ route('admissions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Retour au registre
            </a>
            <a href="{{ route('admissions.card.download', $etudiant->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-md transition">
                <i class="fas fa-download mr-2"></i> Télécharger le PDF
            </a>
        </div>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex justify-center">
        <iframe src="{{ route('admissions.card.stream', $etudiant->id) }}" class="w-[500px] h-[320px] border-none shadow-lg"></iframe>
    </div>
</div>
@endsection