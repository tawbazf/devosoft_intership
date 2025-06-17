@extends('layouts.app')

@section('content')
<!-- CDN Tailwind si pas déjà dans layouts.app -->
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@endpush

<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Inscription</h2>

        <form id="register-form">
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

            <div class="mb-4 flex items-center">
                <input type="checkbox" id="is_admin" name="is_admin"
                       class="mr-2 rounded border-gray-300 text-blue-600 shadow-sm focus:ring focus:ring-blue-300">
                <label for="is_admin" class="text-gray-700">Administrateur</label>
            </div>

            <button type="submit"
                    class="w-full bg-yellow-100 hover:bg-yellow-300 text-white font-bold py-2 px-4 rounded-lg transition">
                S'inscrire
            </button>
        </form>

        <div id="register-error" class="text-red-500 text-sm mt-4 text-center"></div>
    </div>
</div>

<script>
    document.getElementById('register-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);
        data.is_admin = data.is_admin === 'on';
        try {
            const response = await fetch('/api/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.error || 'Erreur d\'inscription');
            }
            window.location.href = '/login';
        } catch (error) {
            document.getElementById('register-error').textContent = error.message;
        }
    });
</script>
@endsection
