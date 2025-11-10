<?php 
require_once("../middleware.php");
requireAdmin();

$pagina = 'consulta-processual'; 
?>

<style>
/* Forçar largura total para a página de consulta processual */
.conteudo-painel {
	max-width: 100% !important;
	width: 100% !important;
	padding-left: 15px;
	padding-right: 15px;
}

/* Garantir que os cards ocupem toda a largura */
#form-consulta,
.card {
	width: 100% !important;
	max-width: 100% !important;
}

/* Ajustar largura dos campos do formulário */
.form-control,
.form-control-lg {
	width: 100% !important;
}
</style>

<div class="row">
	<div class="col-md-12">
		<h2 class="mb-4">Consulta Processual</h2>
		<p class="text-muted">Digite o número do processo para consultar informações atualizadas</p>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header bg-info text-white">
				<h5 class="mb-0"><i class="fas fa-search"></i> Consultar Processo</h5>
			</div>
			<div class="card-body">
				<form id="form-consulta" method="post">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="tipo_tribunal"><strong>Tipo de Tribunal</strong></label>
								<select class="form-control" id="tipo_tribunal" name="tipo_tribunal" required>
									<option value="">Selecione o tipo...</option>
									<option value="superiores">Tribunais Superiores</option>
									<option value="federal">Justiça Federal</option>
									<option value="estadual">Justiça Estadual</option>
									<option value="trabalho">Justiça do Trabalho</option>
									<option value="eleitoral">Justiça Eleitoral</option>
									<option value="militar">Justiça Militar</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="tribunal"><strong>Tribunal</strong></label>
								<select class="form-control" id="tribunal" name="tribunal" required disabled>
									<option value="">Primeiro selecione o tipo de tribunal</option>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="numero_processo"><strong>Número do Processo</strong></label>
						<input type="text" 
						       class="form-control form-control-lg" 
						       id="numero_processo" 
						       name="numero_processo" 
						       placeholder="Ex: 0000123-45.2023.8.26.0100" 
						       required
						       autocomplete="off">
						<small class="form-text text-muted">
							Digite o número completo do processo (com ou sem formatação)
						</small>
					</div>

					<div id="mensagem-consulta" class="mt-3"></div>

					<button type="submit" class="btn btn-info btn-lg btn-block" id="btn-consultar">
						<i class="fas fa-search"></i> Consultar Processo
					</button>
					
					<div class="form-check mt-3">
						<input type="checkbox" class="form-check-input" id="modo-debug">
						<label class="form-check-label" for="modo-debug">
							Modo Debug (mostra detalhes técnicos do erro)
						</label>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Área de Resultados -->
<div class="row mt-4" id="area-resultados" style="display: none;">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header bg-success text-white">
				<h5 class="mb-0"><i class="fas fa-file-alt"></i> Resultado da Consulta</h5>
			</div>
			<div class="card-body" id="resultado-consulta" style="padding: 20px;">
				<!-- Resultados serão inseridos aqui via AJAX -->
			</div>
		</div>
	</div>
</div>

<!-- Área de Histórico de Consultas -->
<div class="row mt-4">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h5 class="mb-0"><i class="fas fa-history"></i> Histórico de Consultas</h5>
			</div>
			<div class="card-body">
				<div id="historico-consultas">
					<!-- Histórico será carregado aqui -->
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>

