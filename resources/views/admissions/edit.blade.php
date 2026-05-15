@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 shadow-md">
            <p class="font-bold text-sm">Veuillez corriger les erreurs suivantes :</p>
            <ul class="list-disc ml-5 mt-1 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-gray-900">Modifier l'Étudiant</h1>
            <p class="text-gray-600">Modification du profil de : <span class="font-bold text-indigo-600">{{ $etudiant->prenom }} {{ $etudiant->nom }}</span></p>
        </div>
        <a href="{{ route('admissions.index') }}" class="text-gray-500 hover:text-gray-800 transition">
            <i class="fas fa-arrow-left mr-1"></i> Retour au registre
        </a>
    </div>

    <form action="{{ route('admissions.update', $etudiant->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200">
                    <h3 class="font-bold text-gray-800 mb-6 border-b pb-2 flex items-center">
                        <i class="fas fa-user-edit mr-2 text-indigo-600"></i> Informations Personnelles
                    </h3>
                    
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
                            <input type="text" name="nom" value="{{ old('nom', $etudiant->nom) }}" required 
                                   class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-900 focus:border-indigo-600 focus:bg-white focus:ring-0 transition-all">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Prénom <span class="text-red-500">*</span></label>
                            <input type="text" name="prenom" value="{{ old('prenom', $etudiant->prenom) }}" required 
                                   class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-900 focus:border-indigo-600 focus:bg-white focus:ring-0 transition-all">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Email (Optionnel)</label>
                            <input type="email" name="email" value="{{ old('email', $etudiant->email) }}"
                                   class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-900 focus:border-indigo-600 focus:bg-white focus:ring-0 transition-all">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Téléphone <span class="text-red-500">*</span></label>
                            <input type="text" name="telephone" value="{{ old('telephone', $etudiant->telephone) }}" required 
                                   class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-900 focus:border-indigo-600 focus:bg-white focus:ring-0 transition-all">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Date de Naissance <span class="text-red-500">*</span></label>
                            <input type="date" name="date_naissance" value="{{ old('date_naissance', $etudiant->date_naissance) }}" required 
                                   class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-900 focus:border-indigo-600 focus:bg-white focus:ring-0 transition-all">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200">
                    <h3 class="font-bold text-gray-800 mb-6 border-b pb-2 flex items-center">
                        <i class="fas fa-graduation-cap mr-2 text-indigo-600"></i> Affectation Académique
                    </h3>
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Filière <span class="text-red-500">*</span></label>
                            <select name="filiere_id" required 
                                    class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-900 focus:border-indigo-600 focus:bg-white transition-all cursor-pointer">
                                @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id }}" {{ $etudiant->filiere_id == $filiere->id ? 'selected' : '' }}>
                                        {{ $filiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Niveau <span class="text-red-500">*</span></label>
                            <select name="niveau_id" required 
                                    class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-900 focus:border-indigo-600 focus:bg-white transition-all cursor-pointer">
                                @foreach($niveaux as $niveau)
                                    <option value="{{ $niveau->id }}" {{ $etudiant->niveau_id == $niveau->id ? 'selected' : '' }}>
                                        {{ $niveau->nom }} ({{ $niveau->filiere->nom ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200 text-center">
                    <label class="block text-sm font-bold text-gray-700 mb-4 text-left">Photo actuelle</label>
                    
                    <div class="relative group w-36 h-36 mx-auto mb-4">
                        @if($etudiant->photo)
                            <img src="{{ asset('storage/' . $etudiant->photo) }}" class="w-full h-full rounded-full object-cover border-4 border-indigo-100 shadow-sm">
                        @else
                            <div class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center border-2 border-dashed border-gray-300">
                                <i class="fas fa-camera text-4xl text-gray-300"></i>
                            </div>
                        @endif
                    </div>
                    
                    <p class="text-xs text-gray-400 mb-4">Modifier la photo :</p>
                    <input type="file" name="photo" 
                           class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 transition">
                </div>

                <div class="bg-amber-50 p-6 rounded-2xl border border-amber-200">
                    <p class="text-sm text-amber-700 mb-6 italic">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Le matricule <span class="font-bold">{{ $etudiant->matricule }}</span> est définitif et ne peut pas être modifié.
                    </p>
                    <button type="submit" class="w-full bg-indigo-600 text-white font-black py-4 rounded-xl shadow-lg hover:bg-indigo-700 hover:scale-[1.03] transition-all transform duration-200 uppercase tracking-wider">
                        Enregistrer les modifications
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection