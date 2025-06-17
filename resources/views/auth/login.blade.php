@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Connexion</div>
            <div class="card-body">
                <form id="login-form">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </form>
                <div id="login-error" class="text-danger mt-2"></div>
            </div>
        </div>
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