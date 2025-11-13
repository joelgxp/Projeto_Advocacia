<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<title>404 Página Não Encontrada</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 0;
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			display: flex;
			justify-content: center;
			align-items: center;
			min-height: 100vh;
		}
		.error-container {
			background: #fff;
			border-radius: 10px;
			padding: 40px;
			text-align: center;
			box-shadow: 0 10px 40px rgba(0,0,0,0.2);
			max-width: 500px;
		}
		h1 {
			font-size: 72px;
			margin: 0;
			color: #667eea;
		}
		h2 {
			color: #333;
			margin: 20px 0;
		}
		p {
			color: #666;
			margin: 20px 0;
		}
		a {
			display: inline-block;
			padding: 12px 30px;
			background: #667eea;
			color: #fff;
			text-decoration: none;
			border-radius: 5px;
			margin-top: 20px;
		}
		a:hover {
			background: #5568d3;
		}
	</style>
</head>
<body>
	<div class="error-container">
		<h1>404</h1>
		<h2>Página Não Encontrada</h2>
		<p>A página que você está procurando não existe.</p>
		<a href="<?php echo base_url(); ?>">Voltar para o Início</a>
	</div>
</body>
</html>

