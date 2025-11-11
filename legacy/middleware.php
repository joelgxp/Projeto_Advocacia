<?php
/**
 * Middleware de Autenticação
 * 
 * Este arquivo deve ser incluído no início de todas as páginas protegidas
 * para verificar se o usuário está logado e tem permissão de acesso
 */

// Inicia a sessão se não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica se o usuário está logado
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['nivel_usuario']) && !empty($_SESSION['nivel_usuario']);
}

/**
 * Verifica se o usuário tem nível específico
 * @param string $nivel
 * @return bool
 */
function hasLevel($nivel) {
    return isLoggedIn() && $_SESSION['nivel_usuario'] === $nivel;
}

/**
 * Verifica se o usuário tem pelo menos um dos níveis especificados
 * @param array $niveis
 * @return bool
 */
function hasAnyLevel($niveis) {
    if (!isLoggedIn()) return false;
    
    foreach ($niveis as $nivel) {
        if ($_SESSION['nivel_usuario'] === $nivel) {
            return true;
        }
    }
    return false;
}

/**
 * Middleware principal - verifica autenticação
 * @param string|array $niveis_permitidos
 * @param string $redirect_url
 */
function requireAuth($niveis_permitidos = null, $redirect_url = '../index.php') {
    // Se não estiver logado, redireciona
    if (!isLoggedIn()) {
        header("Location: $redirect_url");
        exit;
    }
    
    // Se especificou níveis, verifica permissão
    if ($niveis_permitidos !== null) {
        if (is_array($niveis_permitidos)) {
            if (!hasAnyLevel($niveis_permitidos)) {
                header("Location: $redirect_url");
                exit;
            }
        } else {
            if (!hasLevel($niveis_permitidos)) {
                header("Location: $redirect_url");
                exit;
            }
        }
    }
}

/**
 * Middleware para páginas que só admin pode acessar
 */
function requireAdmin() {
    requireAuth('admin', '../index.php');
}

/**
 * Middleware para páginas que só advogados podem acessar
 */
function requireAdvogado() {
    requireAuth('Advogado', '../index.php');
}

/**
 * Middleware para páginas que só recepção pode acessar
 */
function requireRecepcao() {
    requireAuth(['Recepcionista', 'Tesoureiro'], '../index.php');
}

/**
 * Middleware para páginas que qualquer usuário logado pode acessar
 */
function requireAnyUser() {
    requireAuth();
}

/**
 * Retorna informações do usuário logado
 * @return array|null
 */
function getCurrentUser() {
    if (!isLoggedIn()) return null;
    
    return [
        'nome' => $_SESSION['nome_usuario'] ?? '',
        'email' => $_SESSION['email_usuario'] ?? '',
        'nivel' => $_SESSION['nivel_usuario'] ?? '',
        'cpf' => $_SESSION['cpf_usuario'] ?? ''
    ];
}

/**
 * Verifica se o usuário atual é admin
 * @return bool
 */
function isAdmin() {
    return hasLevel('admin');
}

/**
 * Verifica se o usuário atual é advogado
 * @return bool
 */
function isAdvogado() {
    return hasLevel('Advogado');
}

/**
 * Verifica se o usuário atual é recepção
 * @return bool
 */
function isRecepcao() {
    return hasAnyLevel(['Recepcionista', 'Tesoureiro']);
}

/**
 * Logout do usuário
 */
function logout() {
    session_destroy();
    session_start();
    session_regenerate_id(true);
}
?>
