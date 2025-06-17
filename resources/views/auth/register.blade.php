@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Inscription</div>
            <div class="card-body">
                <form id="register-form">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin">
                        <label for="is_admin" class="form-check-label">Administrateur</label>
                    </div>
                    <button type="submit" class="btn btn-primary">S'inscrire</button>
                </form>
                <div id="register-error" class="text-danger mt-2"></div>
            </div>
        </div>
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