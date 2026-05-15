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
            <h1 class="text-2xl font-bold text-gray-800">Registre des Étudiants</h1>
            <p class="text-gray-500 text-sm">Gestion et suivi des admissions UniLink</p>
        </div>
        <a href="{{ route('admissions.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center shadow-md transition">
            <i class="fas fa-user-plus mr-2"></i> Nouvelle Inscription
        </a>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('admissions.index') }}" method="GET" class="flex gap-2">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" 
                       placeholder="Rechercher par nom ou matricule...">
            </div>
            <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition">
                Rechercher
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-400 uppercase text-xs font-semibold">
                <tr>
                    <th class="px-6 py-4">Photo</th>
                    <th class="px-6 py-4">Étudiant / Matricule</th>
                    <th class="px-6 py-4">Filière & Niveau</th>
                    <th class="px-6 py-4">Contact</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($etudiants as $etudiant)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        @if($etudiant->photo)
                            <img src="{{ asset('storage/' . $etudiant->photo) }}" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                        @else
                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                                {{ substr($etudiant->prenom, 0, 1) }}{{ substr($etudiant->nom, 0, 1) }}
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-gray-800">{{ $etudiant->nom }} {{ $etudiant->prenom }}</div>
                        <div class="text-xs text-indigo-600 font-mono">{{ $etudiant->matricule }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <div class="font-medium text-gray-800">{{ $etudiant->filiere->nom ?? 'N/A' }}</div>
                        <div class="text-xs">{{ $etudiant->niveau->nom ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <div class="flex items-center"><i class="fas fa-phone mr-2 text-xs text-gray-400"></i> {{ $etudiant->telephone }}</div>
                        @if($etudiant->email)
                        <div class="flex items-center text-xs text-gray-400"><i class="fas fa-envelope mr-2"></i> {{ $etudiant->email }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-3">
                            <a href="{{ route('admissions.edit', $etudiant->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition shadow-sm" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('admissions.destroy', $etudiant->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition shadow-sm" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
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