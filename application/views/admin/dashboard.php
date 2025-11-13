<div class="container-fluid">
    <h2 class="mb-4">Dashboard Administrativo</h2>
    
    <div class="row">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 class="card-title">Processos</h5>
                    <h2 class="text-primary"><?php echo isset($total_processos) ? $total_processos : 0; ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 class="card-title">Clientes</h5>
                    <h2 class="text-success"><?php echo isset($total_clientes) ? $total_clientes : 0; ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 class="card-title">Advogados</h5>
                    <h2 class="text-info"><?php echo isset($total_advogados) ? $total_advogados : 0; ?></h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Processos Recentes</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($processos_recentes) && !empty($processos_recentes)): ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Cliente</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($processos_recentes as $processo): ?>
                                    <tr>
                                        <td><?php echo $processo->numero_processo; ?></td>
                                        <td><?php echo isset($processo->cliente_nome) ? $processo->cliente_nome : '-'; ?></td>
                                        <td><?php echo $processo->status; ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($processo->data_abertura)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-muted">Nenhum processo encontrado.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

