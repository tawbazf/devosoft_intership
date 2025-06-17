<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnPlay - Streaming sans limites</title>
    @vite('resources/css/app.css') <!-- Si tu utilises Laravel + Vite -->
</head>
<body class="bg-gray-900 text-white font-sans">

    <!-- Hero section -->
    <section class="min-h-screen flex flex-col items-center justify-center text-center px-6">
        <h1 class="text-5xl md:text-6xl font-extrabold mb-4 text-yellow-400">Bienvenue sur <span class="text-white">OnPlay</span></h1>
        <p class="text-lg md:text-xl mb-8 max-w-2xl">
            Regardez vos films, séries et contenus préférés partout, à tout moment. Streaming rapide, interface intuitive.
        </p>
        <a href="{{ route('login') }}" class="bg-yellow-400 hover:bg-yellow-300 text-black font-bold py-3 px-6 rounded-full transition">
            Commencer maintenant
        </a>
    </section>

    <!-- Features section -->
    <section class="py-16 bg-gray-800 px-6">
        <div class="max-w-6xl mx-auto text-center">
            <h2 class="text-3xl font-bold mb-12">Pourquoi choisir OnPlay ?</h2>
            <div class="grid md:grid-cols-3 gap-10">
                <div>
                    <h3 class="text-xl font-semibold mb-2">🎬 Catalogue étendu</h3>
                    <p>Films, séries, documentaires, et plus encore, mis à jour régulièrement.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">⚡ Streaming rapide</h3>
                    <p>Lecture fluide en HD et 4K, sans interruption ni publicité.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">📱 Multi-appareil</h3>
                    <p>Disponible sur mobile, tablette, TV, et ordinateur.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-8 bg-gray-950 text-center text-sm">
        <p>&copy; {{ date('Y') }} OnPlay. Tous droits réservés.</p>
        <p class="mt-2"><a href="#" class="underline hover:text-yellow-400">Conditions d'utilisation</a> • <a href="#" class="underline hover:text-yellow-400">Politique de confidentialité</a></p>
    </footer>

</body>
</html>
