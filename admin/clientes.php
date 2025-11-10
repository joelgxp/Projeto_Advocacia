<?php 
require_once("../middleware.php");
requireAdmin();

$pagina = 'clientes'; 
?>

<style>
/* Estilos para a modal de consulta processual */
#modal-consulta-processo .modal-body {
	max-height: 70vh;
	overflow-y: auto;
	padding: 20px;
}

#modal-consulta-processo .card {
	border: none;
	box-shadow: 0 2px 4px rgba(0,0,0,0.1);
	margin-bottom: 15px;
}

#modal-consulta-processo .card-header {
	font-weight: 600;
	padding: 10px 15px;
}

#modal-consulta-processo .card-body {
	padding: 15px;
}

#modal-consulta-processo .table th {
	font-weight: 600;
	vertical-align: middle;
}

#modal-consulta-processo .badge {
	font-size: 0.9em;
	padding: 5px 10px;
}

.btn-consulta-processo {
	margin-left: 8px;
	text-decoration: none;
}

.btn-consulta-processo:hover {
	opacity: 0.8;
}
</style>



<div class="row botao-novo">
	<div class="col-md-12">
		
		<a id="btn-novo" data-toggle="modal" data-target="#modal"></a>
		<a href="index.php?acao=<?php echo $pagina ?>&funcao=novo"  type="button" class="btn btn-info">Novo Cliente</a>

	</div>
</div>



<div class="row mt-4">
	<div class="col-md-6 col-sm-12">
		<div class="float-left">
			<form method="post">
				<select id="itens-pagina" onChange="submit();" class="form-control-sm" id="exampleFormControlSelect1" name="itens-pagina">

					<?php 

					if(isset($_POST['itens-pagina'])){
						$item_paginado = $_POST['itens-pagina'];
					}elseif(isset($_GET['itens'])){
						$item_paginado = $_GET['itens'];
					}

					?>

					<option value="<?php echo @$item_paginado ?>"><?php echo @$item_paginado ?> Registros</option>

					<?php if(@$item_paginado != $opcao1){ ?> 
						<option value="<?php echo $opcao1 ?>"><?php echo $opcao1 ?> Registros</option>
					<?php } ?>

					<?php if(@$item_paginado != $opcao2){ ?> 
						<option value="<?php echo $opcao2 ?>"><?php echo $opcao2 ?> Registros</option>
					<?php } ?>

					<?php if(@$item_paginado != $opcao3){ ?> 
						<option value="<?php echo $opcao3 ?>"><?php echo $opcao3 ?> Registros</option>
					<?php } ?>

					

				</select>
			</form>
		</div>

	</div>


	<?php 

	//DEFINIR O NUMERO DE ITENS POR PÁGINA
	if(isset($_POST['itens-pagina'])){
		$itens_por_pagina = $_POST['itens-pagina'];
		@$_GET['pagina'] = 0;
	}elseif(isset($_GET['itens'])){
		$itens_por_pagina = $_GET['itens'];
	}
	else{
		$itens_por_pagina = $opcao1;

	}

	?>
	

	<div class="col-md-6 col-sm-12">

		<div class="float-right mr-4">
			<form id="frm" class="form-inline my-2 my-lg-0" method="post">

				<input type="hidden" id="pag"  name="pag" value="<?php echo @$_GET['pagina'] ?>">

				<input type="hidden" id="itens"  name="itens" value="<?php echo @$itens_por_pagina; ?>">

				<input class="form-control form-control-sm mr-sm-2" type="search" placeholder="Nome ou CPF" aria-label="Search" name="txtbuscar" id="txtbuscar">
				<button class="btn btn-outline-secondary btn-sm my-2 my-sm-0" name="btn-buscar" id="btn-buscar"><i class="fas fa-search"></i></button>
			</form>
		</div>
		
	</div>


