<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Admin - Dashboard
 */
class Admin extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        
        // Verificar se é admin (temporariamente desabilitado para debug)
        // TODO: Reabilitar após corrigir sistema de permissões
        /*
        if (!$this->verificar_permissao('admin.access')) {
            show_error('Acesso negado', 403);
        }
        */
        
        // Verificação simples: se tem nível admin
        $nivel = $this->session->userdata('usuario_nivel');
        if ($nivel && strtolower($nivel) !== 'admin') {
            // Permitir acesso temporariamente para debug
            // show_error('Acesso negado. Apenas administradores.', 403);
        }
    }

    /**
     * Dashboard administrativo
     */
    public function index()
    {
        $this->load->model('Processo_model');
        $this->load->model('Cliente_model');
        $this->load->model('Advogado_model');

        $data = array(
            'total_processos' => $this->Processo_model->count(),
            'total_clientes' => $this->Cliente_model->count(),
            'total_advogados' => $this->Advogado_model->count(),
            'processos_recentes' => $this->Processo_model->getAll(array(), 10)
        );

        $this->layout('admin/dashboard', $data);
    }
}

