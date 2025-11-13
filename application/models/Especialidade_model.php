<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model de Especialidades
 */
class Especialidade_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getById($id)
    {
        $query = $this->db->where('id', $id)->get('especialidades');
        return $query->row();
    }

    public function getAll($filtros = array())
    {
        if (isset($filtros['ativo'])) {
            $this->db->where('ativo', $filtros['ativo']);
        }

        $this->db->order_by('nome', 'ASC');
        $query = $this->db->get('especialidades');
        return $query->result();
    }

    public function add($data)
    {
        return $this->db->insert('especialidades', $data) ? $this->db->insert_id() : false;
    }

    public function edit($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('especialidades', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('especialidades');
    }
}

