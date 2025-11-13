<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model de Permissões
 * 
 * Gerencia grupos de permissões do sistema
 */
class Permissao_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Busca grupo de permissões por ID
     * 
     * @param int $id
     * @return object|null
     */
    public function get($id)
    {
        $query = $this->db->where('idPermissoes', $id)->get('permissoes');
        return $query->row();
    }

    /**
     * Lista todos os grupos de permissões
     * 
     * @return array
     */
    public function getAll()
    {
        $query = $this->db->get('permissoes');
        return $query->result();
    }

    /**
     * Adiciona novo grupo de permissões
     * 
     * @param array $data
     * @return int|false
     */
    public function add($data)
    {
        // Serializar permissões se for array
        if (isset($data['permissoes']) && is_array($data['permissoes'])) {
            $data['permissoes'] = serialize($data['permissoes']);
        }

        if ($this->db->insert('permissoes', $data)) {
            return $this->db->insert_id();
        }

        return false;
    }

    /**
     * Atualiza grupo de permissões
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function edit($id, $data)
    {
        // Serializar permissões se for array
        if (isset($data['permissoes']) && is_array($data['permissoes'])) {
            $data['permissoes'] = serialize($data['permissoes']);
        }

        $this->db->where('idPermissoes', $id);
        return $this->db->update('permissoes', $data);
    }

    /**
     * Remove grupo de permissões
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $this->db->where('idPermissoes', $id);
        return $this->db->delete('permissoes');
    }
}

