

<?php $__env->startSection('title', 'Consulta Processual'); ?>

<?php $__env->startSection('page-title', 'Consulta Processual'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-search me-2"></i>Consulta Processual - API CNJ/DataJud
                </h5>
            </div>
            <div class="card-body">
                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('warning')): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i><?php echo e(session('warning')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('admin.consulta-processual.consultar')); ?>" method="POST" id="formConsulta">
                    <?php echo csrf_field(); ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="numero_processo" class="form-label">
                                Número do Processo <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control <?php $__errorArgs = ['numero_processo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="numero_processo" 
                                name="numero_processo" 
                                value="<?php echo e(old('numero_processo')); ?>"
                                placeholder="Ex: 0000123-45.2023.8.26.0100"
                                required
                            >
                            <?php $__errorArgs = ['numero_processo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                Formato CNJ: NNNNNNN-DD.AAAA.J.TR.OOOO<br>
                                Exemplo: 5001234-85.2023.8.13.0139<br>
                                Aceita com ou sem formatação
                            </small>
                            
                            <!-- Informações detectadas do número -->
                            <div id="info-processo" class="mt-2" style="display: none;">
                                <div class="alert alert-info mb-0">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <div>
                                            <strong>Informações detectadas:</strong>
                                            <div id="info-detalhes" class="mt-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tribunal" class="form-label">
                                Tribunal <span class="text-muted">(Opcional)</span>
                            </label>
                            <select 
                                class="form-select <?php $__errorArgs = ['tribunal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="tribunal" 
                                name="tribunal"
                            >
                                <option value="">Detectar automaticamente do número</option>
                                <?php $__currentLoopData = $tribunaisListaPlana; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $codigo => $nome): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($codigo); ?>" <?php echo e(old('tribunal') == $codigo ? 'selected' : ''); ?>>
                                        <?php echo e($codigo); ?> - <?php echo e($nome); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['tribunal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                O código do tribunal será detectado automaticamente do número do processo (padrão CNJ)
                            </small>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informação:</strong> A consulta utiliza a API pública do CNJ/DataJud. 
                        Os dados são atualizados automaticamente e podem ter um pequeno atraso.
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" onclick="limparFormulario()">
                            <i class="fas fa-times me-1"></i> Limpar
                        </button>
                        <button type="submit" class="btn btn-primary" id="btnConsultar">
                            <i class="fas fa-search me-1"></i> Consultar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.getElementById('formConsulta').addEventListener('submit', function(e) {
        const btnConsultar = document.getElementById('btnConsultar');
        btnConsultar.disabled = true;
        btnConsultar.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Consultando...';
    });

    function limparFormulario() {
        document.getElementById('formConsulta').reset();
        document.getElementById('info-processo').style.display = 'none';
        document.getElementById('info-detalhes').innerHTML = '';
        document.getElementById('numero_processo').focus();
    }

    // Mapeamento de segmentos - Conforme tabela oficial do CNJ
    const segmentos = {
        '1': 'Supremo Tribunal Federal (STF)',
        '2': 'Conselho Nacional de Justiça (CNJ)',
        '3': 'Superior Tribunal de Justiça (STJ)',
        '4': 'Justiça Federal',
        '5': 'Justiça do Trabalho',
        '6': 'Justiça Eleitoral',
        '7': 'Justiça Militar da União',
        '8': 'Justiça dos Estados e do Distrito Federal e Territórios',
        '9': 'Justiça Militar Estadual'
    };

    // Função para formatar número do processo CNJ
    // Formato: NNNNNNN-DD.AAAA.J.TR.OOOO
    function formatarNumeroProcesso(valor) {
        // Remove tudo exceto números
        const numero = valor.replace(/[^0-9]/g, '');
        
        // Se não tem números, retorna vazio
        if (!numero) return '';
        
        // Limita a 20 dígitos
        const numeroLimitado = numero.substring(0, 20);
        const tamanho = numeroLimitado.length;
        
        // Formatação progressiva conforme o usuário digita
        if (tamanho <= 7) {
            // Apenas o número sequencial: NNNNNNN
            return numeroLimitado;
        } else if (tamanho <= 9) {
            // NNNNNNN-DD
            return `${numeroLimitado.substring(0, 7)}-${numeroLimitado.substring(7)}`;
        } else if (tamanho <= 13) {
            // NNNNNNN-DD.AAAA
            return `${numeroLimitado.substring(0, 7)}-${numeroLimitado.substring(7, 9)}.${numeroLimitado.substring(9)}`;
        } else if (tamanho <= 14) {
            // NNNNNNN-DD.AAAA.J
            return `${numeroLimitado.substring(0, 7)}-${numeroLimitado.substring(7, 9)}.${numeroLimitado.substring(9, 13)}.${numeroLimitado.substring(13)}`;
        } else if (tamanho <= 16) {
            // NNNNNNN-DD.AAAA.J.TR
            return `${numeroLimitado.substring(0, 7)}-${numeroLimitado.substring(7, 9)}.${numeroLimitado.substring(9, 13)}.${numeroLimitado.substring(13, 14)}.${numeroLimitado.substring(14)}`;
        } else {
            // NNNNNNN-DD.AAAA.J.TR.OOOO (completo)
            return `${numeroLimitado.substring(0, 7)}-${numeroLimitado.substring(7, 9)}.${numeroLimitado.substring(9, 13)}.${numeroLimitado.substring(13, 14)}.${numeroLimitado.substring(14, 16)}.${numeroLimitado.substring(16, 20)}`;
        }
    }

    // Função para detectar e exibir informações do processo
    function detectarInformacoesProcesso() {
        const numeroInput = document.getElementById('numero_processo');
        const numero = numeroInput.value.replace(/[^0-9]/g, '');
        const infoDiv = document.getElementById('info-processo');
        const infoDetalhes = document.getElementById('info-detalhes');
        const selectTribunal = document.getElementById('tribunal');
        const tribunaisPorSegmento = <?php echo json_encode($tribunaisPorSegmento, 15, 512) ?>;
        
        // Limpar informações anteriores
        infoDiv.style.display = 'none';
        infoDetalhes.innerHTML = '';
        
        // Verificar se tem 20 dígitos (formato CNJ completo)
        // Formato: NNNNNNN-DD.AAAA.J.TR.OOOO (20 dígitos)
        // Posições: 0-6: NNNNNNN, 7-8: DD, 9-12: AAAA, 13: J, 14-15: TR, 16-19: OOOO
        if (numero.length === 20) {
            const numeroSequencial = numero.substring(0, 7);   // NNNNNNN (posições 0-6)
            const digitoVerificador = numero.substring(7, 9);  // DD (posições 7-8)
            const ano = numero.substring(9, 13);               // AAAA (posições 9-12)
            const segmento = numero.substring(13, 14);         // J (posição 13)
            const tribunal = numero.substring(14, 16);         // TR (posições 14-15)
            const origem = numero.substring(16, 20);           // OOOO (posições 16-19)
            
            // Formatar número
            const numeroFormatado = `${numeroSequencial}-${digitoVerificador}.${ano}.${segmento}.${tribunal}.${origem}`;
            
            // Obter nome do segmento
            const nomeSegmento = segmentos[segmento] || 'Desconhecido';
            
            // Obter nome do tribunal
            let nomeTribunal = 'Não identificado';
            if (tribunaisPorSegmento[segmento] && 
                tribunaisPorSegmento[segmento].tribunais && 
                tribunaisPorSegmento[segmento].tribunais[tribunal]) {
                nomeTribunal = tribunaisPorSegmento[segmento].tribunais[tribunal];
            }
            
            // Exibir informações
            infoDetalhes.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>Número formatado:</strong> ${numeroFormatado}
                    </div>
                    <div class="col-md-6">
                        <strong>Ano:</strong> ${ano}
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <strong>Segmento da Justiça:</strong> 
                        <span class="badge bg-primary">${segmento} - ${nomeSegmento}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Tribunal:</strong> 
                        <span class="badge bg-success">${tribunal} - ${nomeTribunal}</span>
                    </div>
                </div>
            `;
            infoDiv.style.display = 'block';
            
            // Filtrar e selecionar tribunal
            if (tribunaisPorSegmento[segmento] && tribunaisPorSegmento[segmento].tribunais) {
                // Limpar opções atuais (exceto a primeira)
                while (selectTribunal.options.length > 1) {
                    selectTribunal.remove(1);
                }
                
                // Adicionar tribunais do segmento detectado
                const tribunaisSegmento = tribunaisPorSegmento[segmento].tribunais;
                for (const [codigo, nome] of Object.entries(tribunaisSegmento)) {
                    const option = document.createElement('option');
                    option.value = codigo;
                    option.textContent = `${codigo} - ${nome}`;
                    selectTribunal.appendChild(option);
                }
                
                // Selecionar tribunal se encontrado
                if (tribunal && selectTribunal.querySelector(`option[value="${tribunal}"]`)) {
                    selectTribunal.value = tribunal;
                }
            }
        } else if (numero.length > 0) {
            // Mostrar mensagem de número incompleto
            infoDetalhes.innerHTML = `
                <div class="text-warning">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Número incompleto. Digite os 20 dígitos do processo CNJ.
                </div>
            `;
            infoDiv.style.display = 'block';
        }
    }

    // Formatar número do processo em tempo real
    const numeroProcessoInput = document.getElementById('numero_processo');
    
    numeroProcessoInput.addEventListener('input', function(e) {
        const valor = e.target.value;
        const cursorPos = e.target.selectionStart;
        
        // Formatar o número
        const numeroFormatado = formatarNumeroProcesso(valor);
        
        // Se o valor mudou, atualizar
        if (valor !== numeroFormatado) {
            e.target.value = numeroFormatado;
            
            // Ajustar posição do cursor
            // Contar quantos caracteres não numéricos foram adicionados antes da posição do cursor
            const numerosAntesCursor = valor.substring(0, cursorPos).replace(/[^0-9]/g, '').length;
            let novaPosicao = 0;
            let numerosEncontrados = 0;
            
            for (let i = 0; i < numeroFormatado.length && numerosEncontrados < numerosAntesCursor; i++) {
                if (numeroFormatado[i].match(/[0-9]/)) {
                    numerosEncontrados++;
                }
                novaPosicao = i + 1;
            }
            
            e.target.setSelectionRange(novaPosicao, novaPosicao);
        }
        
        // Detectar informações
        detectarInformacoesProcesso();
    });
    
    // Formatar ao colar (paste)
    numeroProcessoInput.addEventListener('paste', function(e) {
        e.preventDefault();
        const texto = (e.clipboardData || window.clipboardData).getData('text');
        const numeroFormatado = formatarNumeroProcesso(texto);
        e.target.value = numeroFormatado;
        detectarInformacoesProcesso();
        e.target.focus();
    });
    
    // Detectar informações ao sair do campo
    numeroProcessoInput.addEventListener('blur', function() {
        // Garantir formatação final
        const valor = this.value;
        const numeroFormatado = formatarNumeroProcesso(valor);
        if (valor !== numeroFormatado && numeroFormatado.length === 23) { // 20 dígitos + 3 separadores = 23 caracteres
            this.value = numeroFormatado;
        }
        detectarInformacoesProcesso();
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia\resources\views/admin/consulta-processual/index.blade.php ENDPATH**/ ?>