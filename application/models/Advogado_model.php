<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model de Advogados
 */
class Advogado_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Busca advogado por ID
     */
    public function getById($id)
    {
        $this->db->select('a.*, u.nome, u.email, u.telefone');
        $this->db->from('advogados a');
        $this->db->join('usuarios u', 'u.id = a.user_id', 'left');
        $this->db->where('a.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Lista advogados
     */
    public function getAll($filtros = array(), $limit = null, $offset = null)
    {
        $this->db->select('a.*, u.nome, u.email');
        $this->db->from('advogados a');
        $this->db->join('usuarios u', 'u.id = a.user_id', 'left');

        if (isset($filtros['ativo'])) {
            $this->db->where('a.ativo', $filtros['ativo']);
        }

        $this->db->order_by('u.nome', 'ASC');

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Adiciona novo advogado
     */
    public function add($data)
    {
        $data['dataCadastro'] = date('Y-m-d H:i:s');
        $data['dataAlteracao'] = date('Y-m-d H:i:s');

        if ($this->db->insert('advogados', $data)) {
            return $this->db->insert_id();
        }

        return false;
    }

    /**
     * Atualiza advogado
     */
    public function edit($id, $data)
    {
        $data['dataAlteracao'] = date('Y-m-d H:i:s');

        $this->db->where('id', $id);
        return $this->db->update('advogados', $data);
    }

    /**
     * Remove advogado
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('advogados');
    }
}