</div>


	<div id="listar" class="mt-4">

	</div>




	<!-- Modal -->
	<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel"><?php if(@$_GET['funcao'] == 'editar'){

					$nome_botao = 'Editar';
					$id_reg = $_GET['id'];

					//BUSCAR DADOS DO REGISTRO A SER EDITADO
					$res = $pdo->query("select * from clientes where id = '$id_reg'");
					$dados = $res->fetchAll(PDO::FETCH_ASSOC);
					$nome = $dados[0]['nome'];

					$cpf = $dados[0]['cpf'];
					$telefone = $dados[0]['telefone'];
					$email = $dados[0]['email'];
					$endereco = $dados[0]['endereco'];
					$obs = $dados[0]['obs'];
					$pessoa = $dados[0]['tipo_pessoa'];
					
					// Buscar processo do cliente se existir
					$numero_processo = '';
					$tipo_tribunal_processo = '';
					$tribunal_processo = '';
					$tipo_acao_processo = '';
					$res_proc = $pdo->query("SELECT * from processos where cliente = '$cpf' LIMIT 1");
					$dados_proc = $res_proc->fetchAll(PDO::FETCH_ASSOC);
					if(count($dados_proc) > 0){
						$numero_processo = $dados_proc[0]['num_processo'] ?? '';
						// O campo 'vara' agora armazena o tribunal (URL da API)
						$tribunal_processo = $dados_proc[0]['vara'] ?? '';
						$tipo_acao_processo = $dados_proc[0]['tipo_acao'] ?? '';
						
						// Tentar identificar o tipo de tribunal baseado no tribunal salvo
						if(!empty($tribunal_processo)){
							if(strpos($tribunal_processo, 'tst') !== false || strpos($tribunal_processo, 'tse') !== false || strpos($tribunal_processo, 'stj') !== false || strpos($tribunal_processo, 'stm') !== false){
								$tipo_tribunal_processo = 'superiores';
							} elseif(strpos($tribunal_processo, 'trf') !== false){
								$tipo_tribunal_processo = 'federal';
							} elseif(strpos($tribunal_processo, 'tj') !== false){
								$tipo_tribunal_processo = 'estadual';
							} elseif(strpos($tribunal_processo, 'trt') !== false){
								$tipo_tribunal_processo = 'trabalho';
							} elseif(strpos($tribunal_processo, 'tre') !== false){
								$tipo_tribunal_processo = 'eleitoral';
							} elseif(strpos($tribunal_processo, 'tjm') !== false){
								$tipo_tribunal_processo = 'militar';
							}
						}
					}

					echo 'Edição de Clientes';
				}else{
					$pessoa = 'Pessoa Física';
					$nome_botao = 'Salvar';
					echo 'Cadastro de Clientes';
				} ?>
			</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">


			<form method="post">
				<div class="row">
					<div class="col-md-4 col-sm-12">
						<div class="form-group">

							<input type="hidden" id="id"  name="id" value="<?php echo @$id_reg ?>" required>

							<input type="hidden" id="cpf_antigo"  name="cpf_antigo" value="<?php echo @$cpf ?>" required>

							<label for="exampleFormControlInput1">Nome</label>
							<input type="text" class="form-control" id="nome" placeholder="Insira o Nome" name="nome" value="<?php echo @$nome ?>" required>
						</div>
					</div>



					<div class="col-md-4 col-sm-12">
						<div class="form-group">
							<label for="exampleFormControlInput1">Física / Jurídica</label>
							<select class="form-control" id="pessoa" name="tipo_pessoa">
								<?php if(@$_GET['funcao'] == 'editar'){ ?>
									<option value="<?php echo @$pessoa ?>"><?php echo @$pessoa ?></option>
									<?php  

									if($pessoa != 'Pessoa Física'){
										echo '<option value="Pessoa Física">Pessoa Física</option>';
									}

									if($pessoa != 'Pessoa Jurídica'){
										echo '<option value="Pessoa Jurídica">Pessoa Jurídica</option>';
									}
								}else{
									echo '<option value="Pessoa Física">Pessoa Física</option>';
									echo '<option value="Pessoa Jurídica">Pessoa Jurídica</option>';
								}
								?>	

							</select>
						</div>
					</div>

					<?php if(@$pessoa == 'Pessoa Física'){ ?>
						<div class="col-md-4 col-sm-12" id="divcpf">
							<div class="form-group">
								<label for="exampleFormControlInput1">CPF</label>
								<?php if(@$_GET['funcao'] == 'editar'){ ?>

									<input type="hidden" class="form-control" name="cpf_oculto" value="<?php echo @$cpf ?>">

									<input type="text" class="form-control" id="cpf" name="cpf" placeholder="Insira o CPF" disabled value="<?php echo @$cpf ?>">
								<?php }else{ ?>
									<input type="text" class="form-control" id="cpf" name="cpf" placeholder="Insira o CPF" required value="<?php echo @$cpf ?>">
								<?php } ?>
							</div>

						</div>
					<?php } ?>

					<?php if(@$pessoa == 'Pessoa Jurídica'){ ?>
						<div class="col-md-4 col-sm-12" id="divcnpj">
							<div class="form-group">
								<label for="exampleFormControlInput1">CNPJ</label>
								<?php if(@$_GET['funcao'] == 'editar'){ ?>

									<input type="hidden" class="form-control" name="cpf_oculto" value="<?php echo @$cpf ?>">

									<input type="text" class="form-control" id="cnpj" name="cnpj" placeholder="Insira o CNPJ" disabled value="<?php echo @$cpf ?>">
								<?php }else{ ?>
									<input type="text" class="form-control" id="cnpj" name="cnpj" placeholder="Insira o CNPJ" required value="<?php echo @$cpf ?>">
								<?php } ?>
							</div>

						</div>
					<?php } ?>



					<?php if(@$_GET['funcao'] != 'editar'){ ?>
					<div class="col-md-4 col-sm-12" id="divcnpj2" style="display: none;">
						<div class="form-group">
							<label for="exampleFormControlInput1">CNPJ</label>
							
							<input type="text" class="form-control" id="cnpj" name="cnpj" placeholder="Insira o CNPJ" required value="<?php echo @$cpf ?>">

						</div>

					</div>
					<?php } ?>

					

				</div>

				<div class="row">


					<div class="col-md-3 col-sm-12">
						<div class="form-group">
							<label for="exampleFormControlInput1">Telefone</label>
							<input type="text" class="form-control" id="telefone" name="telefone" placeholder="Insira o Telefone" value="<?php echo @$telefone ?>">
						</div>
					</div>

					<div class="col-md-3 col-sm-12">
						<div class="form-group">
							<label for="exampleFormControlInput1">Email</label>

							<input type="text" class="form-control" id="email" name="email" placeholder="Insira o Email" required value="<?php echo @$email ?>">
							
						</div>


					</div>


					<div class="col-md-6 col-sm-12">
						<div class="form-group">
							<label for="exampleFormControlInput1">Endereço</label>
							<input type="text" class="form-control"  name="endereco" placeholder="Insira o Endereço" value="<?php echo @$endereco ?>">
						</div>
					</div>

					


				</div>




				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<label for="exampleFormControlInput1">Observações</label>
							<textarea class="form-control"  name="obs" maxlength="350"><?php echo @$obs ?></textarea>
						</div>
					</div>
				</div>

				<!-- Seção de Processo (Opcional) -->
				<div class="row mt-3">
					<div class="col-md-12">
						<hr>
						<h6 class="text-muted">Processo (Opcional)</h6>
						<p class="text-muted small">Se o cliente já possui um processo, informe os dados abaixo</p>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4 col-sm-12">
						<div class="form-group">
							<label for="numero_processo">Número do Processo</label>
							<input type="text" class="form-control" id="numero_processo" name="numero_processo" placeholder="Ex: 0000123-45.2023.8.26.0100" value="<?php echo @$numero_processo ?>">
							<small class="form-text text-muted">Opcional - Se informado, um processo será criado automaticamente</small>
						</div>
					</div>

					<div class="col-md-4 col-sm-12">
						<div class="form-group">
							<label for="tipo_tribunal_processo">Tipo de Tribunal <span id="req-tipo-tribunal" class="text-danger" style="display:none;">*</span></label>
							<select class="form-control" id="tipo_tribunal_processo" name="tipo_tribunal_processo">
								<option value="">Selecione o tipo...</option>
								<option value="superiores" <?php echo (isset($tipo_tribunal_processo) && $tipo_tribunal_processo == 'superiores') ? 'selected' : ''; ?>>Tribunais Superiores</option>
								<option value="federal" <?php echo (isset($tipo_tribunal_processo) && $tipo_tribunal_processo == 'federal') ? 'selected' : ''; ?>>Justiça Federal</option>
								<option value="estadual" <?php echo (isset($tipo_tribunal_processo) && $tipo_tribunal_processo == 'estadual') ? 'selected' : ''; ?>>Justiça Estadual</option>
								<option value="trabalho" <?php echo (isset($tipo_tribunal_processo) && $tipo_tribunal_processo == 'trabalho') ? 'selected' : ''; ?>>Justiça do Trabalho</option>
								<option value="eleitoral" <?php echo (isset($tipo_tribunal_processo) && $tipo_tribunal_processo == 'eleitoral') ? 'selected' : ''; ?>>Justiça Eleitoral</option>
								<option value="militar" <?php echo (isset($tipo_tribunal_processo) && $tipo_tribunal_processo == 'militar') ? 'selected' : ''; ?>>Justiça Militar</option>
							</select>
							<small class="form-text text-danger" id="msg-tipo-tribunal" style="display:none;">Obrigatório quando número do processo é informado</small>
						</div>
					</div>

					<div class="col-md-4 col-sm-12">
						<div class="form-group">
							<label for="tribunal_processo">Tribunal <span id="req-tribunal" class="text-danger" style="display:none;">*</span></label>
							<select class="form-control" id="tribunal_processo" name="tribunal_processo" <?php echo (!isset($tribunal_processo) || empty($tribunal_processo)) ? 'disabled' : ''; ?>>
								<option value=""><?php echo (!isset($tipo_tribunal_processo) || empty($tipo_tribunal_processo)) ? 'Primeiro selecione o tipo de tribunal' : 'Selecione o tribunal...'; ?></option>
							</select>
							<small class="form-text text-danger" id="msg-tribunal" style="display:none;">Obrigatório quando número do processo é informado</small>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4 col-sm-12">
						<div class="form-group">
							<label for="tipo_acao_processo">Tipo de Ação</label>
							<select class="form-control" id="tipo_acao_processo" name="tipo_acao_processo">
								<option value="">Selecione o tipo...</option>
								<?php 
								$res = $pdo->query("SELECT * from especialidades order by nome asc");
								$dados = $res->fetchAll(PDO::FETCH_ASSOC);

								for ($i=0; $i < count($dados); $i++) { 
									foreach ($dados[$i] as $key => $value) {
									}

									$id = $dados[$i]['id'];	
									$nome = $dados[$i]['nome'];
									
									$selected = (isset($tipo_acao_processo) && $tipo_acao_processo == $id) ? 'selected' : '';
									echo '<option value="'.$id.'" '.$selected.'>'.$nome.'</option>';
								}
								?>
							</select>
						</div>
					</div>
				</div>




				<div id="mensagem" class="">

				</div>

			</div>
			<div class="modal-footer">
				<button id="btn-fechar" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>

				<button name="<?php echo $nome_botao ?>" id="<?php echo $nome_botao ?>" class="btn btn-primary"><?php echo $nome_botao ?></button>

			</div>
		</form>
	</div>
