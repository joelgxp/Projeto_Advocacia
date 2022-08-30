<?php 

require_once("../../conexao.php");

$descricao = $_POST['descricao'];
$data = $_POST['data'];
$hora = $_POST['hora'];
$local = $_POST['local'];
$processo = $_POST['numero'];


$res_proc = $pdo->query("SELECT * from processos where num_processo = '$processo'");
$dados_proc = $res_proc->fetchAll(PDO::FETCH_ASSOC);
$cpf_cliente = $dados_proc[0]['cliente'];
$advogado = $dados_proc[0]['advogado'];

//VERIFICAR SE O REGISTRO JÁ ESTÁ CADASTRADO
$res_c = $pdo->query("SELECT * from audiencias where data = '$data' and hora = '$hora' and advogado = '$advogado' and processo = '$processo'");
$dados_c = $res_c->fetchAll(PDO::FETCH_ASSOC);
$linhas = count($dados_c);
if($linhas == 0){
	$res = $pdo->prepare("INSERT into audiencias (processo, descricao, data, hora, local, advogado, cliente) values (:processo, :descricao, :data, :hora, :local, :advogado, :cliente)");

	$res->bindValue(":processo", $processo);
	$res->bindValue(":descricao", $descricao);
	$res->bindValue(":data", $data);
	$res->bindValue(":hora", $hora);
	$res->bindValue(":local", $local);
	$res->bindValue(":advogado", $advogado);
	$res->bindValue(":cliente", $cpf_cliente);
	

	$res->execute();



	//TRAZER O TOTAL DE AUDIENCIAS JÁ REALIZADAS NO PROCESSO
	$res_a = $pdo->query("SELECT * from processos where num_processo = '$processo'");
	$dados_a = $res_a->fetchAll(PDO::FETCH_ASSOC);
	$total_aud = $dados_a[0]['audiencias'];
	$total_aud = $total_aud + 1;
	
	$res_p = $pdo->prepare("UPDATE processos SET data_audiencia = :data_audiencia, hora_audiencia = :hora_audiencia, audiencias = :audiencias WHERE num_processo = '$processo'");

	$res_p->bindValue(":data_audiencia", $data);
	$res_p->bindValue(":hora_audiencia", $hora);
	$res_p->bindValue(":audiencias", $total_aud);
	

	$res_p->execute();




	echo "Cadastrado com Sucesso!!";

}else{
	echo "Esta Audiência já está cadastrada!!";
}

?>