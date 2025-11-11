@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="card shadow">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <i class="fas fa-gavel fa-3x text-primary mb-3"></i>
            <h2 class="card-title mb-1">{{ config('app.name') }}</h2>
            <p class="text-muted">Faça login para continuar</p>
        </div>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-3">
                <label for="usuario" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" 
                           class="form-control @error('usuario') is-invalid @enderror" 
                           id="usuario" 
                           name="usuario" 
                           value="{{ old('usuario') }}" 
                           required 
                           autofocus
                           placeholder="Digite seu email">
                </div>
                @error('usuario')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" 
                           class="form-control @error('senha') is-invalid @enderror" 
                           id="senha" 
                           name="senha" 
                           required
                           placeholder="Digite sua senha">
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
            
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-sign-in-alt me-2"></i> Entrar
                </button>
            </div>
            
            <div class="text-center mt-3">
                <a href="#" class="text-decoration-none">Esqueceu sua senha?</a>
            </div>
        </form>
    </div>
</div>
@endsection