</div>
</div>



<!--CHAMADA DA MODAL NOVO -->
<?php 
if(@$_GET['funcao'] == 'novo' && @$item_paginado == ''){ 
	
	?>
	<script>$('#btn-novo').click();</script>
<?php } ?>


<!--CHAMADA DA MODAL EDITAR -->
<?php 
if(@$_GET['funcao'] == 'editar' && @$item_paginado == ''){ 
	
	?>
	<script>$('#btn-novo').click();</script>
<?php } ?>



<!--CHAMADA DA MODAL DELETAR -->
<?php 
if(@$_GET['funcao'] == 'excluir' && @$item_paginado == ''){ 
	$id = $_GET['id'];
	?>

	<div class="modal" id="modal-deletar" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Excluir Registro</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<p>Deseja realmente Excluir este Registro?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancelar-excluir">Cancelar</button>
					<form method="post">
						<input type="hidden" id="id"  name="id" value="<?php echo @$id ?>" required>

						<button type="button" id="btn-deletar" name="btn-deletar" class="btn btn-danger">Excluir</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	
<?php } ?>



<script>$('#modal-deletar').modal("show");</script>





<!--MASCARAS -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>

<script src="../js/mascaras.js"></script>

<script>
// Máscara para número de processo
$(document).ready(function(){
	$('#numero_processo').mask('0000000-00.0000.0.00.0000', {
		reverse: false,
		placeholder: '0000000-00.0000.0.00.0000'
	});

	// Mapeamento de tribunais (mesmo da consulta processual)
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

	// Popula o select de tribunal baseado no tipo selecionado
	$('#tipo_tribunal_processo').change(function(){
		var tipo = $(this).val();
		var selectTribunal = $('#tribunal_processo');
		selectTribunal.empty();
		
		if(tipo && tribunais[tipo]){
			selectTribunal.append('<option value="">Selecione o tribunal...</option>');
			$.each(tribunais[tipo], function(index, tribunal){
				var selected = '';
				<?php if(isset($tribunal_processo) && !empty($tribunal_processo)): ?>
				if('<?php echo $tribunal_processo; ?>' == tribunal.url){
					selected = 'selected';
				}
				<?php endif; ?>
				selectTribunal.append('<option value="' + tribunal.url + '" ' + selected + '>' + tribunal.nome + '</option>');
			});
			selectTribunal.prop('disabled', false);
		} else {
			selectTribunal.append('<option value="">Primeiro selecione o tipo de tribunal</option>');
			selectTribunal.prop('disabled', true);
		}
	});

	// Se já tiver tipo e tribunal selecionados (modo edição), popular o select
	<?php if(isset($tipo_tribunal_processo) && !empty($tipo_tribunal_processo)): ?>
	$('#tipo_tribunal_processo').trigger('change');
	<?php endif; ?>
	
	// Validação: Se número do processo for informado, tribunal é obrigatório
	function validarProcesso(){
		var numeroProcesso = $('#numero_processo').val().trim();
		var tipoTribunal = $('#tipo_tribunal_processo').val();
		var tribunal = $('#tribunal_processo').val();
		
		if(numeroProcesso !== ''){
			// Se tem número de processo, tribunal é obrigatório
			$('#req-tipo-tribunal, #req-tribunal').show();
			
			if(!tipoTribunal){
				$('#tipo_tribunal_processo').addClass('is-invalid');
				$('#msg-tipo-tribunal').show();
				return false;
			} else {
				$('#tipo_tribunal_processo').removeClass('is-invalid');
				$('#msg-tipo-tribunal').hide();
			}
			
			if(!tribunal){
				$('#tribunal_processo').addClass('is-invalid');
				$('#msg-tribunal').show();
				return false;
			} else {
				$('#tribunal_processo').removeClass('is-invalid');
				$('#msg-tribunal').hide();
			}
		} else {
			// Se não tem número de processo, tribunal é opcional
			$('#req-tipo-tribunal, #req-tribunal').hide();
			$('#tipo_tribunal_processo, #tribunal_processo').removeClass('is-invalid');
			$('#msg-tipo-tribunal, #msg-tribunal').hide();
		}
		
		return true;
	}
	
	// Validar quando número do processo mudar
	$('#numero_processo').on('blur change', function(){
		validarProcesso();
	});
	
	// Validar quando tipo de tribunal mudar
	$('#tipo_tribunal_processo').on('change', function(){
		validarProcesso();
	});
	
	// Validar quando tribunal mudar
	$('#tribunal_processo').on('change', function(){
		validarProcesso();
	});
	
	// Validar antes de submeter o formulário
	$('form').on('submit', function(e){
		if(!validarProcesso()){
			e.preventDefault();
			alert('Por favor, preencha o tipo de tribunal e o tribunal quando informar o número do processo.');
			return false;
		}
	});
});
</script>




