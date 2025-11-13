<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model de Processos
 */
class Processo_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Busca processo por ID
     */
    public function getById($id)
    {
        $this->db->select('p.*, c.nome as cliente_nome, c.cpf_cnpj, a.nome as advogado_nome, v.nome as vara_nome, e.nome as especialidade_nome');
        $this->db->from('processos p');
        $this->db->join('clientes c', 'c.id = p.cliente_id', 'left');
        $this->db->join('advogados a', 'a.id = p.advogado_id', 'left');
        $this->db->join('varas v', 'v.id = p.vara_id', 'left');
        $this->db->join('especialidades e', 'e.id = p.especialidade_id', 'left');
        $this->db->where('p.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Lista processos com filtros
     */
    public function getAll($filtros = array(), $limit = null, $offset = null)
    {
        $this->db->select('p.*, c.nome as cliente_nome, a.nome as advogado_nome, v.nome as vara_nome');
        $this->db->from('processos p');
        $this->db->join('clientes c', 'c.id = p.cliente_id', 'left');
        $this->db->join('advogados a', 'a.id = p.advogado_id', 'left');
        $this->db->join('varas v', 'v.id = p.vara_id', 'left');

        if (isset($filtros['cliente_id'])) {
            $this->db->where('p.cliente_id', $filtros['cliente_id']);
        }

        if (isset($filtros['advogado_id'])) {
            $this->db->where('p.advogado_id', $filtros['advogado_id']);
        }

        if (isset($filtros['status'])) {
            $this->db->where('p.status', $filtros['status']);
        }

        if (isset($filtros['numero_processo'])) {
            $this->db->like('p.numero_processo', $filtros['numero_processo']);
        }

        $this->db->order_by('p.data_abertura', 'DESC');
        $this->db->order_by('p.id', 'DESC');

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Adiciona novo processo
     */
    public function add($data)
    {
        $data['dataCadastro'] = date('Y-m-d H:i:s');
        $data['dataAlteracao'] = date('Y-m-d H:i:s');

        if ($this->db->insert('processos', $data)) {
            return $this->db->insert_id();
        }

        return false;
    }

    /**
     * Atualiza processo
     */
    public function edit($id, $data)
    {
        $data['dataAlteracao'] = date('Y-m-d H:i:s');

        $this->db->where('id', $id);
        return $this->db->update('processos', $data);
    }

    /**
     * Remove processo
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('processos');
    }

    /**
     * Conta total de processos
     */
    public function count($filtros = array())
    {
        if (isset($filtros['cliente_id'])) {
            $this->db->where('cliente_id', $filtros['cliente_id']);
        }

        if (isset($filtros['advogado_id'])) {
            $this->db->where('advogado_id', $filtros['advogado_id']);
        }

        if (isset($filtros['status'])) {
            $this->db->where('status', $filtros['status']);
        }

        return $this->db->count_all_results('processos');
    }
}

