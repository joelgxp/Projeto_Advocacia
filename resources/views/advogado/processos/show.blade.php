@extends('layouts.app')

@section('title', 'Detalhes do Processo - Advogado')

@section('page-title', 'Detalhes')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Detalhes do Processo</h5>
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