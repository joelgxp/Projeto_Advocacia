@extends('layouts.app')

@section('title', 'Consulta Processual')
@section('page-title', 'Consulta Processual')

@section('content')
<div class="row fade-in">
    <div class="col-12">
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="mb-0 text-gradient">Consulta Processual</h5>
            </div>
            <div class="modern-card-body">
                <form method="POST" action="{{ route('admin.consulta-processual.consultar') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label-modern">Número do Processo *</label>
                        <input type="text" name="numero_processo" class="form-control-modern" required 
                               placeholder="0000000-00.0000.0.00.0000" value="{{ old('numero_processo') }}">
                        <small class="text-muted">Formato CNJ: NNNNNNN-DD.AAAA.J.TR.OOOO</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-modern">Tribunal (Opcional)</label>
                        <select name="tribunal" class="form-control-modern">
                            <option value="">Selecione um tribunal</option>
                            @foreach($tribunaisListaPlana as $sigla => $nome)
                                <option value="{{ $sigla }}">{{ $sigla }} - {{ $nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-modern btn-modern-primary">
                        <i class="fas fa-search me-2"></i>Consultar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

