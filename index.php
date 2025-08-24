<?php 

require_once("conexao.php");

// Verifica se a conexão foi estabelecida
if ($pdo === null) {
    // Se não conseguiu conectar, mostra mensagem amigável
    $erro_conexao = true;
} else {
    $erro_conexao = false;
    
    try {
        $senha = '123';
        $senha_cript = md5($senha);
        $res_usuarios = $pdo->query("SELECT * from usuarios");
        $dados_usuarios = $res_usuarios->fetchAll(PDO::FETCH_ASSOC);
        $linhas_usuarios = count($dados_usuarios);
        if($linhas_usuarios == 0){
            $res_insert = $pdo->query("INSERT into usuarios (nome, cpf, usuario, senha, senha_original, nivel) values ('Administrador', '000.000.000-00', '$email_site', '$senha_cript', '$senha', 'admin')");
        }
    } catch (Exception $e) {
        $erro_conexao = true;
        error_log('Erro na operação do banco: ' . $e->getMessage());
    }
}

 ?>


 <!DOCTYPE html>
<html>
<head>
  <title>Painel Administrativo</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">

  
  <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">

<link href="css/style.css" rel="stylesheet">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

</head>
<body>

<section class="login-block">
    <div class="container">
	<div class="row">
		<div class="col-md-4 login-sec">
		    <h2 class="text-center">Faça seu LoginXXX</h2>
		    
		    <?php if (isset($erro_conexao) && $erro_conexao): ?>
		    <div class="alert alert-warning" role="alert">
		        <strong>⚠️ Atenção:</strong> Sistema temporariamente indisponível.<br>
		        <small>Entre em contato com o administrador ou tente novamente mais tarde.</small>
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
    <button type="submit" class="btn btn-login float-right">Logar</button>
  </div>
  
</form>
<div class="copy-text"><a title="Clique aqui para recuperar a senha!" href="#" class="text-dark">Recuperar Senha</a></div>
		</div>
		<div class="col-md-8 banner-sec">
           
	</div>
</div>
</section>


</body>
</html>