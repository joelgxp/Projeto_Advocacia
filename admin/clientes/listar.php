<?php 

require_once("../../conexao.php");
$pagina = 'clientes';

$txtbuscar = @$_POST['txtbuscar'];


echo '
<table class="table table-sm mt-3 tabelas">
	<thead class="thead-light">
		<tr>
			<th scope="col">Nome</th>
			<th scope="col">CPF / CNPJ</th>
			<th scope="col">Telefone</th>
			<th scope="col">Email</th>
			<th scope="col">Tipo</th>
			<th scope="col">Ações</th>
		</tr>
	</thead>
	<tbody>';

	
	    $itens_por_pagina = $_POST['itens'];

	//PEGAR A PÁGINA ATUAL
		$pagina_pag = intval(@$_POST['pag']);
		
		$limite = $pagina_pag * $itens_por_pagina;

		//CAMINHO DA PAGINAÇÃO
		$caminho_pag = 'index.php?acao='.$pagina.'&';

	if($txtbuscar == ''){
		$res = $pdo->query("SELECT * from clientes order by id desc LIMIT $limite, $itens_por_pagina");
	}else{
		$txtbuscar = '%'.@$_POST['txtbuscar'].'%';
		$res = $pdo->query("SELECT * from clientes where nome LIKE '$txtbuscar' or cpf LIKE '$txtbuscar' order by id desc");

	}
	
	$dados = $res->fetchAll(PDO::FETCH_ASSOC);


	//TOTALIZAR OS REGISTROS PARA PAGINAÇÃO
		$res_todos = $pdo->query("SELECT * from clientes");
		$dados_total = $res_todos->fetchAll(PDO::FETCH_ASSOC);
		$num_total = count($dados_total);

		//DEFINIR O TOTAL DE PAGINAS
		$num_paginas = ceil($num_total/$itens_por_pagina);


	for ($i=0; $i < count($dados); $i++) { 
			foreach ($dados[$i] as $key => $value) {
			}

			$id = $dados[$i]['id'];	
			$nome = $dados[$i]['nome'];
			
			$cpf = $dados[$i]['cpf'];
			$telefone = $dados[$i]['telefone'];
			$email = $dados[$i]['email'];
			$tipo_pessoa = $dados[$i]['tipo_pessoa'];

			// Buscar processo do cliente
			$res_proc = $pdo->query("SELECT num_processo, vara FROM processos WHERE cliente = '$cpf' LIMIT 1");
			$dados_proc = $res_proc->fetchAll(PDO::FETCH_ASSOC);
			$tem_processo = count($dados_proc) > 0;
			$numero_processo = $tem_processo ? $dados_proc[0]['num_processo'] : '';
			$tribunal = $tem_processo ? $dados_proc[0]['vara'] : '';

echo '
		<tr>
			<td>'.$nome.'</td>
			<td>'.$cpf.'</td>
			<td>'.$telefone.'</td>
			<td>'.$email.'</td>
			<td>'.$tipo_pessoa.'</td>
			<td>
				<a href="index.php?acao='.$pagina.'&funcao=editar&id='.$id.'"><i class="fas fa-edit text-info"></i></a>
				<a href="index.php?acao='.$pagina.'&funcao=excluir&id='.$id.'"><i class="far fa-trash-alt text-danger"></i></a>';
			
			if($tem_processo && $numero_processo && $tribunal){
				echo '<a href="#" class="btn-consulta-processo" 
				      data-nome="'.htmlspecialchars($nome).'" 
				      data-processo="'.htmlspecialchars($numero_processo).'" 
				      data-tribunal="'.htmlspecialchars($tribunal).'"
				      title="Consultar Processo">
				      <i class="fas fa-search text-success"></i>
				      </a>';
			}
			
echo '
			</td>
		</tr>';

	}

echo  '
	</tbody>
</table> 

