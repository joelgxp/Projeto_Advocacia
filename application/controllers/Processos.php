<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller de Processos (Admin)
 */
class Processos extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Processo_model');
        $this->load->model('Cliente_model');
        $this->load->model('Advogado_model');
        $this->load->model('Vara_model');
        $this->load->model('Especialidade_model');
    }

    /**
     * Lista processos
     */
    public function index()
    {
        $filtros = array();
        
        if ($this->input->get('cliente_id')) {
            $filtros['cliente_id'] = $this->input->get('cliente_id');
        }

        if ($this->input->get('advogado_id')) {
            $filtros['advogado_id'] = $this->input->get('advogado_id');
        }

        if ($this->input->get('status')) {
            $filtros['status'] = $this->input->get('status');
        }

        // Paginação
        $this->load->library('pagination');
        $config['base_url'] = base_url('processos');
        $config['total_rows'] = $this->Processo_model->count($filtros);
        $config['per_page'] = 15;
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        
        $data = array(
            'processos' => $this->Processo_model->getAll($filtros, $config['per_page'], $page),
            'links' => $this->pagination->create_links()
        );

        $this->layout('admin/processos/index', $data);
    }

    /**
     * Visualiza processo
     */
    public function visualizar($id)
    {
        $processo = $this->Processo_model->getById($id);
        
        if (!$processo) {
            show_404();
        }

        $this->load->model('Documento_model');
        $this->load->model('Audiencia_model');
        $this->load->model('Prazo_model');

        $data = array(
            'processo' => $processo,
            'documentos' => $this->Documento_model->getAll(array('processo_id' => $id)),
            'audiencias' => $this->Audiencia_model->getAll(array('processo_id' => $id)),
            'prazos' => $this->Prazo_model->getAll(array('processo_id' => $id))
        );

        $this->layout('admin/processos/visualizar', $data);
    }

    /**
     * Formulário de adicionar processo
     */
    public function adicionar()
    {
        $data = array(
            'clientes' => $this->Cliente_model->getAll(array('ativo' => 1)),
            'advogados' => $this->Advogado_model->getAll(array('ativo' => 1)),
            'varas' => $this->Vara_model->getAll(array('ativo' => 1)),
            'especialidades' => $this->Especialidade_model->getAll(array('ativo' => 1))
        );

        $this->layout('admin/processos/adicionar', $data);
    }

    /**
     * Salva novo processo
     */
    public function salvar()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('numero_processo', 'Número do Processo', 'required');
        $this->form_validation->set_rules('cliente_id', 'Cliente', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->adicionar();
            return;
        }

        $data = array(
            'numero_processo' => $this->input->post('numero_processo'),
            'cliente_id' => $this->input->post('cliente_id'),
            'advogado_id' => $this->input->post('advogado_id'),
            'vara_id' => $this->input->post('vara_id'),
            'especialidade_id' => $this->input->post('especialidade_id'),
            'status' => $this->input->post('status') ?: 'aberto',
            'data_abertura' => $this->input->post('data_abertura'),
            'observacoes' => $this->input->post('observacoes')
        );

        if ($this->Processo_model->add($data)) {
            $this->session->set_flashdata('success', 'Processo cadastrado com sucesso!');
            redirect('processos');
        } else {
            $this->session->set_flashdata('error', 'Erro ao cadastrar processo.');
            $this->adicionar();
        }
    }

    /**
     * Formulário de editar processo
     */
    public function editar($id)
    {
        $processo = $this->Processo_model->getById($id);
        
        if (!$processo) {
            show_404();
        }

        $data = array(
            'processo' => $processo,
            'clientes' => $this->Cliente_model->getAll(array('ativo' => 1)),
            'advogados' => $this->Advogado_model->getAll(array('ativo' => 1)),
            'varas' => $this->Vara_model->getAll(array('ativo' => 1)),
            'especialidades' => $this->Especialidade_model->getAll(array('ativo' => 1))
        );

        $this->layout('admin/processos/editar', $data);
    }

    /**
     * Atualiza processo
     */
    public function atualizar($id)
    {
        $processo = $this->Processo_model->getById($id);
        
        if (!$processo) {
            show_404();
        }

        $data = array(
            'numero_processo' => $this->input->post('numero_processo'),
            'cliente_id' => $this->input->post('cliente_id'),
            'advogado_id' => $this->input->post('advogado_id'),
            'vara_id' => $this->input->post('vara_id'),
            'especialidade_id' => $this->input->post('especialidade_id'),
            'status' => $this->input->post('status'),
            'data_abertura' => $this->input->post('data_abertura'),
            'observacoes' => $this->input->post('observacoes')
        );

        if ($this->Processo_model->edit($id, $data)) {
            $this->session->set_flashdata('success', 'Processo atualizado com sucesso!');
            redirect('processos');
        } else {
            $this->session->set_flashdata('error', 'Erro ao atualizar processo.');
            $this->editar($id);
        }
    }

    /**
     * Remove processo
     */
    public function excluir($id)
    {
        if ($this->Processo_model->delete($id)) {
            $this->session->set_flashdata('success', 'Processo excluído com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao excluir processo.');
        }

        redirect('processos');
    }
}

