<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model de Varas
 */
class Vara_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getById($id)
    {
        $query = $this->db->where('id', $id)->get('varas');
        return $query->row();
    }

    public function getAll($filtros = array())
    {
        if (isset($filtros['ativo'])) {
            $this->db->where('ativo', $filtros['ativo']);
        }

        $this->db->order_by('nome', 'ASC');
        $query = $this->db->get('varas');
        return $query->result();
    }

    public function add($data)
    {
        return $this->db->insert('varas', $data) ? $this->db->insert_id() : false;
    }

    public function edit($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('varas', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('varas');
    }
}

