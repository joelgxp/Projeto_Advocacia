<?php 

require_once("../../conexao.php");

$nome = $_POST['nome'];
$cpf = $_POST['cpf_oculto'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$id = $_POST['id'];
$cpf_antigo = $_POST['cpf_antigo'];
$endereco = @$_POST['endereco'];
$obs = @$_POST['obs'];
$pessoa = $_POST['tipo_pessoa'];

// Dados do processo (opcional)
$numero_processo = isset($_POST['numero_processo']) ? trim($_POST['numero_processo']) : '';
$tribunal_processo = isset($_POST['tribunal_processo']) ? trim($_POST['tribunal_processo']) : '';
$tipo_acao_processo = isset($_POST['tipo_acao_processo']) ? trim($_POST['tipo_acao_processo']) : '';

$cpf_limpo = preg_replace('/[^0-9]/', '', $cpf);
$cpf_cript = md5($cpf_limpo);

if($cpf_antigo != $cpf){

		//VERIFICAR SE O CLIENTE JÁ ESTÁ CADASTRADO
	$res_c = $pdo->query("select * from clientes where cpf = '$cpf'");
	$dados_c = $res_c->fetchAll(PDO::FETCH_ASSOC);
	$linhas = count($dados_c);

	if($linhas != 0){

		echo "Este Registro já está cadastrado!!";
		exit();
	}

}




$res = $pdo->prepare("UPDATE clientes set nome = :nome, telefone = :telefone, email = :email, endereco = :endereco, obs = :obs, tipo_pessoa = :tipo_pessoa where id = :id ");

$res->bindValue(":nome", $nome);
//$res->bindValue(":cpf", $cpf);
$res->bindValue(":telefone", $telefone);
$res->bindValue(":email", $email);
$res->bindValue(":endereco", $endereco);
$res->bindValue(":obs", $obs);
$res->bindValue(":tipo_pessoa", $pessoa);
$res->bindValue(":id", $id);


$res->execute();


	
	$res = $pdo->prepare("UPDATE usuarios set nome = :nome,  usuario = :usuario where cpf = :cpf ");

	$res->bindValue(":nome", $nome);
	$res->bindValue(":usuario", $email);
	
	$res->bindValue(":cpf", $cpf);

	$res->execute();
	
	// Se número do processo foi informado, criar ou atualizar processo
	if(!empty($numero_processo)){
		// Verificar se o processo já existe para este cliente
		$res_proc = $pdo->query("select * from processos where cliente = '$cpf' LIMIT 1");
		$dados_proc = $res_proc->fetchAll(PDO::FETCH_ASSOC);
		
		if(count($dados_proc) > 0){
			// Atualizar processo existente
			$id_processo = $dados_proc[0]['id'];
			$res_proc = $pdo->prepare("UPDATE processos set num_processo = :num_processo, vara = :vara, tipo_acao = :tipo_acao where id = :id");
			$res_proc->bindValue(":num_processo", $numero_processo);
			// O campo 'vara' agora armazena o tribunal (URL da API)
			$res_proc->bindValue(":vara", !empty($tribunal_processo) ? $tribunal_processo : $dados_proc[0]['vara']);
			$res_proc->bindValue(":tipo_acao", !empty($tipo_acao_processo) ? $tipo_acao_processo : $dados_proc[0]['tipo_acao']);
			$res_proc->bindValue(":id", $id_processo);
			$res_proc->execute();
		} else {
			// Criar novo processo
			$cpf_admin = $_SESSION['cpf_usuario'] ?? '000.000.000-00';
			$tribunal_processo = !empty($tribunal_processo) ? $tribunal_processo : '';
			$tipo_acao_processo = !empty($tipo_acao_processo) ? $tipo_acao_processo : '1';
			
			$res_proc = $pdo->prepare("INSERT into processos (num_processo, vara, tipo_acao, advogado, cliente, data_abertura, status, tipo_pessoa) values (:num_processo, :vara, :tipo_acao, :advogado, :cliente, curDate(), :status, :tipo_pessoa) ");
			
			$res_proc->bindValue(":num_processo", $numero_processo);
			$res_proc->bindValue(":vara", $tribunal_processo); // Armazena o tribunal (URL da API)
			$res_proc->bindValue(":tipo_acao", $tipo_acao_processo);
			$res_proc->bindValue(":advogado", $cpf_admin);
			$res_proc->bindValue(":cliente", $cpf);
			$res_proc->bindValue(":status", 'Aberto');
			$res_proc->bindValue(":tipo_pessoa", $pessoa);
			
			$res_proc->execute();
		}
	}

echo "Editado com Sucesso!!";




?>

