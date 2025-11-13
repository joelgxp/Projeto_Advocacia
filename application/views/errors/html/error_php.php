<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<title>Erro PHP</title>
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
		.error-file {
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
		<h1>Erro PHP</h1>
		<div class="error-message">
			<strong>Severidade:</strong> <?php echo $severity; ?><br>
			<strong>Mensagem:</strong> <?php echo $message; ?><br>
			<strong>Arquivo:</strong> <?php echo $filepath; ?><br>
			<strong>Linha:</strong> <?php echo $line; ?>
		</div>
		<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>
			<div class="error-file">
				<strong>Backtrace:</strong>
				<?php foreach (debug_backtrace() as $error): ?>
					<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>
						<p style="margin-left:10px">
							Arquivo: <?php echo str_replace(array(BASEPATH, FCPATH), '', $error['file']); ?><br />
							Linha: <?php echo $error['line']; ?><br />
							Função: <?php echo $error['function']; ?>
						</p>
					<?php endif ?>
				<?php endforeach ?>
			</div>
		<?php endif ?>
	</div>
</body>
</html>

