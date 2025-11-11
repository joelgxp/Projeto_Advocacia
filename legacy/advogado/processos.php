<?php 
$pagina = 'processos';
$agora = date('Y-m-d');
$data = date('Y-m-d');
?>


<div class="container">
	<div class="row botao-novo">
		<div class="col-md-12">
			<a id="btn-novo" data-toggle="modal" data-target="#modal"></a>
			
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


					<input class="form-control form-control-sm mr-sm-2" type="text" name="txtbuscar" id="txtbuscar" placeholder="Número ou CPF/CNPJ">
					<button class="btn btn-outline-secondary btn-sm my-2 my-sm-0" name="btn-buscar" id="btn-buscar"><i class="fas fa-search"></i></button>
				</form>
			</div>

		</div>


	</div>


	<div id="listar" class="mt-4">

	</div>


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
					$res = $pdo->query("select * from processos where id = '$id_reg'");
					$dados = $res->fetchAll(PDO::FETCH_ASSOC);
					$num_processo = $dados[0]['num_processo'];

					$vara = $dados[0]['vara'];
					$tipo_acao = $dados[0]['tipo_acao'];
					$data_peticao = $dados[0]['data_peticao'];
					$status = $dados[0]['status'];

					if($data == ''){
						$data = date('Y-m-d');
					}
					


					echo 'Edição do Processo';

				} ?>
			</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">


			<form method="post">

				<input id="antigo" type="hidden" class="form-control"  name="antigo" value="<?php echo $num_processo ?>">


				<input id="id" type="hidden" class="form-control"  name="id" value="<?php echo $id_reg ?>">
				
				<div class="row">
					<div class="col-md-4 col-sm-12">
						<div class="form-group">
							<label for="exampleFormControlInput1">Número Processo</label>
							<input id="num_processo" type="text" class="form-control"  name="numero" placeholder="Número do Processo" value="<?php echo $num_processo ?>">
						</div>
					</div>
					<div class="col-md-4 col-sm-12">
						<div class="form-group">
							<label for="exampleFormControlInput1">Vara</label>
							<select class="form-control" id="" name="vara">
								<?php 
								//SE EXISTIR EDIÇÃO DOS DADOS, TRAZER COMO PRIMEIRO REGISTRO O CARGO DO FUNCIONARIO
								if(@$_GET['funcao'] == 'editar'){

									$res_espec = $pdo->query("SELECT * from varas where id = '$vara'");
									$dados_espec = $res_espec->fetchAll(PDO::FETCH_ASSOC);

									for ($i=0; $i < count($dados_espec); $i++) { 
										foreach ($dados_espec[$i] as $key => $value) {
										}

										$id_cargo = $dados_espec[$i]['id'];	
										$nome_cargo = $dados_espec[$i]['nome'];

									}


									echo '<option value="'.$id_cargo.'">'.$nome_cargo.'</option>';
								}



								//TRAZER TODOS OS REGISTROS DE CARGOS
								$res = $pdo->query("SELECT * from varas order by nome asc");
								$dados = $res->fetchAll(PDO::FETCH_ASSOC);

								for ($i=0; $i < count($dados); $i++) { 
									foreach ($dados[$i] as $key => $value) {
									}

									$id = $dados[$i]['id'];	
									$nome = $dados[$i]['nome'];

									if($nome_cargo != $nome){
										echo '<option value="'.$id.'">'.$nome.'</option>';
									}


								}
								?>
							</select>
						</div>
					</div>

					<div class="col-md-4 col-sm-12">
						<div class="form-group">
							<label for="exampleFormControlInput1">Tipo Ação</label>
							<select class="form-control" id="" name="tipo_acao">
								<?php 
								//SE EXISTIR EDIÇÃO DOS DADOS, TRAZER COMO PRIMEIRO REGISTRO O CARGO DO FUNCIONARIO
								if(@$_GET['funcao'] == 'editar'){

									$res_espec = $pdo->query("SELECT * from especialidades where id = '$tipo_acao'");
									$dados_espec = $res_espec->fetchAll(PDO::FETCH_ASSOC);

									for ($i=0; $i < count($dados_espec); $i++) { 
										foreach ($dados_espec[$i] as $key => $value) {
										}

										$id_cargo = $dados_espec[$i]['id'];	
										$nome_cargo = $dados_espec[$i]['nome'];

									}


									echo '<option value="'.$id_cargo.'">'.$nome_cargo.'</option>';
								}



								//TRAZER TODOS OS REGISTROS DE CARGOS
								$res = $pdo->query("SELECT * from especialidades order by nome asc");
								$dados = $res->fetchAll(PDO::FETCH_ASSOC);

								for ($i=0; $i < count($dados); $i++) { 
									foreach ($dados[$i] as $key => $value) {
									}

									$id = $dados[$i]['id'];	
									$nome = $dados[$i]['nome'];

									if($nome_cargo != $nome){
										echo '<option value="'.$id.'">'.$nome.'</option>';
									}


								}
								?>
							</select>
						</div>
					</div>
				</div>	

				<div class="row">
					<div class="col-md-4 col-sm-12">
						<div class="form-group">
							<label for="exampleFormControlInput1">Data Petição</label>
							<input class="form-control form-control-sm mr-sm-2" type="date" name="data_peticao"  value="<?php echo $data_peticao ?>">
						</div>
					</div>


					<div class="col-md-4 col-sm-12">
						<div class="form-group">
							<label for="exampleFormControlInput1">Status Processo</label>
							
							<select class="form-control" id="" name="status">
								<?php 
								
									echo '<option value="'.$status.'">'.$status.'</option>';					

									if($status != 'Aberto'){
										echo '<option value="Aberto">Aberto</option>';
									}

									if($status != 'Andamento'){
										echo '<option value="Andamento">Andamento</option>';
									}


									if($status != 'Arquivado'){
										echo '<option value="Arquivado">Arquivado</option>';
									}

									if($status != 'Concluído'){
										echo '<option value="Concluído">Concluído</option>';
									}


									if($status != 'Cancelado'){
										echo '<option value="Cancelado">Cancelado</option>';
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
					<h5 class="modal-title">Cancelar Processo</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<p>Deseja realmente Cancelar este Processo?</p>
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





<!--CHAMADA DA MODAL CONCLUIR -->
<?php 
if(@$_GET['funcao'] == 'concluir' && @$item_paginado == ''){ 
	$id = $_GET['id'];
	?>

	<div class="modal" id="modal-concluir" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Concluir Processo</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<p>Deseja realmente Finalizar o Processo?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancelar-tarefa">Cancelar</button>
					<form method="post">
						<input type="hidden" id="id"  name="id" value="<?php echo @$id ?>" required>

						<button type="button" id="btn-concluir" name="btn-deletar" class="btn btn-success">Concluir</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	
<?php } ?>



<script>$('#modal-concluir').modal("show");</script>








<!--CHAMADA DA MODAL OBSERVAÇÕES -->
<?php 
if(@$_GET['funcao'] == 'obs' && @$item_paginado == ''){ 
	$id = $_GET['id'];
	?>

	<div class="modal" id="modal-obs" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Observações</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="post">

						<?php 
						$res = $pdo->query("select * from processos where id = '$id'");
						$dados = $res->fetchAll(PDO::FETCH_ASSOC);
						$obs = $dados[0]['obs'];
						 ?>
					
						<div class="form-group">
							<label for="exampleFormControlInput1">Observação</label>
							<textarea maxlength="350" type="text" class="form-control"  name="obs"><?php echo @$obs ?></textarea>
						</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancelar-obs">Cancelar</button>
					
						<input type="hidden" id="id"  name="id" value="<?php echo @$id ?>" required>

						<button type="button" id="btn-obs" name="btn-deletar" class="btn btn-info">Lançar</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	
<?php } ?>



<script>$('#modal-obs').modal("show");</script>






<!--CHAMADA DA MODAL COBRANÇA -->
<?php 
if(@$_GET['funcao'] == 'cobranca' && @$item_paginado == ''){ 
	$id = $_GET['id'];
	?>

	<div class="modal" id="modal-cobranca" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Gerar Cobrança</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>


				<div class="modal-body">
				<form method="post">
					
						<div class="form-group">
							<label for="exampleFormControlInput1">Descrição</label>
							<input type="text" class="form-control"  name="descricao" placeholder="Descrição da Cobrança">
						</div>


						<div class="form-group">
							<label for="exampleFormControlInput1">Valor</label>
							<input type="text" class="form-control"  name="valor" placeholder="Valor">
						</div>
					
					
				</div>


				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancelar-cobranca">Cancelar</button>
					
						<input type="hidden" id="id"  name="id" value="<?php echo @$id ?>" required>

						<button type="button" id="btn-cobranca" name="btn-cobranca" class="btn btn-success">Cobrar</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	
<?php } ?>



<script>$('#modal-cobranca').modal("show");</script>






<!--MASCARAS -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>

<script src="../js/mascaras.js"></script>








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



<!--AJAX PARA BUSCAR OS DADOS PELA TROCA NA DATA-->
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

					
					$('#btn-buscar').click();
					$('#btn-cancelar-excluir').click();

				},
				
			})
		})
	})
