<?php 

require_once("../../conexao.php");

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$endereco = $_POST['endereco'];
$obs = $_POST['obs'];
$pessoa = $_POST['tipo_pessoa'];

// Dados do processo (opcional)
$numero_processo = isset($_POST['numero_processo']) ? trim($_POST['numero_processo']) : '';
$tribunal_processo = isset($_POST['tribunal_processo']) ? trim($_POST['tribunal_processo']) : '';
$tipo_acao_processo = isset($_POST['tipo_acao_processo']) ? trim($_POST['tipo_acao_processo']) : '';

if($cpf == ''){
	$cpf = $_POST['cnpj'];
}


$cpf_limpo = preg_replace('/[^0-9]/', '', $cpf);
$cpf_cript = md5($cpf_limpo);

//VERIFICAR SE O REGISTRO JÁ ESTÁ CADASTRADO
$res_c = $pdo->query("select * from clientes where cpf = '$cpf'");
$dados_c = $res_c->fetchAll(PDO::FETCH_ASSOC);
$linhas = count($dados_c);
if($linhas == 0){
	$res = $pdo->prepare("INSERT into clientes (nome, cpf, telefone, email, endereco, data, obs, tipo_pessoa) values (:nome, :cpf, :telefone, :email, :endereco, curDate(), :obs, :tipo_pessoa) ");

	$res->bindValue(":nome", $nome);
	$res->bindValue(":cpf", $cpf);
	$res->bindValue(":telefone", $telefone);
	$res->bindValue(":email", $email);
	$res->bindValue(":endereco", $endereco);
	
	$res->bindValue(":obs", $obs);
	$res->bindValue(":tipo_pessoa", $pessoa);

	$res->execute();


	

	$res = $pdo->prepare("INSERT into usuarios (nome, cpf, usuario, senha, senha_original, nivel) values (:nome, :cpf, :usuario, :senha, :senha_original, :nivel) ");

	$res->bindValue(":nome", $nome);
	$res->bindValue(":cpf", $cpf);
	$res->bindValue(":usuario", $email);
	$res->bindValue(":senha", $cpf_cript);
	$res->bindValue(":senha_original", $cpf_limpo);
	$res->bindValue(":nivel", 'Cliente');

	$res->execute();
	
	// Se número do processo foi informado, criar processo automaticamente
	if(!empty($numero_processo)){
		// Verificar se o processo já existe
		$res_proc = $pdo->query("select * from processos where num_processo = '$numero_processo'");
		$dados_proc = $res_proc->fetchAll(PDO::FETCH_ASSOC);
		$linhas_proc = count($dados_proc);
		
		if($linhas_proc == 0){
			// Buscar CPF do admin para vincular o processo
			$cpf_admin = $_SESSION['cpf_usuario'] ?? '000.000.000-00';
			
			// O campo 'vara' agora armazena o tribunal (URL da API)
			// Se não informou tribunal, usar valor padrão
			$tribunal_processo = !empty($tribunal_processo) ? $tribunal_processo : '';
			$tipo_acao_processo = !empty($tipo_acao_processo) ? $tipo_acao_processo : '1'; // Tipo padrão
			
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

	echo "Cadastrado com Sucesso!!";

}else{
	echo "Este Registro já está cadastrado!!";
}

?>