<!-- Modal para exibir resultado da consulta processual -->
<div class="modal fade" id="modal-consulta-processo" tabindex="-1" role="dialog" aria-labelledby="modalConsultaProcessoLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h5 class="modal-title" id="modalConsultaProcessoLabel">
					<i class="fas fa-search"></i> Consulta Processual - <span id="nome-cliente-consulta"></span>
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="conteudo-consulta-processo">
				<div class="text-center">
					<div class="spinner-border text-primary" role="status">
						<span class="sr-only">Consultando...</span>
					</div>
					<p class="mt-2">Consultando processo...</p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function(){
	// Clique no botão de consulta de processo
	$(".btn-consulta-processo").click(function(e){
		e.preventDefault();
		
		var nomeCliente = $(this).data("nome");
		var numeroProcesso = $(this).data("processo");
		var tribunal = $(this).data("tribunal");
		
		// Abrir modal e consultar processo
		$("#nome-cliente-consulta").text(nomeCliente);
		$("#modal-consulta-processo").modal("show");
		
		// Fazer consulta via AJAX
		consultarProcesso(numeroProcesso, tribunal);
	});
	
	function consultarProcesso(numeroProcesso, tribunal){
		$("#conteudo-consulta-processo").html(\'<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Consultando...</span></div><p class="mt-2">Consultando processo...</p></div>\');
		
		// Caminho relativo à raiz do admin (index.php está em admin/)
		var urlConsulta = \'consulta-processual/consultar.php\';
		
		$.ajax({
			url: urlConsulta,
			method: "POST",
			data: {
				numero_processo: numeroProcesso,
				tribunal: tribunal,
				modo_debug: 1
			},
			dataType: "json",
			success: function(response){
				if(response.success){
					exibirResultadosConsulta(response.data || response.dados);
				} else {
					var msgErro = response.message || response.mensagem || "Erro ao consultar processo.";
					$("#conteudo-consulta-processo").html(\'<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> <strong>Erro:</strong> \' + msgErro + \'</div>\');
				}
			},
			error: function(xhr, status, error){
				var mensagem = "Erro ao consultar processo. ";
				var detalhes = "";
				
				// Tentar obter mais detalhes do erro
				if(xhr.responseText){
					try {
						var response = JSON.parse(xhr.responseText);
						if(response.message || response.mensagem){
							mensagem += (response.message || response.mensagem);
						} else {
							mensagem += "Erro HTTP " + xhr.status + ": " + error;
							detalhes = "<br><small class=\"text-muted\">Status: " + xhr.status + " | Erro: " + error + "</small>";
						}
					} catch(e) {
						// Se não for JSON, mostrar o texto da resposta
						if(xhr.responseText.length < 500){
							mensagem += "Resposta do servidor: " + xhr.responseText.substring(0, 200);
						} else {
							mensagem += "Erro HTTP " + xhr.status + ": " + error;
						}
						detalhes = "<br><small class=\"text-muted\">Status: " + xhr.status + " | Erro: " + error + "</small>";
					}
				} else {
					mensagem += "Não foi possível conectar ao servidor. Verifique sua conexão.";
					detalhes = "<br><small class=\"text-muted\">Status: " + xhr.status + " | Erro: " + error + "</small>";
				}
				
				$("#conteudo-consulta-processo").html(
					\'<div class="alert alert-danger">\' +
					\'<i class="fas fa-times-circle"></i> <strong>Erro:</strong> \' + mensagem + detalhes + \'<br>\' +
					\'<small class="text-muted mt-2 d-block">URL: \' + urlConsulta + \' | Processo: \' + numeroProcesso + \' | Tribunal: \' + tribunal + \'</small>\' +
					\'</div>\'
				);
			}
		});
	}
	
	function exibirResultadosConsulta(dados){
		// Usar o mesmo formato da página de consulta processual
		var html = \'<div class="table-responsive" style="width: 100%;">\';
		html += \'<table class="table table-bordered table-striped" style="width: 100%; font-size: 14px;">\';
		
		if(dados.numero_processo){
			html += \'<tr><th width="20%">Número do Processo:</th><td width="80%"><strong>\' + dados.numero_processo + \'</strong></td></tr>\';
		}
		if(dados.tribunal){
			html += \'<tr><th width="20%">Tribunal:</th><td width="80%">\' + dados.tribunal + \'</td></tr>\';
		}
		if(dados.grau){
			html += \'<tr><th width="20%">Grau:</th><td width="80%">\' + dados.grau + \'</td></tr>\';
		}
		if(dados.classe){
			html += \'<tr><th width="20%">Classe:</th><td width="80%">\' + dados.classe + \'</td></tr>\';
		}
		if(dados.assunto){
			html += \'<tr><th width="20%">Assunto:</th><td width="80%">\' + dados.assunto + \'</td></tr>\';
		}
		if(dados.vara){
			html += \'<tr><th width="20%">Órgão Julgador:</th><td width="80%">\' + dados.vara + \'</td></tr>\';
		}
		if(dados.status){
			html += \'<tr><th width="20%">Status:</th><td width="80%"><span class="badge badge-info">\' + dados.status + \'</span></td></tr>\';
		}
		if(dados.data_ajuizamento){
			html += \'<tr><th width="20%">Data de Ajuizamento:</th><td width="80%">\' + dados.data_ajuizamento + \'</td></tr>\';
		}
		if(dados.data_ultima_atualizacao){
			html += \'<tr><th width="20%">Última Atualização:</th><td width="80%">\' + dados.data_ultima_atualizacao + \'</td></tr>\';
		}
		if(dados.sistema){
			html += \'<tr><th width="20%">Sistema:</th><td width="80%">\' + dados.sistema + \'</td></tr>\';
		}
		if(dados.formato){
			html += \'<tr><th width="20%">Formato:</th><td width="80%">\' + dados.formato + \'</td></tr>\';
		}
		if(dados.valor_causa){
			html += \'<tr><th width="20%">Valor da Causa:</th><td width="80%">R$ \' + parseFloat(dados.valor_causa).toLocaleString(\'pt-BR\', {minimumFractionDigits: 2}) + \'</td></tr>\';
		}
		if(dados.partes){
			html += \'<tr><th width="20%">Partes:</th><td width="80%">\' + dados.partes + \'</td></tr>\';
		}
		if(dados.movimentacoes){
			html += \'<tr><th width="20%">Últimas Movimentações:</th><td width="80%"><div class="mt-2">\' + dados.movimentacoes + \'</div></td></tr>\';
		}
		
		html += \'</table>\';
		html += \'</div>\';
		
		$("#conteudo-consulta-processo").html(html);
	}
});
</script>';


if($txtbuscar == ''){


echo '
<!--ÁREA DA PÁGINAÇÃO -->
<nav class="paginacao" aria-label="Page navigation example">
          <ul class="pagination">
            <li class="page-item">
              <a class="btn btn-outline-dark btn-sm mr-1" href="'.$caminho_pag.'pagina=0&itens='.$itens_por_pagina.'" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
              </a>
            </li>';
            
            for($i=0;$i<$num_paginas;$i++){
            $estilo = "";
            if($pagina_pag == $i)
              $estilo = "active";

          echo '
             <li class="page-item"><a class="btn btn-outline-dark btn-sm mr-1 '.$estilo.'" href="'.$caminho_pag.'pagina='.$i.'&itens='.$itens_por_pagina.'">'.($i+1).'</a></li>';
           } 
            
           echo '<li class="page-item">
              <a class="btn btn-outline-dark btn-sm" href="'.$caminho_pag.'pagina='.($num_paginas-1).'&itens='.$itens_por_pagina.'" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
              </a>
            </li>
          </ul>
</nav>




';


}


?>

