@extends('layouts.app')

@section('title', 'Dashboard - Recepção')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard - Recepção</h1>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Bem-vindo ao Painel de Recepção</h5>
            </div>
            <div class="card-body">
                <p>Use o menu lateral para navegar pelas funcionalidades do sistema.</p>
                <p>Áreas disponíveis:</p>
                <ul>
                    <li>Gestão de Clientes</li>
                    <li>Gestão de Processos</li>
                    <li>Audiências</li>
                    <li>Controle Financeiro</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