<!--AJAX PARA INSERÇÃO DOS DADOS -->
<script type="text/javascript">
	$(document).ready(function(){
		var pag = "<?=$pagina?>";
		$('#Salvar').click(function(event){
			event.preventDefault();
			
			$.ajax({
				url: pag + "/inserir.php",
				method: "post",
				data: $('form').serialize(),
				dataType: "text",
				success: function(mensagem){

					$('#mensagem').removeClass()

					if(mensagem == 'Cadastrado com Sucesso!!'){
						
						$('#mensagem').addClass('mensagem-sucesso')

						$('#nome').val('')
						$('#cpf').val('')
						$('#telefone').val('')
						
						$('#email').val('')

						$('#txtbuscar').val('')
						$('#btn-buscar').click();

						//$('#btn-fechar').click();




					}else{
						
						$('#mensagem').addClass('mensagem-erro')
					}
					
					$('#mensagem').text(mensagem)

				},
				
			})
		})
	})
</script>




<!--AJAX PARA BUSCAR OS DADOS -->
<script type="text/javascript">
	$(document).ready(function(){

		var pag = "<?=$pagina?>";
		$('#btn-buscar').click(function(event){
			event.preventDefault();	
			
			$.ajax({
				url: pag + "/listar.php",
				method: "post",
				data: $('form').serialize(),
				dataType: "html",
				success: function(result){
					$('#listar').html(result)
					
				},
				

			})

		})

		
	})
