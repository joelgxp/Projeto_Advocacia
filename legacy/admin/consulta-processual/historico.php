<?php 

require_once("../../conexao.php");
require_once("../../middleware.php");
requireAdmin();

try {
	// Verificar se a tabela existe
	$stmt = $pdo->query("SHOW TABLES LIKE 'consultas_processuais'");
	$tabela_existe = $stmt->rowCount() > 0;
	
	if(!$tabela_existe){
		// Criar tabela se não existir
		$pdo->exec("
			CREATE TABLE IF NOT EXISTS consultas_processuais (
				id INT AUTO_INCREMENT PRIMARY KEY,
				numero_processo VARCHAR(50) NOT NULL,
				dados_consulta TEXT,
				usuario VARCHAR(100),
				data_consulta DATETIME NOT NULL,
				INDEX idx_numero_processo (numero_processo),
				INDEX idx_data_consulta (data_consulta)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
		");
	}
	
	// Buscar últimas 10 consultas
	$stmt = $pdo->query("
		SELECT * FROM consultas_processuais 
		ORDER BY data_consulta DESC 
		LIMIT 10
	");
	
	$consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	if(empty($consultas)){
		echo '<p class="text-muted">Nenhuma consulta realizada ainda.</p>';
	} else {
		echo '<div class="table-responsive">';
		echo '<table class="table table-sm table-hover">';
		echo '<thead class="thead-light">';
		echo '<tr>';
		echo '<th>Número do Processo</th>';
		echo '<th>Data da Consulta</th>';
		echo '<th>Usuário</th>';
		echo '<th>Ações</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		
		foreach($consultas as $consulta){
			$numero_formatado = formatarNumeroProcesso($consulta['numero_processo']);
			$data_formatada = date('d/m/Y H:i', strtotime($consulta['data_consulta']));
			
			echo '<tr>';
			echo '<td>' . htmlspecialchars($numero_formatado) . '</td>';
			echo '<td>' . $data_formatada . '</td>';
			echo '<td>' . htmlspecialchars($consulta['usuario'] ?? 'N/A') . '</td>';
			echo '<td>';
			echo '<button class="btn btn-sm btn-info" onclick="verDetalhes(' . $consulta['id'] . ')">Ver Detalhes</button>';
			echo '</td>';
			echo '</tr>';
		}
		
		echo '</tbody>';
		echo '</table>';
		echo '</div>';
	}
	
} catch (Exception $e) {
	echo '<div class="alert alert-warning">Erro ao carregar histórico: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

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

?>

<script>
function verDetalhes(id){
	$.ajax({
		url: 'consulta-processual/detalhes.php',
		method: 'GET',
		data: { id: id },
		dataType: 'html',
		success: function(html){
			$('#modal-detalhes .modal-body').html(html);
			$('#modal-detalhes').modal('show');
		}
	});
}
</script>

<!-- Modal para detalhes -->
<div class="modal fade" id="modal-detalhes" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Detalhes da Consulta</h5>
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- Conteúdo será carregado via AJAX -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div>
</div>

