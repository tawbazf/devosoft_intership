<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ config('app.name', 'OnPlay') }}</title>

    <!-- ✅ Tailwind CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    @vite(['resources/js/app.js']) <!-- Facultatif si tu utilises Vite -->
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- ✅ Navigation -->
    <nav class="bg-gray-900 text-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0">
                    <a href="{{ route('welcome') }}" class="text-xl font-bold text-yellow-400 hover:text-yellow-300">
                        OnPlay
                    </a>
                </div>

                <div class="hidden md:flex space-x-4">
                    @auth
                        <a href="{{ route('videos.index') }}" class="hover:text-yellow-400">Vidéos</a>
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.users') }}" class="hover:text-yellow-400">Utilisateurs</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-yellow-400">Déconnexion</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-yellow-400">Connexion</a>
                        <a href="{{ route('register') }}" class="hover:text-yellow-400">Inscription</a>
                    @endauth
                </div>

                <!-- Menu mobile -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="focus:outline-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="md:hidden hidden px-4 pb-4">
            @auth
                <a href="{{ route('videos.index') }}" class="block py-2 hover:text-yellow-400">Vidéos</a>
                @if (auth()->user()->is_admin)
                    <a href="{{ route('admin.users') }}" class="block py-2 hover:text-yellow-400">Utilisateurs</a>
                @endif
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="block py-2 hover:text-yellow-400">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block py-2 hover:text-yellow-400">Connexion</a>
                <a href="{{ route('register') }}" class="block py-2 hover:text-yellow-400">Inscription</a>
            @endauth
        </div>
    </nav>

    <!-- ✅ Contenu principal -->
    <div class="max-w-4xl mx-auto p-6">
        @yield('content')
    </div>

    <!-- ✅ Script pour menu mobile -->
    <script>
        const menuBtn = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');
        menuBtn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>
