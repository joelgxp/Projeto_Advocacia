<?php
require_once("../middleware.php");
requireAdmin();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>🏠 Painel Administrativo - Home</h1>
            <p>Bem-vindo ao sistema de gerenciamento administrativo.</p>
            
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">Funcionários</h5>
                            <p class="card-text">Gerenciar funcionários do escritório</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Advogados</h5>
                            <p class="card-text">Gerenciar advogados e especialidades</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h5 class="card-title">Clientes</h5>
                            <p class="card-text">Gerenciar base de clientes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">Configurações</h5>
                            <p class="card-text">Cargos, especialidades e varas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>