</script>





<!--AJAX PARA CONCLUIR TAREFA -->
<script type="text/javascript">
	$(document).ready(function(){
		var pag = "<?=$pagina?>";
		$('#btn-concluir').click(function(event){
			event.preventDefault();
			
			$.ajax({
				url: pag + "/concluir.php",
				method: "post",
				data: $('form').serialize(),
				dataType: "text",
				success: function(mensagem){

					
					$('#btn-buscar').click();
					$('#btn-cancelar-tarefa').click();

				},
				
			})
		})
	})
</script>






<!--AJAX PARA CONCLUIR COBRANÇA -->
<script type="text/javascript">
	$(document).ready(function(){
		var pag = "<?=$pagina?>";
		$('#btn-cobranca').click(function(event){
			event.preventDefault();
			
			$.ajax({
				url: pag + "/cobranca.php",
				method: "post",
				data: $('form').serialize(),
				dataType: "text",
				success: function(mensagem){

					
					$('#btn-buscar').click();
					$('#btn-cancelar-cobranca').click();

				},
				
			})
		})
	})
</script>






<!--AJAX PARA LANÇAR OBS -->
<script type="text/javascript">
	$(document).ready(function(){
		var pag = "<?=$pagina?>";
		$('#btn-obs').click(function(event){
			event.preventDefault();
			
			$.ajax({
				url: pag + "/obs.php",
				method: "post",
				data: $('form').serialize(),
				dataType: "text",
				success: function(mensagem){

					
					$('#btn-buscar').click();
					$('#btn-cancelar-obs').click();

				},
				
			})
		})
	})
</script>
