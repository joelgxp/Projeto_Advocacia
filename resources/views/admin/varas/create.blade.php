@extends('layouts.app')

@section('title', 'Nova Vara - Administrador')

@section('page-title', 'Nova Vara')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Cadastrar Nova Vara</h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <p class="text-muted">Funcionalidade em desenvolvimento.</p>
    </div>
</div>
@endsection