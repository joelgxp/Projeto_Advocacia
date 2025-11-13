@extends('layouts.guest')

@section('title', 'Login - ' . config('app.name'))

@section('content')
<div class="card shadow-lg border-0" style="border-radius: 1rem; overflow: hidden;">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <div class="mb-3">
                <i class="fas fa-gavel text-primary" style="font-size: 3rem;"></i>
            </div>
            <h2 class="fw-bold text-gradient mb-2">{{ config('app.name') }}</h2>
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
            
            <div class="mb-4">
                <label for="usuario" class="form-label-modern">E-mail</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="fas fa-envelope text-muted"></i>
                    </span>
                    <input type="email" 
                           class="form-control form-control-modern border-start-0 @error('usuario') is-invalid @enderror" 
                           id="usuario" 
                           name="usuario" 
                           value="{{ old('usuario') }}" 
                           required 
                           autofocus
                           placeholder="seu@email.com">
                </div>
                @error('usuario')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="senha" class="form-label-modern">Senha</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="fas fa-lock text-muted"></i>
                    </span>
                    <input type="password" 
                           class="form-control form-control-modern border-start-0 @error('senha') is-invalid @enderror" 
                           id="senha" 
                           name="senha" 
                           required
                           placeholder="Sua senha">
                </div>
                @error('senha')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">
                    Lembrar-me
                </label>
            </div>

            <button type="submit" class="btn btn-modern btn-modern-primary w-100 mb-3 py-2">
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

