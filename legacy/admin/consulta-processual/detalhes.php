<?php 

require_once("../../conexao.php");
require_once("../../middleware.php");
requireAdmin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id <= 0){
	echo '<div class="alert alert-danger">ID inválido</div>';
	exit;
}

try {
	$stmt = $pdo->prepare("SELECT * FROM consultas_processuais WHERE id = :id");
	$stmt->bindValue(':id', $id);
	$stmt->execute();
	
	$consulta = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if(!$consulta){
		echo '<div class="alert alert-warning">Consulta não encontrada</div>';
		exit;
	}
	
	$dados = json_decode($consulta['dados_consulta'], true);
	
	echo '<div class="table-responsive">';
	echo '<table class="table table-bordered">';
	echo '<tr><th width="200">Número do Processo:</th><td>' . htmlspecialchars($consulta['numero_processo']) . '</td></tr>';
	echo '<tr><th>Data da Consulta:</th><td>' . date('d/m/Y H:i:s', strtotime($consulta['data_consulta'])) . '</td></tr>';
	echo '<tr><th>Usuário:</th><td>' . htmlspecialchars($consulta['usuario'] ?? 'N/A') . '</td></tr>';
	
	if($dados){
		foreach($dados as $key => $value){
			if($key !== 'dados_completos' && !empty($value)){
				$label = ucfirst(str_replace('_', ' ', $key));
				echo '<tr><th>' . $label . ':</th><td>' . (is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : htmlspecialchars($value)) . '</td></tr>';
			}
		}
	}
	
	echo '</table>';
	echo '</div>';
	
	if(isset($dados['dados_completos'])){
		echo '<div class="mt-3">';
		echo '<h6>Dados Completos da API:</h6>';
		echo '<pre class="bg-light p-3" style="max-height: 400px; overflow-y: auto;">';
		echo htmlspecialchars(json_encode($dados['dados_completos'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
		echo '</pre>';
		echo '</div>';
	}
	
} catch (Exception $e) {
	echo '<div class="alert alert-danger">Erro ao carregar detalhes: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

?>

