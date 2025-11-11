<?php 

date_default_timezone_set("America/Sao_Paulo");
$email_site = "joelvieirasouza@gmail.com";

//BANCO DE DADOS LOCAL (DESENVOLVIMENTO)
// ⚠️ IMPORTANTE: Ajuste estas credenciais conforme sua instalação local
// - XAMPP: senha geralmente é vazia ('')
// - WAMP: senha pode ser vazia ('') ou 'root'
// - MySQL standalone: use a senha que você configurou
$host = 'localhost';
$porta = 3306;
$usuario = 'root';
$senha = '';  // ⚠️ ALTERE: Ajuste para sua senha MySQL local
$banco = 'advocacia';

//BANCO DE DADOS PRODUÇÃO (HOSTGATOR)
// $host = 'localhost';
// $porta = 3306;
// $usuario = 'hotel631_joeladv';
// $senha = '@{]kdP^iT?M1';
// $banco = 'hotel631_advocacia';



//VALORES PARA A COMBOBOX DE PAGINAÇÃO
$opcao1 = 10;
$opcao2 = 20;
$opcao3 = 50;

// API Key do CNJ (DataJud) - Chave Pública
// ⚠️ IMPORTANTE: Esta chave pode ser alterada pelo CNJ a qualquer momento
// Verifique sempre a chave atualizada na documentação oficial do CNJ
// Formato: Authorization: APIKey [Chave Pública]
$api_cnj_key = 'cDZHYzlZa0JadVREZDJCendQbXY6SkJlTzNjLV9TRENyQk1RdnFKZGRQdw==';

 ?>