@extends('layouts.guest')

@section('title', 'Login - ' . config('app.name'))

@section('content')
<div class="card shadow">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary">{{ config('app.name') }}</h2>
            <p class="text-muted">Faça login para acessar o sistema</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-3">
                <label for="usuario" class="form-label">E-mail</label>
                <input type="email" 
                       class="form-control @error('usuario') is-invalid @enderror" 
                       id="usuario" 
                       name="usuario" 
                       value="{{ old('usuario') }}" 
                       required 
                       autofocus
                       placeholder="seu@email.com">
                @error('usuario')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" 
                       class="form-control @error('senha') is-invalid @enderror" 
                       id="senha" 
                       name="senha" 
                       required
                       placeholder="Sua senha">
                @error('senha')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">
                    Lembrar-me
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="fas fa-sign-in-alt me-2"></i>Entrar
            </button>
        </form>

        <div class="text-center mt-3">
            <small class="text-muted">
                Sistema de Gerenciamento de Advocacia
            </small>
        </div>
    </div>
</div>
@endsection

