<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Advogado - Dashboard e funcionalidades
 */
class Advogado extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Processo_model');
    }

    /**
     * Dashboard do advogado
     */
    public function index()
    {
        // Buscar advogado do usuário logado
        $this->load->model('Advogado_model');
        $advogado = $this->Advogado_model->getById($this->usuario->id);

        if (!$advogado) {
            show_error('Advogado não encontrado', 404);
        }

        $filtros = array('advogado_id' => $advogado->id);
        
        $data = array(
            'total_processos' => $this->Processo_model->count($filtros),
            'processos_recentes' => $this->Processo_model->getAll($filtros, 10)
        );

        $this->layout('advogado/dashboard', $data);
    }

    /**
     * Lista processos do advogado
     */
    public function processos()
    {
        $this->load->model('Advogado_model');
        $advogado = $this->Advogado_model->getById($this->usuario->id);

        if (!$advogado) {
            show_error('Advogado não encontrado', 404);
        }

        $filtros = array('advogado_id' => $advogado->id);

        $this->load->library('pagination');
        $config['base_url'] = base_url('advogado/processos');
        $config['total_rows'] = $this->Processo_model->count($filtros);
        $config['per_page'] = 15;
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data = array(
            'processos' => $this->Processo_model->getAll($filtros, $config['per_page'], $page),
            'links' => $this->pagination->create_links()
        );

        $this->layout('advogado/processos/index', $data);
    }
}

