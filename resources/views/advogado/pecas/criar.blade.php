@extends('layouts.app')

@section('title', 'Gerar Peça com IA')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-robot"></i> Gerar Peça Processual com IA</h2>
            <p class="text-muted">Utilize inteligência artificial para gerar petições seguindo as regras do CPC</p>
        </div>
    </div>

    <div class="row">
        <!-- Formulário -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Configuração da Peça</h5>
                </div>
                <div class="card-body">
                    <form id="formGerarPeca">
                        <!-- Seleção de Processo -->
                        <div class="form-group">
                            <label for="processo_id">Processo *</label>
                            <select class="form-control" id="processo_id" name="processo_id" required>
                                <option value="">Selecione um processo</option>
                                @foreach($processos as $proc)
                                    <option value="{{ $proc->id }}" 
                                            {{ isset($processo) && $processo->id == $proc->id ? 'selected' : '' }}
                                            data-cliente="{{ $proc->cliente->nome ?? '' }}"
                                            data-especialidade="{{ $proc->especialidade->nome ?? '' }}">
                                        {{ $proc->numero_processo }} - {{ $proc->cliente->nome ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tipo de Peça -->
                        <div class="form-group">
                            <label for="tipo_peca">Tipo de Peça *</label>
                            <select class="form-control" id="tipo_peca" name="tipo_peca" required>
                                <option value="">Selecione o tipo</option>
                                <option value="peticao_inicial">Petição Inicial</option>
                                <option value="contestacao">Contestação</option>
                                <option value="replica">Réplica</option>
                                <option value="agravo">Agravo de Instrumento</option>
                                <option value="apelacao">Apelação</option>
                                <option value="embargos_declaracao">Embargos de Declaração</option>
                                <option value="recurso_especial">Recurso Especial</option>
                                <option value="manifestacao">Manifestação</option>
                            </select>
                        </div>

                        <!-- Usar Template -->
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="usar_template" name="usar_template">
                                <label class="custom-control-label" for="usar_template">
                                    Usar template existente
                                </label>
                            </div>
                        </div>

                        <!-- Seleção de Template (oculto inicialmente) -->
                        <div class="form-group" id="div_template" style="display: none;">
                            <label for="template_id">Template</label>
                            <select class="form-control" id="template_id" name="template_id">
                                <option value="">Selecione um template</option>
                                @foreach($templates as $categoria => $temps)
                                    <optgroup label="{{ $categoria ?? 'Sem categoria' }}">
                                        @foreach($temps as $template)
                                            <option value="{{ $template->id }}">
                                                {{ $template->nome }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                <a href="#" id="btnPreviewTemplate">Visualizar template</a>
                            </small>
                        </div>

                        <!-- Contexto -->
                        <div class="form-group">
                            <label for="contexto">Contexto do Caso</label>
                            <textarea class="form-control" id="contexto" name="contexto" rows="4" 
                                      placeholder="Descreva o contexto do caso, fatos relevantes, histórico...">{{ $processo->observacoes ?? '' }}</textarea>
                            <small class="form-text text-muted">
                                Quanto mais detalhes, melhor será a peça gerada
                            </small>
                        </div>

                        <!-- Campos específicos para Petição Inicial -->
                        <div id="campos_peticao_inicial" style="display: none;">
                            <div class="form-group">
                                <label for="pedidos">Pedidos</label>
                                <textarea class="form-control" id="pedidos" name="pedidos" rows="3"
                                          placeholder="Liste os pedidos que deseja fazer ao juízo..."></textarea>
                            </div>

                            <div class="form-group">
                                <label for="valor_causa">Valor da Causa</label>
                                <input type="text" class="form-control" id="valor_causa" name="valor_causa"
                                       placeholder="R$ 0,00">
                            </div>
                        </div>

                        <!-- Campos para outras peças -->
                        <div id="campos_outras_pecas" style="display: none;">
                            <div class="form-group">
                                <label for="argumentos">Argumentos</label>
                                <textarea class="form-control" id="argumentos" name="argumentos" rows="3"
                                          placeholder="Principais argumentos a serem utilizados..."></textarea>
                            </div>

                            <div class="form-group">
                                <label for="fundamentos">Fundamentos Jurídicos</label>
                                <textarea class="form-control" id="fundamentos" name="fundamentos" rows="3"
                                          placeholder="Leis, artigos, jurisprudência relevante..."></textarea>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-lg" id="btnGerar">
                                <i class="fas fa-robot"></i> Gerar Peça com IA
                            </button>
                            <button type="button" class="btn btn-secondary btn-lg" onclick="window.history.back()">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview/Resultado -->
        <div class="col-md-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header">
                    <h5 class="mb-0">Informações</h5>
                </div>
                <div class="card-body">
                    <div id="info_processo" style="display: none;">
                        <h6>Processo Selecionado</h6>
                        <p class="mb-1"><strong>Número:</strong> <span id="info_numero"></span></p>
                        <p class="mb-1"><strong>Cliente:</strong> <span id="info_cliente"></span></p>
                        <p class="mb-3"><strong>Especialidade:</strong> <span id="info_especialidade"></span></p>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Requisitos CPC Art. 319</h6>
                        <small>
                            A petição inicial deve conter:
                            <ul class="mb-0 pl-3">
                                <li>Endereçamento ao juízo</li>
                                <li>Qualificação das partes</li>
                                <li>Fatos e fundamentos jurídicos</li>
                                <li>Pedidos específicos</li>
                                <li>Valor da causa</li>
                                <li>Provas a produzir</li>
                            </ul>
                        </small>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle"></i> Importante</h6>
                        <small>
                            A peça gerada por IA deve ser revisada por um advogado antes de ser protocolada.
                            A IA é uma ferramenta auxiliar, não substitui a análise jurídica profissional.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Resultado -->
    <div class="modal fade" id="modalResultado" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Peça Gerada</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="loading" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Gerando...</span>
                        </div>
                        <p class="mt-3">Gerando peça com IA... Isso pode levar alguns segundos.</p>
                    </div>

                    <div id="resultado" style="display: none;">
                        <div class="mb-3">
                            <button type="button" class="btn btn-success" id="btnSalvar">
                                <i class="fas fa-save"></i> Salvar
                            </button>
                            <button type="button" class="btn btn-info" id="btnCopiar">
                                <i class="fas fa-copy"></i> Copiar
                            </button>
                            <button type="button" class="btn btn-secondary" id="btnExportar">
                                <i class="fas fa-download"></i> Exportar
                            </button>
                        </div>

                        <div class="border p-3" style="background: #f8f9fa; max-height: 600px; overflow-y: auto;">
                            <pre id="conteudoPeca" style="white-space: pre-wrap; font-family: 'Times New Roman', serif; font-size: 14px;"></pre>
                        </div>
                    </div>

                    <div id="erro" class="alert alert-danger" style="display: none;">
                        <h6><i class="fas fa-exclamation-circle"></i> Erro ao Gerar Peça</h6>
                        <p id="mensagemErro"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Mostrar/ocultar campos baseado no tipo de peça
    $('#tipo_peca').change(function() {
        const tipo = $(this).val();
        
        if (tipo === 'peticao_inicial') {
            $('#campos_peticao_inicial').show();
            $('#campos_outras_pecas').hide();
        } else if (tipo) {
            $('#campos_peticao_inicial').hide();
            $('#campos_outras_pecas').show();
        } else {
            $('#campos_peticao_inicial').hide();
            $('#campos_outras_pecas').hide();
        }
    });

    // Mostrar/ocultar seleção de template
    $('#usar_template').change(function() {
        if ($(this).is(':checked')) {
            $('#div_template').show();
        } else {
            $('#div_template').hide();
        }
    });

    // Atualizar informações do processo
    $('#processo_id').change(function() {
        const option = $(this).find('option:selected');
        if (option.val()) {
            $('#info_numero').text(option.text().split(' - ')[0]);
            $('#info_cliente').text(option.data('cliente'));
            $('#info_especialidade').text(option.data('especialidade'));
            $('#info_processo').show();
        } else {
            $('#info_processo').hide();
        }
    }).trigger('change');

    // Submeter formulário
    $('#formGerarPeca').submit(function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $('#modalResultado').modal('show');
        $('#loading').show();
        $('#resultado').hide();
        $('#erro').hide();
        
        $.ajax({
            url: '{{ route("advogado.pecas.gerar") }}',
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#loading').hide();
                
                if (response.sucesso) {
                    $('#conteudoPeca').text(response.conteudo);
                    $('#resultado').show();
                } else {
                    $('#mensagemErro').text(response.erro || 'Erro desconhecido');
                    $('#erro').show();
                }
            },
            error: function(xhr) {
                $('#loading').hide();
                const erro = xhr.responseJSON?.erro || 'Erro ao comunicar com o servidor';
                $('#mensagemErro').text(erro);
                $('#erro').show();
            }
        });
    });

    // Copiar conteúdo
    $('#btnCopiar').click(function() {
        const conteudo = $('#conteudoPeca').text();
        navigator.clipboard.writeText(conteudo).then(function() {
            alert('Conteúdo copiado para a área de transferência!');
        });
    });

    // Salvar peça
    $('#btnSalvar').click(function() {
        const conteudo = $('#conteudoPeca').text();
        const titulo = prompt('Digite um título para a peça:');
        
        if (!titulo) return;
        
        $.ajax({
            url: '{{ route("advogado.pecas.salvar") }}',
            method: 'POST',
            data: {
                processo_id: $('#processo_id').val(),
                tipo_peca: $('#tipo_peca').val(),
                conteudo: conteudo,
                titulo: titulo
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.sucesso) {
                    alert('Peça salva com sucesso!');
                    $('#modalResultado').modal('hide');
                } else {
                    alert('Erro ao salvar: ' + response.erro);
                }
            },
            error: function() {
                alert('Erro ao salvar a peça');
            }
        });
    });
});
</script>
@endpush
@endsection
