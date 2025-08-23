<?php 

require_once("config.php");
@session_start();

try {
	$pdo = new PDO("mysql:host=$host;port=$porta;dbname=$banco;charset=utf8mb4", "$usuario", "$senha", [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES => false,
	]);
} catch (Exception $e) {
	// Em vez de mostrar o erro, vamos definir $pdo como null e continuar
	$pdo = null;
	// Log do erro (em produção, não mostrar detalhes)
	error_log('Erro de conexão com banco: ' . $e->getMessage());
}


 ?>