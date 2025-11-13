<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Biblioteca de Permissões
 * 
 * Gerencia permissões do sistema baseado em grupos
 * Padrão MapOS adaptado para sistema de advocacia
 */
class Permission {

    protected $CI;
    protected $permissoes = array();

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('Permissao_model');
    }

    /**
     * Verifica se o grupo de permissões tem acesso a uma ação
     * 
     * @param int $permissoes_id ID do grupo de permissões
     * @param string $permissao Nome da permissão (ex: 'processos.v')
     * @return bool
     */
    public function check($permissoes_id, $permissao)
    {
        if (!$permissoes_id) {
            return false;
        }

        // Carregar permissões do grupo
        $permissoes = $this->CI->Permissao_model->get($permissoes_id);
        
        if (!$permissoes) {
            return false;
        }

        // Deserializar permissões
        $permissoes_array = unserialize($permissoes->permissoes);
        
        if (!is_array($permissoes_array)) {
            return false;
        }

        // Verificar permissão específica
        return isset($permissoes_array[$permissao]) && $permissoes_array[$permissao] == '1';
    }

    /**
     * Verifica se o usuário tem permissão para visualizar
     * 
     * @param int $permissoes_id
     * @param string $modulo
     * @return bool
     */
    public function canView($permissoes_id, $modulo)
    {
        return $this->check($permissoes_id, $modulo . '.v');
    }

    /**
     * Verifica se o usuário tem permissão para cadastrar
     * 
     * @param int $permissoes_id
     * @param string $modulo
     * @return bool
     */
    public function canCreate($permissoes_id, $modulo)
    {
        return $this->check($permissoes_id, $modulo . '.c');
    }

    /**
     * Verifica se o usuário tem permissão para editar
     * 
     * @param int $permissoes_id
     * @param string $modulo
     * @return bool
     */
    public function canEdit($permissoes_id, $modulo)
    {
        return $this->check($permissoes_id, $modulo . '.e');
    }

    /**
     * Verifica se o usuário tem permissão para deletar
     * 
     * @param int $permissoes_id
     * @param string $modulo
     * @return bool
     */
    public function canDelete($permissoes_id, $modulo)
    {
        return $this->check($permissoes_id, $modulo . '.d');
    }
}