<script type="text/javascript">
// Mapeamento de tribunais
var tribunais = {
	'superiores': [
		{ nome: 'Tribunal Superior do Trabalho', url: 'api_publica_tst' },
		{ nome: 'Tribunal Superior Eleitoral', url: 'api_publica_tse' },
		{ nome: 'Tribunal Superior de Justiça', url: 'api_publica_stj' },
		{ nome: 'Tribunal Superior Militar', url: 'api_publica_stm' }
	],
	'federal': [
		{ nome: 'TRF da 1ª Região', url: 'api_publica_trf1' },
		{ nome: 'TRF da 2ª Região', url: 'api_publica_trf2' },
		{ nome: 'TRF da 3ª Região', url: 'api_publica_trf3' },
		{ nome: 'TRF da 4ª Região', url: 'api_publica_trf4' },
		{ nome: 'TRF da 5ª Região', url: 'api_publica_trf5' },
		{ nome: 'TRF da 6ª Região', url: 'api_publica_trf6' }
	],
	'estadual': [
		{ nome: 'TJ do Acre', url: 'api_publica_tjac' },
		{ nome: 'TJ de Alagoas', url: 'api_publica_tjal' },
		{ nome: 'TJ do Amazonas', url: 'api_publica_tjam' },
		{ nome: 'TJ do Amapá', url: 'api_publica_tjap' },
		{ nome: 'TJ da Bahia', url: 'api_publica_tjba' },
		{ nome: 'TJ do Ceará', url: 'api_publica_tjce' },
		{ nome: 'TJ do Distrito Federal', url: 'api_publica_tjdft' },
		{ nome: 'TJ do Espírito Santo', url: 'api_publica_tjes' },
		{ nome: 'TJ de Goiás', url: 'api_publica_tjgo' },
		{ nome: 'TJ do Maranhão', url: 'api_publica_tjma' },
		{ nome: 'TJ de Minas Gerais', url: 'api_publica_tjmg' },
		{ nome: 'TJ do Mato Grosso do Sul', url: 'api_publica_tjms' },
		{ nome: 'TJ do Mato Grosso', url: 'api_publica_tjmt' },
		{ nome: 'TJ do Pará', url: 'api_publica_tjpa' },
		{ nome: 'TJ da Paraíba', url: 'api_publica_tjpb' },
		{ nome: 'TJ de Pernambuco', url: 'api_publica_tjpe' },
		{ nome: 'TJ do Piauí', url: 'api_publica_tjpi' },
		{ nome: 'TJ do Paraná', url: 'api_publica_tjpr' },
		{ nome: 'TJ do Rio de Janeiro', url: 'api_publica_tjrj' },
		{ nome: 'TJ do Rio Grande do Norte', url: 'api_publica_tjrn' },
		{ nome: 'TJ de Rondônia', url: 'api_publica_tjro' },
		{ nome: 'TJ de Roraima', url: 'api_publica_tjrr' },
		{ nome: 'TJ do Rio Grande do Sul', url: 'api_publica_tjrs' },
		{ nome: 'TJ de Santa Catarina', url: 'api_publica_tjsc' },
		{ nome: 'TJ de Sergipe', url: 'api_publica_tjse' },
		{ nome: 'TJ de São Paulo', url: 'api_publica_tjsp' },
		{ nome: 'TJ do Tocantins', url: 'api_publica_tjto' }
	],
	'trabalho': [
		{ nome: 'TRT da 1ª Região', url: 'api_publica_trt1' },
		{ nome: 'TRT da 2ª Região', url: 'api_publica_trt2' },
		{ nome: 'TRT da 3ª Região', url: 'api_publica_trt3' },
		{ nome: 'TRT da 4ª Região', url: 'api_publica_trt4' },
		{ nome: 'TRT da 5ª Região', url: 'api_publica_trt5' },
		{ nome: 'TRT da 6ª Região', url: 'api_publica_trt6' },
		{ nome: 'TRT da 7ª Região', url: 'api_publica_trt7' },
		{ nome: 'TRT da 8ª Região', url: 'api_publica_trt8' },
		{ nome: 'TRT da 9ª Região', url: 'api_publica_trt9' },
		{ nome: 'TRT da 10ª Região', url: 'api_publica_trt10' },
		{ nome: 'TRT da 11ª Região', url: 'api_publica_trt11' },
		{ nome: 'TRT da 12ª Região', url: 'api_publica_trt12' },
		{ nome: 'TRT da 13ª Região', url: 'api_publica_trt13' },
		{ nome: 'TRT da 14ª Região', url: 'api_publica_trt14' },
		{ nome: 'TRT da 15ª Região', url: 'api_publica_trt15' },
		{ nome: 'TRT da 16ª Região', url: 'api_publica_trt16' },
		{ nome: 'TRT da 17ª Região', url: 'api_publica_trt17' },
		{ nome: 'TRT da 18ª Região', url: 'api_publica_trt18' },
		{ nome: 'TRT da 19ª Região', url: 'api_publica_trt19' },
		{ nome: 'TRT da 20ª Região', url: 'api_publica_trt20' },
		{ nome: 'TRT da 21ª Região', url: 'api_publica_trt21' },
		{ nome: 'TRT da 22ª Região', url: 'api_publica_trt22' },
		{ nome: 'TRT da 23ª Região', url: 'api_publica_trt23' },
		{ nome: 'TRT da 24ª Região', url: 'api_publica_trt24' }
	],
	'eleitoral': [
		{ nome: 'TRE do Acre', url: 'api_publica_tre-ac' },
		{ nome: 'TRE de Alagoas', url: 'api_publica_tre-al' },
		{ nome: 'TRE do Amazonas', url: 'api_publica_tre-am' },
		{ nome: 'TRE do Amapá', url: 'api_publica_tre-ap' },
		{ nome: 'TRE da Bahia', url: 'api_publica_tre-ba' },
		{ nome: 'TRE do Ceará', url: 'api_publica_tre-ce' },
		{ nome: 'TRE do Distrito Federal', url: 'api_publica_tre-dft' },
		{ nome: 'TRE do Espírito Santo', url: 'api_publica_tre-es' },
		{ nome: 'TRE de Goiás', url: 'api_publica_tre-go' },
		{ nome: 'TRE do Maranhão', url: 'api_publica_tre-ma' },
		{ nome: 'TRE de Minas Gerais', url: 'api_publica_tre-mg' },
		{ nome: 'TRE do Mato Grosso do Sul', url: 'api_publica_tre-ms' },
		{ nome: 'TRE do Mato Grosso', url: 'api_publica_tre-mt' },
		{ nome: 'TRE do Pará', url: 'api_publica_tre-pa' },
		{ nome: 'TRE da Paraíba', url: 'api_publica_tre-pb' },
		{ nome: 'TRE de Pernambuco', url: 'api_publica_tre-pe' },
		{ nome: 'TRE do Piauí', url: 'api_publica_tre-pi' },
		{ nome: 'TRE do Paraná', url: 'api_publica_tre-pr' },
		{ nome: 'TRE do Rio de Janeiro', url: 'api_publica_tre-rj' },
		{ nome: 'TRE do Rio Grande do Norte', url: 'api_publica_tre-rn' },
		{ nome: 'TRE de Rondônia', url: 'api_publica_tre-ro' },
		{ nome: 'TRE de Roraima', url: 'api_publica_tre-rr' },
		{ nome: 'TRE do Rio Grande do Sul', url: 'api_publica_tre-rs' },
		{ nome: 'TRE de Santa Catarina', url: 'api_publica_tre-sc' },
		{ nome: 'TRE de Sergipe', url: 'api_publica_tre-se' },
		{ nome: 'TRE de São Paulo', url: 'api_publica_tre-sp' },
		{ nome: 'TRE do Tocantins', url: 'api_publica_tre-to' }
	],
	'militar': [
		{ nome: 'TJM de Minas Gerais', url: 'api_publica_tjmmg' },
		{ nome: 'TJM do Rio Grande do Sul', url: 'api_publica_tjmrs' },
		{ nome: 'TJM de São Paulo', url: 'api_publica_tjmsp' }
	]
};

