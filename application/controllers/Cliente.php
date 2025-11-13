<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Cliente - Área do cliente
 */
class Cliente extends MY_Controller {

    public function index()
    {
        // Buscar cliente do usuário logado
        $this->load->model('Cliente_model');
        $cliente = $this->Cliente_model->getByCpfCnpj($this->usuario->cpf);

        if (!$cliente) {
            show_error('Cliente não encontrado', 404);
        }

        $this->load->model('Processo_model');
        $filtros = array('cliente_id' => $cliente->id);

        $data = array(
            'cliente' => $cliente,
            'total_processos' => $this->Processo_model->count($filtros),
            'processos' => $this->Processo_model->getAll($filtros, 10)
        );

        $this->layout('cliente/dashboard', $data);
    }

    public function processos()
    {
        $this->load->model('Cliente_model');
        $cliente = $this->Cliente_model->getByCpfCnpj($this->usuario->cpf);

        if (!$cliente) {
            show_error('Cliente não encontrado', 404);
        }

        $this->load->model('Processo_model');
        $filtros = array('cliente_id' => $cliente->id);

        $data = array(
            'processos' => $this->Processo_model->getAll($filtros)
        );

        $this->layout('cliente/processos/index', $data);
    }
}

