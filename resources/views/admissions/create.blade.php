@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-md animate-pulse">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 shadow-md">
            <p class="font-bold">Veuillez corriger les erreurs suivantes :</p>
            <ul class="list-disc ml-5 mt-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6">
        <h1 class="text-3xl font-black text-gray-900">Nouvelle Inscription</h1>
        <p class="text-gray-600">Enregistrement d'un nouvel étudiant dans le système UniLink</p>
    </div>

    <form action="{{ route('admissions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200">
                    <h3 class="font-bold text-gray-800 mb-6 border-b pb-2 flex items-center">
                        <i class="fas fa-user-circle mr-2 text-indigo-600"></i> Informations Personnelles
                    </h3>
                    
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
                            <input type="text" name="nom" required 
                                   class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-900 focus:border-indigo-600 focus:bg-white focus:ring-0 transition-all placeholder-gray-400" 
                                   placeholder="Ex: TRAORE">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Prénom <span class="text-red-500">*</span></label>
                            <input type="text" name="prenom" required 
                                   class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-900 focus:border-indigo-600 focus:bg-white focus:ring-0 transition-all placeholder-gray-400" 
                                   placeholder="Ex: Moussa">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Email (Optionnel)</label>
                            <input type="email" name="email" 
                                   class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-900 focus:border-indigo-600 focus:bg-white focus:ring-0 transition-all placeholder-gray-400" 
                                   placeholder="etudiant@exemple.com">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Téléphone <span class="text-red-500">*</span></label>
                            <input type="text" name="telephone" required 
                                   class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-900 focus:border-indigo-600 focus:bg-white focus:ring-0 transition-all placeholder-gray-400" 
                                   placeholder="+223 ...">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Date de Naissance <span class="text-red-500">*</span></label>
                            <input type="date" name="date_naissance" required 
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
                                    class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-900 focus:border-indigo-600 focus:bg-white focus:ring-0 transition-all cursor-pointer">
                                <option value="">Sélectionner une filière</option>
                                @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id }}">{{ $filiere->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Niveau <span class="text-red-500">*</span></label>
                            <select name="niveau_id" required 
                                    class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-900 focus:border-indigo-600 focus:bg-white focus:ring-0 transition-all cursor-pointer">
                                <option value="">Sélectionner un niveau</option>
                                @foreach($niveaux as $niveau)
                                    <option value="{{ $niveau->id }}">{{ $niveau->nom }} ({{ $niveau->filiere->nom ?? '' }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200 text-center">
                    <label class="block text-sm font-bold text-gray-700 mb-4 text-left">Photo de l'étudiant</label>
                    <div class="w-36 h-36 bg-gray-50 rounded-full mx-auto mb-4 flex items-center justify-center border-2 border-dashed border-gray-400 hover:border-indigo-600 transition-colors group cursor-pointer">
                        <i class="fas fa-camera text-4xl text-gray-400 group-hover:text-indigo-600"></i>
                    </div>
                    <input type="file" name="photo" 
                           class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 transition">
                </div>

                <div class="bg-indigo-50 p-6 rounded-2xl border border-indigo-200">
                    <p class="text-sm text-indigo-700 mb-6 italic">
                        <i class="fas fa-magic mr-1"></i> Le matricule sera généré automatiquement à la validation.
                    </p>
                    <button type="submit" class="w-full bg-indigo-600 text-white font-black py-4 rounded-xl shadow-lg hover:bg-indigo-700 hover:scale-[1.03] transition-all transform duration-200">
                        VALIDER L'INSCRIPTION
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection