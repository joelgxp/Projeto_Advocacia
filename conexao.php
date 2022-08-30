<?php 

require_once("config.php");
@session_start();

try {
	$pdo = new PDO("mysql:dbname=$banco;host=$host", "$usuario", "$senha");
} catch (Exception $e) {
	echo 'Erro ao conectar com o banco!!' .$e;
}


 ?>