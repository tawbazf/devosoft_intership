@extends('layouts.app')

@section('content')
<!-- Tailwind CDN si non déjà inclus dans layouts.app -->
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@endpush

<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Connexion</h2>

        <form id="login-form">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                <input type="email" id="email" name="email" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-semibold mb-2">Mot de passe</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <button type="submit"
                    class="w-full bg-yellow-100 hover:bg-yellow-300 text-white font-bold py-2 px-4 rounded-lg transition">
                Se connecter
            </button>
        </form>

        <div id="login-error" class="text-red-500 text-sm mt-4 text-center"></div>
    </div>
</div>

<script>
    document.getElementById('login-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);
        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams(data)
            });
            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.error || 'Erreur de connexion');
            }
            const { access_token } = await response.json();
            document.cookie = `jwt_token=${access_token}; path=/; max-age=3600; samesite=strict`;
            localStorage.setItem('jwt_token', access_token);
            window.location.href = '/videos';
        } catch (error) {
            document.getElementById('login-error').textContent = error.message;
        }
    });
</script>
@endsection
