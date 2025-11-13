<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<title>Erro</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			margin: 40px;
			background: #f5f5f5;
		}
		.error-container {
			background: #fff;
			border: 1px solid #ddd;
			border-radius: 4px;
			padding: 20px;
			max-width: 800px;
		}
		h1 {
			color: #d32f2f;
			margin-top: 0;
		}
		.error-message {
			background: #ffebee;
			border-left: 4px solid #d32f2f;
			padding: 15px;
			margin: 15px 0;
		}
	</style>
</head>
<body>
	<div class="error-container">
		<h1>Erro</h1>
		<div class="error-message">
			<?php echo $message; ?>
		</div>
		<p><a href="<?php echo base_url(); ?>">Voltar para o Início</a></p>
	</div>
</body>
</html>

