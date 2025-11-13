<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<title>Erro de Banco de Dados</title>
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
		.error-code {
			font-family: monospace;
			background: #f5f5f5;
			padding: 10px;
			border-radius: 3px;
			margin: 10px 0;
		}
	</style>
</head>
<body>
	<div class="error-container">
		<h1>Erro de Banco de Dados</h1>
		<div class="error-message">
			<strong>Mensagem:</strong> <?php echo $message; ?><br>
			<?php if (isset($heading)): ?>
				<strong>Tipo:</strong> <?php echo $heading; ?><br>
			<?php endif; ?>
		</div>
		<?php if (ENVIRONMENT !== 'production'): ?>
			<div class="error-code">
				<strong>Query:</strong><br>
				<?php echo isset($sql) ? $sql : 'N/A'; ?>
			</div>
		<?php endif; ?>
		<p><a href="<?php echo base_url(); ?>">Voltar para o Início</a></p>
	</div>
</body>
</html>

