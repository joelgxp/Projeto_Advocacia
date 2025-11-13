<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model de Clientes
 */
class Cliente_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Busca cliente por ID
     */
    public function getById($id)
    {
        $query = $this->db->where('id', $id)->get('clientes');
        return $query->row();
    }

    /**
     * Busca cliente por CPF/CNPJ
     */
    public function getByCpfCnpj($cpf_cnpj)
    {
        $query = $this->db->where('cpf_cnpj', $cpf_cnpj)->get('clientes');
        return $query->row();
    }

    /**
     * Lista clientes com filtros
     */
    public function getAll($filtros = array(), $limit = null, $offset = null)
    {
        if (isset($filtros['ativo'])) {
            $this->db->where('ativo', $filtros['ativo']);
        }

        if (isset($filtros['tipo_pessoa'])) {
            $this->db->where('tipo_pessoa', $filtros['tipo_pessoa']);
        }

        if (isset($filtros['nome'])) {
            $this->db->like('nome', $filtros['nome']);
        }

        if (isset($filtros['cpf_cnpj'])) {
            $this->db->like('cpf_cnpj', $filtros['cpf_cnpj']);
        }

        $this->db->order_by('nome', 'ASC');

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('clientes');
        return $query->result();
    }

    /**
     * Adiciona novo cliente
     */
    public function add($data)
    {
        $data['dataCadastro'] = date('Y-m-d H:i:s');
        $data['dataAlteracao'] = date('Y-m-d H:i:s');

        if ($this->db->insert('clientes', $data)) {
            return $this->db->insert_id();
        }

        return false;
    }

    /**
     * Atualiza cliente
     */
    public function edit($id, $data)
    {
        $data['dataAlteracao'] = date('Y-m-d H:i:s');

        $this->db->where('id', $id);
        return $this->db->update('clientes', $data);
    }

    /**
     * Remove cliente
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('clientes');
    }

    /**
     * Conta total de clientes
     */
    public function count($filtros = array())
    {
        if (isset($filtros['ativo'])) {
            $this->db->where('ativo', $filtros['ativo']);
        }

        return $this->db->count_all_results('clientes');
    }
}

