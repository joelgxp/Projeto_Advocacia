<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model de Audiências
 */
class Audiencia_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getById($id)
    {
        $query = $this->db->where('id', $id)->get('audiencias');
        return $query->row();
    }

    public function getAll($filtros = array(), $limit = null, $offset = null)
    {
        if (isset($filtros['processo_id'])) {
            $this->db->where('processo_id', $filtros['processo_id']);
        }

        if (isset($filtros['data'])) {
            $this->db->where('data', $filtros['data']);
        }

        $this->db->order_by('data', 'ASC');
        $this->db->order_by('hora', 'ASC');

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('audiencias');
        return $query->result();
    }

    public function add($data)
    {
        return $this->db->insert('audiencias', $data) ? $this->db->insert_id() : false;
    }

    public function edit($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('audiencias', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('audiencias');
    }
}

