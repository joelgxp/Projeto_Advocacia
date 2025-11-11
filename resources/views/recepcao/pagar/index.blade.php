@extends('layouts.app')

@section('title', 'Contas a Pagar - Recepção')

@section('page-title', 'Contas a Pagar')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Contas a Pagar</h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>Nenhum registro encontrado.
        </div>
    </div>
</div>
@endsection