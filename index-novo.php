<?php 
/**
 * Index migrado para usar a nova estrutura Composer
 * Esta é a versão que deve substituir o index.php antigo
 */

// Carrega o autoloader do Composer
require_once 'vendor/autoload.php';

use Advocacia\Database\Connection;
use Advocacia\Config\Database;

// Inicia a sessão
@session_start();

// Verifica se as configurações estão válidas
$configErrors = Database::validate();
$erro_conexao = false;
$dados_usuarios = [];

if (empty($configErrors)) {
    try {
        // Usa a nova classe de conexão
        $db = Connection::getInstance();
        
        // Verifica se a conexão está funcionando
        if ($db->getPdo()) {
            $senha = '123';
            $senha_cript = md5($senha);
            
            // Busca usuários existentes
            $res_usuarios = $db->query("SELECT * from usuarios");
            $dados_usuarios = $res_usuarios->fetchAll(PDO::FETCH_ASSOC);
            $linhas_usuarios = count($dados_usuarios);
            
            // Se não há usuários, cria o administrador padrão
            if($linhas_usuarios == 0){
                $db->query("INSERT into usuarios (nome, cpf, usuario, senha, senha_original, nivel) values (?, ?, ?, ?, ?, ?)", 
                    ['Administrador', '000.000.000-00', 'joelvieirasouza@gmail.com', $senha_cript, $senha, 'admin']);
            }
        } else {
            $erro_conexao = true;
        }
        
    } catch (Exception $e) {
        $erro_conexao = true;
        error_log('Erro na operação do banco: ' . $e->getMessage());
    }
} else {
    $erro_conexao = true;
    error_log('Erros de configuração: ' . implode(', ', $configErrors));
}

// Carrega configurações do site
require_once 'config.php';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Painel Administrativo - Sistema de Advocacia</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">

  <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">

  <link href="css/style.css" rel="stylesheet">
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
<body>

<section class="login-block">
    <div class="container">
	<div class="row">
		<div class="col-md-4 login-sec">
		    <h2 class="text-center">Faça seu Login</h2>
		    
		    <?php if ($erro_conexao): ?>
		    <div class="alert alert-warning" role="alert">
		        <strong>⚠️ Sistema em Manutenção</strong><br>
		        <small>O sistema está sendo atualizado. Tente novamente em alguns minutos.</small>
		        <hr>
		        <small class="text-muted">
		            <strong>Status:</strong> 
		            <?php if (!empty($configErrors)): ?>
		                Configuração do banco incompleta
		            <?php else: ?>
		                Conexão com banco indisponível
		            <?php endif; ?>
		        </small>
		    </div>
		    <?php else: ?>
		    <div class="alert alert-success" role="alert">
		        <strong>✅ Sistema Funcionando</strong><br>
		        <small>Banco de dados conectado com sucesso!</small>
		    </div>
		    <?php endif; ?>
		    
		    <form class="login-form" method="post" action="autenticar.php">
                <div class="form-group">
                    <label for="exampleInputEmail1" class="text-uppercase">Usuário</label>
                    <input type="email" name="usuario" class="form-control" placeholder="Digite seu Email" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1" class="text-uppercase">Senha</label>
                    <input type="password" name="senha" class="form-control" placeholder="" required>
                </div>
                
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input">
                        <small>Lembrar-Me</small>
                    </label>
                    <button type="submit" class="btn btn-login float-right" <?php echo $erro_conexao ? 'disabled' : ''; ?>>
                        <?php echo $erro_conexao ? 'Sistema Indisponível' : 'Logar'; ?>
                    </button>
                </div>
            </form>
            
            <div class="copy-text">
                <a title="Clique aqui para recuperar a senha!" href="#" class="text-dark">Recuperar Senha</a>
            </div>
            
            <!-- Informações de debug (remover em produção) -->
            <?php if ($erro_conexao): ?>
            <div class="mt-3 p-2 bg-light rounded">
                <small class="text-muted">
                    <strong>Debug:</strong><br>
                    <a href="teste-banco.php" class="text-info">🔧 Testar Configuração do Banco</a><br>
                    <a href="teste-servidor.php" class="text-info">🖥️ Testar Servidor</a><br>
                    <a href="exemplo-migracao.php" class="text-info">🔄 Ver Migração</a>
                </small>
            </div>
            <?php endif; ?>
		</div>
		<div class="col-md-8 banner-sec">
            <!-- Banner ou informações do sistema -->
            <div class="text-center text-white p-5">
                <h3>Sistema de Advocacia</h3>
                <p>Gerenciamento completo para escritórios de advocacia</p>
                <small>Versão com Composer e PSR-4</small>
            </div>
	</div>
</div>
</section>

</body>
</html>
