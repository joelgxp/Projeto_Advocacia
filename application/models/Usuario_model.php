<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model de Usuários
 * 
 * Adaptado do Laravel User model para CodeIgniter 3
 */
class Usuario_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Busca usuário por ID
     * 
     * @param int $id
     * @return object|null
     */
    public function getById($id)
    {
        $query = $this->db->where('id', $id)->get('usuarios');
        return $query->row();
    }

    /**
     * Busca usuário por email ou usuario
     * 
     * @param string $email
     * @return object|null
     */
    public function getByEmail($email)
    {
        // Tentar primeiro por 'usuario', depois por 'email' (compatibilidade)
        $query = $this->db->where('usuario', $email)->get('usuarios');
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        // Se não encontrar, tentar por 'email' (caso a coluna exista)
        $query = $this->db->where('email', $email)->get('usuarios');
        return $query->row();
    }

    /**
     * Verifica credenciais de login
     * 
     * @param string $usuario_input Usuário ou email
     * @param string $password
     * @return object|false
     */
    public function verificarLogin($usuario_input, $password)
    {
        try {
            $usuario = $this->getByEmail($usuario_input);
            
            if (!$usuario) {
                return false;
            }

            // Verificar se está ativo (se campo existir)
            if (isset($usuario->ativo) && !$usuario->ativo) {
                return false;
            }

            // Verificar se tem senha
            if (!isset($usuario->senha) || empty($usuario->senha)) {
                log_message('error', 'Usuário sem senha: ' . $usuario_input);
                return false;
            }

            // Verificar senha (pode ser hash bcrypt ou md5 antigo)
            $senha_valida = false;
            
            // Tentar password_verify primeiro (bcrypt)
            if (password_verify($password, $usuario->senha)) {
                $senha_valida = true;
            }
            // Se não funcionar, tentar md5 (compatibilidade com sistema antigo)
            elseif (md5($password) === $usuario->senha) {
                $senha_valida = true;
                // Atualizar para bcrypt
                $this->db->where('id', $usuario->id)
                         ->update('usuarios', array('senha' => password_hash($password, PASSWORD_DEFAULT)));
            }
            
            if (!$senha_valida) {
                return false;
            }

            return $usuario;
        } catch (Exception $e) {
            log_message('error', 'Erro em verificarLogin: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista todos os usuários
     * 
     * @param array $filtros
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAll($filtros = array(), $limit = null, $offset = null)
    {
        if (isset($filtros['ativo'])) {
            $this->db->where('ativo', $filtros['ativo']);
        }

        if (isset($filtros['permissoes_id'])) {
            $this->db->where('permissoes_id', $filtros['permissoes_id']);
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('usuarios');
        return $query->result();
    }

    /**
     * Adiciona novo usuário
     * 
     * @param array $data
     * @return int|false ID do usuário inserido
     */
    public function add($data)
    {
        // Hash da senha
        if (isset($data['senha'])) {
            $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        }

        $data['dataCadastro'] = date('Y-m-d H:i:s');
        $data['dataAlteracao'] = date('Y-m-d H:i:s');

        if ($this->db->insert('usuarios', $data)) {
            return $this->db->insert_id();
        }

        return false;
    }

    /**
     * Atualiza usuário
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function edit($id, $data)
    {
        // Hash da senha se fornecida
        if (isset($data['senha']) && !empty($data['senha'])) {
            $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        } else {
            unset($data['senha']);
        }

        $data['dataAlteracao'] = date('Y-m-d H:i:s');

        $this->db->where('id', $id);
        return $this->db->update('usuarios', $data);
    }

    /**
     * Remove usuário
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('usuarios');
    }

    /**
     * Conta total de usuários
     * 
     * @param array $filtros
     * @return int
     */
    public function count($filtros = array())
    {
        if (isset($filtros['ativo'])) {
            $this->db->where('ativo', $filtros['ativo']);
        }

        return $this->db->count_all_results('usuarios');
    }
}

