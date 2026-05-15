<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academia - Panel</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-indigo-900 text-white flex flex-col shadow-xl">
            <div class="p-6 text-center border-b border-indigo-800">
                <h2 class="text-2xl font-bold tracking-wider">UniLink</h2>
                <p class="text-xs text-indigo-300">Gestion Academia</p>
            </div>

            <nav class="flex-1 mt-4 px-4 space-y-2 overflow-y-auto">
                <a href="{{ Auth::user()->hasRole('promoteur') ? route('promoteur.dashboard') : '#' }}" 
                   class="flex items-center p-3 rounded-lg {{ request()->routeIs('promoteur.dashboard') ? 'bg-indigo-800 text-white shadow-inner' : 'hover:bg-indigo-800 transition' }}">
                    <i class="fas fa-home mr-3"></i> Dashboard
                </a>

                @role('promoteur')
                <div class="pt-4 pb-2 text-xs font-semibold text-indigo-400 uppercase tracking-widest">Supervision</div>
                <a href="{{ route('promoteur.dashboard') }}" class="flex items-center p-3 rounded-lg hover:bg-indigo-800 transition">
                    <i class="fas fa-chart-line mr-3"></i> Finance & Stats
                </a>
                <a href="#" class="flex items-center p-3 rounded-lg hover:bg-indigo-800 transition">
                    <i class="fas fa-users mr-3"></i> Utilisateurs
                </a>
                @endrole

                <div class="pt-10">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center p-3 rounded-lg text-red-300 hover:bg-red-900 transition border border-transparent hover:border-red-400">
                            <i class="fas fa-sign-out-alt mr-3"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </nav>

            <div class="p-4 border-t border-indigo-800 text-sm text-center text-indigo-300 italic">
                Connecté : {{ Auth::user()->name }}
            </div>
        </aside>

        <main class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm py-4 px-8 border-b border-gray-200">
                <div class="flex justify-end">
                    <span class="text-gray-500 text-sm">Session 2025-2026</span>
                </div>
            </header>

            <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-8">
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>