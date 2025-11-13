<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model de Configurações
 * 
 * Carrega configurações do sistema do banco de dados
 */
class Configuracao_model extends CI_Model {

    protected $configuracoes = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Carrega todas as configurações do banco
     * 
     * @return void
     */
    public function loadConfiguracoes()
    {
        $query = $this->db->get('configuracoes');
        $result = $query->result();

        foreach ($result as $config) {
            $this->configuracoes[$config->nome] = $config->valor;
        }
    }

    /**
     * Obtém valor de uma configuração
     * 
     * @param string $nome
     * @param mixed $default
     * @return mixed
     */
    public function get($nome, $default = null)
    {
        return isset($this->configuracoes[$nome]) ? $this->configuracoes[$nome] : $default;
    }

    /**
     * Define valor de uma configuração
     * 
     * @param string $nome
     * @param mixed $valor
     * @return bool
     */
    public function set($nome, $valor)
    {
        $data = array(
            'nome' => $nome,
            'valor' => $valor
        );

        // Verificar se já existe
        $exists = $this->db->where('nome', $nome)->get('configuracoes')->row();

        if ($exists) {
            $this->db->where('nome', $nome);
            return $this->db->update('configuracoes', $data);
        } else {
            return $this->db->insert('configuracoes', $data);
        }
    }

    /**
     * Retorna todas as configurações
     * 
     * @return array
     */
    public function getAll()
    {
        return $this->configuracoes;
    }
}