</script>






<!--AJAX PARA LISTAR OS DADOS -->
<script type="text/javascript">
	$(document).ready(function(){
		
		var pag = "<?=$pagina?>";

		$.ajax({
			url: pag + "/listar.php",
			method: "post",
			data: $('#frm').serialize(),
			dataType: "html",
			success: function(result){
				$('#listar').html(result)

			},

			
		})
	})
</script>



<!--AJAX PARA BUSCAR OS DADOS PELA TXT -->
<script type="text/javascript">
	$('#txtbuscar').keyup(function(){
		$('#btn-buscar').click();
	})
</script>





<!--AJAX PARA EDIÇÃO DOS DADOS -->
<script type="text/javascript">
	$(document).ready(function(){
		var pag = "<?=$pagina?>";
		$('#Editar').click(function(event){
			event.preventDefault();
			
			$.ajax({
				url: pag + "/editar.php",
				method: "post",
				data: $('form').serialize(),
				dataType: "text",
				success: function(mensagem){

					$('#mensagem').removeClass()

					if(mensagem == 'Editado com Sucesso!!'){
						
						$('#mensagem').addClass('mensagem-sucesso')

						$('#nome').val('')
						$('#cpf').val('')
						$('#telefone').val('')
						
						$('#email').val('')

						$('#txtbuscar').val('')
						$('#btn-buscar').click();

						$('#btn-fechar').click();




					}else{
						
						$('#mensagem').addClass('mensagem-erro')
					}
					
					$('#mensagem').text(mensagem)

				},
				
			})
		})
	})
</script>




<!--AJAX PARA EXCLUSÃO DOS DADOS -->
<script type="text/javascript">
	$(document).ready(function(){
		var pag = "<?=$pagina?>";
		$('#btn-deletar').click(function(event){
			event.preventDefault();
			
			$.ajax({
				url: pag + "/excluir.php",
				method: "post",
				data: $('form').serialize(),
				dataType: "text",
				success: function(mensagem){

					$('#txtbuscar').val('')
					$('#btn-buscar').click();
					$('#btn-cancelar-excluir').click();

				},
				
			})
		})
	})
</script>



<!--AJAX PARA OCULTAR DIV QUANDO TROCADO O SELECT -->
<script type="text/javascript">
	$('#pessoa').change(function(){
		var select = document.getElementById('pessoa');
		var value = select.options[select.selectedIndex].value;
		
		if(value == 'Pessoa Jurídica'){
			$("#divcpf").hide();
			document.getElementById("divcnpj2").style.display = 'block';
		}else{
			$("#divcpf").show();
			document.getElementById("divcnpj2").style.display = 'none';
		}


		

	})
</script>

