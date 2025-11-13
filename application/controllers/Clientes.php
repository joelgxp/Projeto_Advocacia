<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller de Clientes (Admin)
 */
class Clientes extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Cliente_model');
    }

    public function index()
    {
        $filtros = array();
        
        if ($this->input->get('ativo')) {
            $filtros['ativo'] = $this->input->get('ativo');
        }

        if ($this->input->get('nome')) {
            $filtros['nome'] = $this->input->get('nome');
        }

        $this->load->library('pagination');
        $config['base_url'] = base_url('clientes');
        $config['total_rows'] = $this->Cliente_model->count($filtros);
        $config['per_page'] = 15;
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        
        $data = array(
            'clientes' => $this->Cliente_model->getAll($filtros, $config['per_page'], $page),
            'links' => $this->pagination->create_links()
        );

        $this->layout('admin/clientes/index', $data);
    }

    public function adicionar()
    {
        $this->layout('admin/clientes/adicionar');
    }

    public function salvar()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('cpf_cnpj', 'CPF/CNPJ', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->adicionar();
            return;
        }

        $data = $this->input->post();
        
        if ($this->Cliente_model->add($data)) {
            $this->session->set_flashdata('success', 'Cliente cadastrado com sucesso!');
            redirect('clientes');
        } else {
            $this->session->set_flashdata('error', 'Erro ao cadastrar cliente.');
            $this->adicionar();
        }
    }

    public function editar($id)
    {
        $cliente = $this->Cliente_model->getById($id);
        
        if (!$cliente) {
            show_404();
        }

        $data = array('cliente' => $cliente);
        $this->layout('admin/clientes/editar', $data);
    }

    public function atualizar($id)
    {
        $data = $this->input->post();
        
        if ($this->Cliente_model->edit($id, $data)) {
            $this->session->set_flashdata('success', 'Cliente atualizado com sucesso!');
            redirect('clientes');
        } else {
            $this->session->set_flashdata('error', 'Erro ao atualizar cliente.');
            $this->editar($id);
        }
    }

    public function excluir($id)
    {
        if ($this->Cliente_model->delete($id)) {
            $this->session->set_flashdata('success', 'Cliente excluído com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao excluir cliente.');
        }

        redirect('clientes');
    }
}

