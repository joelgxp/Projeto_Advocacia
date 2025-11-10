<?php 

require_once("../../conexao.php");
require_once("../../middleware.php");
requireAdmin();

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
	echo json_encode(['success' => false, 'message' => 'Método não permitido']);
	exit;
}

$numero_processo = isset($_POST['numero_processo']) ? trim($_POST['numero_processo']) : '';

if(empty($numero_processo)){
	echo json_encode(['success' => false, 'message' => 'Número do processo não informado']);
	exit;
}

// Remover formatação do número do processo
$numero_processo_limpo = preg_replace('/[^0-9]/', '', $numero_processo);

// Validar formato básico (deve ter pelo menos 20 dígitos)
if(strlen($numero_processo_limpo) < 20){
	echo json_encode(['success' => false, 'message' => 'Número de processo inválido']);
	exit;
}

try {
	// ============================================
	// CONFIGURAÇÃO DA API - AJUSTE AQUI
	// ============================================
	
	// ============================================
	// API PÚBLICA DO CNJ (DataJud)
	// ============================================
	
	// Verificar se o tribunal foi informado
	$tribunal = isset($_POST['tribunal']) ? trim($_POST['tribunal']) : '';
	
	if(empty($tribunal)){
		throw new Exception('Tribunal não informado. Por favor, selecione o tribunal.');
	}
	
	// URL base da API do CNJ
	$api_base_url = 'https://api-publica.datajud.cnj.jus.br';
	
	// API Key do CNJ (Chave Pública)
	// ⚠️ IMPORTANTE: Esta chave pode ser alterada pelo CNJ a qualquer momento
	// Verifique sempre a chave atualizada na documentação oficial
	// Você pode também definir no config.php: $api_cnj_key = 'SUA_CHAVE';
	
	// Tentar pegar do config.php (já incluído via conexao.php), senão usar a chave padrão
	$api_key = isset($api_cnj_key) ? $api_cnj_key : 'cDZHYzlZa0JadVREZDJCendQbXY6SkJlTzNjLV9TRENyQk1RdnFKZGRQdw==';
	
	// Montar URL completa do tribunal
	$api_url = $api_base_url . '/' . $tribunal . '/_search';
	
	// Body da requisição no formato Elasticsearch
	// A API do CNJ usa Elasticsearch, então precisamos fazer uma query
	// Usar o número do processo sem formatação (apenas dígitos)
	$query_body = [
		'query' => [
			'match' => [
				'numeroProcesso' => $numero_processo_limpo
			]
		]
	];
	
	// Inicializar cURL
	$ch = curl_init();
	
	// Configurar opções do cURL para POST
	curl_setopt_array($ch, [
		CURLOPT_URL => $api_url,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => json_encode($query_body),
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_HTTPHEADER => [
			'Content-Type: application/json',
			'Accept: application/json',
			'Authorization: APIKey ' . $api_key, // Autenticação da API do CNJ
		],
	]);
	
	// Executar requisição
	$response = curl_exec($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$curl_error = curl_error($ch);
	$curl_info = curl_getinfo($ch);
	
	curl_close($ch);
	
	// Verificar erros do cURL
	if($curl_error){
		throw new Exception('Erro na conexão: ' . $curl_error);
	}
	
	// Verificar código HTTP
	if($http_code !== 200){
		// Tentar decodificar resposta de erro
		$error_details = '';
		if(!empty($response)){
			$error_data = json_decode($response, true);
			if(json_last_error() === JSON_ERROR_NONE && isset($error_data['message'])){
				$error_details = ': ' . $error_data['message'];
			} elseif(json_last_error() === JSON_ERROR_NONE && isset($error_data['error'])){
				$error_details = ': ' . (is_string($error_data['error']) ? $error_data['error'] : json_encode($error_data['error']));
			} elseif(json_last_error() === JSON_ERROR_NONE && isset($error_data['mensagem'])){
				$error_details = ': ' . $error_data['mensagem'];
			} else {
				// Se não for JSON, mostrar os primeiros 200 caracteres da resposta
				$error_details = ': ' . substr(strip_tags($response), 0, 200);
			}
		} else {
			$error_details = ' (Resposta vazia)';
		}
		
		// Informações adicionais para debug
		$debug_info = '';
		if(isset($_GET['debug']) && $_GET['debug'] === '1'){
			$debug_info = ' | URL: ' . $curl_info['url'];
			if(!empty($response)){
				$debug_info .= ' | Response: ' . substr($response, 0, 500);
			} else {
				$debug_info .= ' | Response: (vazia)';
			}
			$debug_info .= ' | Request Headers: ' . json_encode($curl_info, JSON_PRETTY_PRINT);
		}
		
		// Mensagem mais detalhada
		$error_message = 'Erro na API. Código HTTP: ' . $http_code . $error_details;
		
		// Adicionar dicas baseadas no código HTTP
		if($http_code === 400){
			$error_message .= ' | Dica: Verifique se o formato do número do processo está correto e se todos os parâmetros necessários foram enviados.';
		} elseif($http_code === 401){
			$error_message .= ' | Dica: Verifique se o token de autenticação está correto.';
		} elseif($http_code === 404){
			$error_message .= ' | Dica: Verifique se a URL da API está correta.';
		}
		
		throw new Exception($error_message . $debug_info);
	}
	
	// Decodificar resposta JSON
	$dados_api = json_decode($response, true);
	
	if(json_last_error() !== JSON_ERROR_NONE){
		$json_error_msg = json_last_error_msg();
		throw new Exception('Erro ao decodificar resposta da API: ' . $json_error_msg . ' | Resposta: ' . substr($response, 0, 200));
	}
	
	// Verificar se a API retornou um erro mesmo com código 200
	if(isset($dados_api['error']) || isset($dados_api['erro'])){
		$error_msg = $dados_api['error'] ?? $dados_api['erro'] ?? 'Erro desconhecido';
		throw new Exception('API retornou erro: ' . (is_string($error_msg) ? $error_msg : json_encode($error_msg)));
	}
	
	// ============================================
	// PROCESSAR RESPOSTA DA API DO CNJ (Elasticsearch)
	// ============================================
	
	// A API do CNJ retorna no formato Elasticsearch
	// Verificar se há resultados
	if(!isset($dados_api['hits']) || !isset($dados_api['hits']['hits']) || empty($dados_api['hits']['hits'])){
		throw new Exception('Processo não encontrado na base de dados do CNJ.');
	}
	
	// Pegar o primeiro resultado
	$processo = $dados_api['hits']['hits'][0]['_source'] ?? null;
	
	if(!$processo){
		throw new Exception('Erro ao processar resposta da API.');
	}
	
	// Processar dados do processo conforme estrutura real da API do CNJ
	$numero_formatado = formatarNumeroProcesso($processo['numeroProcesso'] ?? $numero_processo_limpo);
	
	// Processar classe
	$classe_nome = 'Não informado';
	if(isset($processo['classe'])){
		if(is_array($processo['classe']) && isset($processo['classe']['nome'])){
			$classe_nome = $processo['classe']['nome'];
		} elseif(is_string($processo['classe'])){
			$classe_nome = $processo['classe'];
		}
	}
	
	// Processar assuntos
	$assuntos_texto = 'Não informado';
	if(isset($processo['assuntos']) && is_array($processo['assuntos'])){
		$assuntos_nomes = [];
		foreach($processo['assuntos'] as $assunto){
			if(isset($assunto['nome'])){
				$assuntos_nomes[] = $assunto['nome'];
			}
		}
		if(!empty($assuntos_nomes)){
			$assuntos_texto = implode(', ', $assuntos_nomes);
		}
	}
	
	// Processar órgão julgador
	$orgao_julgador = 'Não informado';
	if(isset($processo['orgaoJulgador'])){
		if(is_array($processo['orgaoJulgador']) && isset($processo['orgaoJulgador']['nome'])){
			$orgao_julgador = $processo['orgaoJulgador']['nome'];
		} elseif(is_string($processo['orgaoJulgador'])){
			$orgao_julgador = $processo['orgaoJulgador'];
		}
	}
	
	// Processar data de ajuizamento
	$data_ajuizamento = 'Não informado';
	if(isset($processo['dataAjuizamento'])){
		$data_ajuizamento = formatarDataAjuizamento($processo['dataAjuizamento']);
	}
	
	// Processar data de última atualização
	$data_ultima_atualizacao = 'Não informado';
	if(isset($processo['dataHoraUltimaAtualizacao'])){
		$data_ultima_atualizacao = formatarData($processo['dataHoraUltimaAtualizacao']);
	}
	
	// Processar sistema
	$sistema = 'Não informado';
	if(isset($processo['sistema']) && is_array($processo['sistema']) && isset($processo['sistema']['nome'])){
		$sistema = $processo['sistema']['nome'];
	}
	
	// Processar formato
	$formato = 'Não informado';
	if(isset($processo['formato']) && is_array($processo['formato']) && isset($processo['formato']['nome'])){
		$formato = $processo['formato']['nome'];
	}
	
	$dados_processados = [
		'numero_processo' => $numero_formatado,
		'numero_processo_original' => $processo['numeroProcesso'] ?? $numero_processo_limpo,
		'classe' => $classe_nome,
		'assunto' => $assuntos_texto,
		'vara' => $orgao_julgador,
		'status' => 'Ativo', // A API não retorna status direto, mas se tem movimentos, está ativo
		'data_ajuizamento' => $data_ajuizamento,
		'data_ultima_atualizacao' => $data_ultima_atualizacao,
		'valor_causa' => isset($processo['valorCausa']) ? $processo['valorCausa'] : null,
		'partes' => isset($processo['partes']) ? formatarPartesCNJ($processo['partes']) : 'Não informado',
		'movimentacoes' => isset($processo['movimentos']) ? formatarMovimentacoesCNJ($processo['movimentos']) : 'Não informado',
		'tribunal' => $processo['tribunal'] ?? 'Não informado',
		'grau' => $processo['grau'] ?? 'Não informado',
		'sistema' => $sistema,
		'formato' => $formato,
		'nivel_sigilo' => isset($processo['nivelSigilo']) ? $processo['nivelSigilo'] : 0,
		'dados_completos' => $processo // Manter dados completos para referência
	];
	
	// Salvar consulta no histórico
	salvarHistorico($numero_processo_limpo, $dados_processados, $_SESSION['cpf_usuario'] ?? 'admin');
	
	// Retornar sucesso
	echo json_encode([
		'success' => true,
		'data' => $dados_processados
	]);
	
} catch (Exception $e) {
	// Log do erro (em produção, use um sistema de logs adequado)
	error_log('Erro na consulta processual: ' . $e->getMessage());
	
	// Retornar erro
	echo json_encode([
		'success' => false,
		'message' => 'Erro ao consultar processo: ' . $e->getMessage()
	]);
}

// ============================================
// FUNÇÕES AUXILIARES
// ============================================

/**
 * Formata número do processo no padrão brasileiro
 */
function formatarNumeroProcesso($numero){
	if(strlen($numero) >= 20){
		return substr($numero, 0, 7) . '-' . 
		       substr($numero, 7, 2) . '.' . 
		       substr($numero, 9, 4) . '.' . 
		       substr($numero, 13, 1) . '.' . 
		       substr($numero, 14, 2) . '.' . 
		       substr($numero, 16);
	}
	return $numero;
}

/**
 * Formata data
 */
function formatarData($data){
	if(empty($data)) return 'Não informado';
	
	// Tentar diferentes formatos
	$timestamp = strtotime($data);
	if($timestamp){
		return date('d/m/Y H:i', $timestamp);
	}
	return $data;
}

/**
 * Formata data de ajuizamento (formato: YYYYMMDDHHMMSS)
 */
function formatarDataAjuizamento($data){
	if(empty($data) || strlen($data) < 8) return 'Não informado';
	
	// Formato: YYYYMMDDHHMMSS ou YYYYMMDD
	$ano = substr($data, 0, 4);
	$mes = substr($data, 4, 2);
	$dia = substr($data, 6, 2);
	
	if(strlen($data) >= 14){
		$hora = substr($data, 8, 2);
		$minuto = substr($data, 10, 2);
		$segundo = substr($data, 12, 2);
		return sprintf('%s/%s/%s %s:%s:%s', $dia, $mes, $ano, $hora, $minuto, $segundo);
	}
	
	return sprintf('%s/%s/%s', $dia, $mes, $ano);
}

/**
 * Formata partes do processo (formato CNJ)
 */
function formatarPartesCNJ($partes){
	if(is_array($partes)){
		$html = '<ul class="list-unstyled mb-0">';
		foreach($partes as $parte){
			$tipo = $parte['tipo'] ?? $parte['tipoParte'] ?? '';
			$nome = $parte['nome'] ?? $parte['nomeParte'] ?? '';
			$documento = isset($parte['documento']) ? ' (' . $parte['documento'] . ')' : '';
			$html .= '<li><strong>' . htmlspecialchars($tipo) . ':</strong> ' . htmlspecialchars($nome) . $documento . '</li>';
		}
		$html .= '</ul>';
		return $html;
	}
	return $partes;
}

/**
 * Formata movimentações (formato CNJ)
 */
function formatarMovimentacoesCNJ($movimentacoes){
	if(is_array($movimentacoes)){
		// Ordenar por data (mais recentes primeiro)
		usort($movimentacoes, function($a, $b){
			$dataA = $a['dataHora'] ?? $a['data'] ?? '';
			$dataB = $b['dataHora'] ?? $b['data'] ?? '';
			return strcmp($dataB, $dataA);
		});
		
		$html = '<div class="list-group" style="max-height: 400px; overflow-y: auto;">';
		foreach(array_slice($movimentacoes, 0, 10) as $mov){ // Últimas 10 movimentações
			$data = $mov['dataHora'] ?? $mov['data'] ?? '';
			$nome = $mov['nome'] ?? 'Movimentação';
			$codigo = $mov['codigo'] ?? '';
			
			// Processar complementos tabelados se existirem
			$complementos = '';
			if(isset($mov['complementosTabelados']) && is_array($mov['complementosTabelados'])){
				$comps = [];
				foreach($mov['complementosTabelados'] as $comp){
					if(isset($comp['nome'])){
						$comps[] = $comp['nome'];
					}
				}
				if(!empty($comps)){
					$complementos = ' (' . implode(', ', $comps) . ')';
				}
			}
			
			$html .= '<div class="list-group-item">';
			$html .= '<div class="d-flex justify-content-between align-items-start">';
			$html .= '<div class="flex-grow-1">';
			$html .= '<strong>' . htmlspecialchars($nome) . '</strong>';
			if($complementos){
				$html .= '<small class="text-muted">' . htmlspecialchars($complementos) . '</small>';
			}
			$html .= '</div>';
			$html .= '<small class="text-muted ml-3">' . formatarData($data) . '</small>';
			$html .= '</div>';
			$html .= '</div>';
		}
		$html .= '</div>';
		return $html;
	}
	return $movimentacoes;
}

/**
 * Formata partes do processo (formato genérico)
 */
function formatarPartes($partes){
	if(is_array($partes)){
		$html = '<ul class="list-unstyled mb-0">';
		foreach($partes as $parte){
			$html .= '<li><strong>' . ($parte['tipo'] ?? '') . ':</strong> ' . ($parte['nome'] ?? '') . '</li>';
		}
		$html .= '</ul>';
		return $html;
	}
	return $partes;
}

/**
 * Formata movimentações (formato genérico)
 */
function formatarMovimentacoes($movimentacoes){
	if(is_array($movimentacoes)){
		$html = '<div class="list-group">';
		foreach(array_slice($movimentacoes, 0, 5) as $mov){ // Últimas 5 movimentações
			$html .= '<div class="list-group-item">';
			$html .= '<small class="text-muted">' . formatarData($mov['data'] ?? '') . '</small><br>';
			$html .= '<strong>' . ($mov['tipo'] ?? '') . '</strong><br>';
			$html .= '<small>' . ($mov['descricao'] ?? '') . '</small>';
			$html .= '</div>';
		}
		$html .= '</div>';
		return $html;
	}
	return $movimentacoes;
}

/**
 * Salva consulta no histórico
 */
function salvarHistorico($numero_processo, $dados, $usuario){
	global $pdo;
	
	try {
		$stmt = $pdo->prepare("
			INSERT INTO consultas_processuais 
			(numero_processo, dados_consulta, usuario, data_consulta) 
			VALUES (:numero_processo, :dados_consulta, :usuario, NOW())
		");
		
		$stmt->bindValue(':numero_processo', $numero_processo);
		$stmt->bindValue(':dados_consulta', json_encode($dados));
		$stmt->bindValue(':usuario', $usuario);
		
		$stmt->execute();
	} catch (Exception $e) {
		// Se a tabela não existir, apenas logar o erro
		error_log('Erro ao salvar histórico: ' . $e->getMessage());
	}
}

?>

