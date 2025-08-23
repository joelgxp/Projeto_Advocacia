<?php 

require_once("conexao.php");
@session_cache_expire(120);
@session_start();

// Verifica se a conexão com o banco está funcionando
if ($pdo === null) {
    echo "<script language='javascript'>
        alert('Sistema temporariamente indisponível. Tente novamente mais tarde.');
        window.location='index.php';
    </script>";
    exit;
}

$usuario = $_POST['usuario'];
$senha = $_POST['senha'];
$senha_cript = md5($_POST['senha']);

if(empty($usuario) || empty($senha)){
	echo "<script language='javascript'>window.location='index.php'; </script>";
}else{
	try {
		$res = $pdo->prepare("SELECT * FROM usuarios where usuario = :usuario and senha = :senha");
			$res->bindValue(":usuario", $usuario);
		$res->bindValue(":senha", $senha_cript);
		$res->execute();

		$dados = $res->fetchAll(PDO::FETCH_ASSOC);
		$linhas = count($dados);

		if($linhas > 0){
		
		$_SESSION['nome_usuario'] = $dados[0]['nome'];
		$_SESSION['email_usuario'] = $dados[0]['usuario'];
		$_SESSION['nivel_usuario'] = $dados[0]['nivel'];
		$_SESSION['cpf_usuario'] = $dados[0]['cpf'];

		if($_SESSION['nivel_usuario'] == 'admin'){
			echo "<script language='javascript'>window.location='admin/'; </script>";
		}

		if($_SESSION['nivel_usuario'] == 'Advogado'){
			echo "<script language='javascript'>window.location='advogado/'; </script>";
		}

		if($_SESSION['nivel_usuario'] == 'Cliente'){
			echo "<script language='javascript'>window.location='cliente/'; </script>";
		}


		if($_SESSION['nivel_usuario'] == 'Recepcionista'){
			echo "<script language='javascript'>window.location='recepcao/'; </script>";
		}

		if($_SESSION['nivel_usuario'] == 'Tesoureiro'){
			echo "<script language='javascript'>window.location='recepcao/'; </script>";
		}


		}else{
			echo "<script language='javascript'>window.alert('Dados Incorretos!'); </script>";
			echo "<script language='javascript'>window.location='index.php'; </script>";
		}
		
	} catch (Exception $e) {
		error_log('Erro na autenticação: ' . $e->getMessage());
		echo "<script language='javascript'>
			alert('Erro interno do sistema. Tente novamente mais tarde.');
			window.location='index.php';
		</script>";
	}
}

?>