$(document).ready(function(){
	
	// Máscara para número de processo (formato: NNNNNNN-DD.AAAA.J.TR.OOOO)
	$('#numero_processo').mask('0000000-00.0000.0.00.0000', {
		reverse: false,
		placeholder: '0000000-00.0000.0.00.0000'
	});

	// Quando o tipo de tribunal mudar, atualizar lista de tribunais
	$('#tipo_tribunal').change(function(){
		var tipo = $(this).val();
		var selectTribunal = $('#tribunal');
		
		selectTribunal.empty();
		
		if(tipo && tribunais[tipo]){
			selectTribunal.append('<option value="">Selecione o tribunal...</option>');
			$.each(tribunais[tipo], function(index, tribunal){
				selectTribunal.append('<option value="' + tribunal.url + '">' + tribunal.nome + '</option>');
			});
			selectTribunal.prop('disabled', false);
		} else {
			selectTribunal.append('<option value="">Primeiro selecione o tipo de tribunal</option>');
			selectTribunal.prop('disabled', true);
		}
	});

	// Submissão do formulário
	$('#form-consulta').submit(function(event){
		event.preventDefault();
		
		var numeroProcesso = $('#numero_processo').val();
		
		// Remover formatação para enviar à API
		numeroProcesso = numeroProcesso.replace(/[^0-9]/g, '');
		
		if(numeroProcesso.length < 20){
			$('#mensagem-consulta').html('<div class="alert alert-warning">Por favor, digite um número de processo válido.</div>');
			return false;
		}

		// Desabilitar botão durante a consulta
		$('#btn-consultar').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Consultando...');
		$('#mensagem-consulta').html('');
		$('#area-resultados').hide();

		// Verificar se tribunal foi selecionado
		var tribunal = $('#tribunal').val();
		if(!tribunal){
			$('#mensagem-consulta').html('<div class="alert alert-warning">Por favor, selecione o tribunal.</div>');
			return false;
		}

		// Verificar se modo debug está ativo
		var modoDebug = $('#modo-debug').is(':checked') ? '1' : '0';
		
		// Fazer requisição AJAX
		$.ajax({
			url: 'consulta-processual/consultar.php?debug=' + modoDebug,
			method: 'POST',
			data: {
				numero_processo: numeroProcesso,
				tribunal: tribunal
			},
			dataType: 'json',
			success: function(response){
				$('#btn-consultar').prop('disabled', false).html('<i class="fas fa-search"></i> Consultar Processo');
				
				if(response.success){
					// Exibir resultados
					exibirResultados(response.data);
					$('#area-resultados').show();
					$('#mensagem-consulta').html('<div class="alert alert-success">Consulta realizada com sucesso!</div>');
					
					// Recarregar histórico
					carregarHistorico();
				} else {
					$('#mensagem-consulta').html('<div class="alert alert-danger">' + (response.message || 'Erro ao consultar processo. Tente novamente.') + '</div>');
				}
			},
			error: function(xhr, status, error){
				$('#btn-consultar').prop('disabled', false).html('<i class="fas fa-search"></i> Consultar Processo');
				$('#mensagem-consulta').html('<div class="alert alert-danger">Erro ao conectar com o servidor. Verifique sua conexão e tente novamente.</div>');
				console.error('Erro:', error);
			}
		});
	});

	// Função para exibir resultados
	function exibirResultados(dados){
		var html = '<div class="table-responsive" style="width: 100%;">';
		html += '<table class="table table-bordered table-striped" style="width: 100%; font-size: 14px;">';
		
		if(dados.numero_processo){
			html += '<tr><th width="20%">Número do Processo:</th><td width="80%"><strong>' + dados.numero_processo + '</strong></td></tr>';
		}
		if(dados.tribunal){
			html += '<tr><th width="20%">Tribunal:</th><td width="80%">' + dados.tribunal + '</td></tr>';
		}
		if(dados.grau){
			html += '<tr><th width="20%">Grau:</th><td width="80%">' + dados.grau + '</td></tr>';
		}
		if(dados.classe){
			html += '<tr><th width="20%">Classe:</th><td width="80%">' + dados.classe + '</td></tr>';
		}
		if(dados.assunto){
			html += '<tr><th width="20%">Assunto:</th><td width="80%">' + dados.assunto + '</td></tr>';
		}
		if(dados.vara){
			html += '<tr><th width="20%">Órgão Julgador:</th><td width="80%">' + dados.vara + '</td></tr>';
		}
		if(dados.status){
			html += '<tr><th width="20%">Status:</th><td width="80%"><span class="badge badge-info">' + dados.status + '</span></td></tr>';
		}
		if(dados.data_ajuizamento){
			html += '<tr><th width="20%">Data de Ajuizamento:</th><td width="80%">' + dados.data_ajuizamento + '</td></tr>';
		}
		if(dados.data_ultima_atualizacao){
			html += '<tr><th width="20%">Última Atualização:</th><td width="80%">' + dados.data_ultima_atualizacao + '</td></tr>';
		}
		if(dados.sistema){
			html += '<tr><th width="20%">Sistema:</th><td width="80%">' + dados.sistema + '</td></tr>';
		}
		if(dados.formato){
			html += '<tr><th width="20%">Formato:</th><td width="80%">' + dados.formato + '</td></tr>';
		}
		if(dados.valor_causa){
			html += '<tr><th width="20%">Valor da Causa:</th><td width="80%">R$ ' + parseFloat(dados.valor_causa).toLocaleString('pt-BR', {minimumFractionDigits: 2}) + '</td></tr>';
		}
		if(dados.partes){
			html += '<tr><th width="20%">Partes:</th><td width="80%">' + dados.partes + '</td></tr>';
		}
		if(dados.movimentacoes){
			html += '<tr><th width="20%">Últimas Movimentações:</th><td width="80%"><div class="mt-2">' + dados.movimentacoes + '</div></td></tr>';
		}
		
		html += '</table>';
		html += '</div>';
		
		// Adicionar dados brutos se disponível
		if(dados.dados_completos){
			html += '<div class="mt-3"><button class="btn btn-sm btn-secondary" type="button" data-toggle="collapse" data-target="#dados-completos">Ver Dados Completos</button>';
			html += '<div class="collapse mt-2" id="dados-completos"><pre class="bg-light p-3" style="max-height: 500px; overflow-y: auto;">' + JSON.stringify(dados.dados_completos, null, 2) + '</pre></div></div>';
		}
		
		$('#resultado-consulta').html(html);
	}

	// Função para carregar histórico
	function carregarHistorico(){
		$.ajax({
			url: 'consulta-processual/historico.php',
			method: 'GET',
			dataType: 'html',
			success: function(html){
				$('#historico-consultas').html(html);
			}
		});
	}

	// Carregar histórico ao abrir a página
	carregarHistorico();
});
</script>

