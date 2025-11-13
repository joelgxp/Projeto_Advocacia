<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller de Login
 * 
 * Gerencia autenticação de usuários
 */
class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usuario_model');
        $this->load->library('form_validation');
    }

    /**
     * Exibe formulário de login
     */
    public function index()
    {
        // Se já estiver logado, redireciona
        if ($this->session->userdata('usuario_id')) {
            $this->redirecionarPorRole();
        }

        $data['title'] = 'Login - Sistema de Advocacia';
        $this->load->view('auth/login', $data);
    }

    /**
     * Processa login
     */
    public function processar()
    {
        // Verificar CSRF manualmente se necessário
        if ($this->config->item('csrf_protection') === TRUE) {
            $this->security->csrf_verify();
        }
        
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('senha', 'Senha', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
            return;
        }

        $email = $this->input->post('email');
        $senha = $this->input->post('senha');

        $usuario = $this->Usuario_model->verificarLogin($email, $senha);

        if ($usuario) {
            // Criar sessão
            $session_data = array(
                'usuario_id' => $usuario->id,
                'usuario_nome' => $usuario->nome,
                'usuario_email' => $usuario->email,
                'permissoes_id' => $usuario->permissoes_id,
                'logged_in' => TRUE
            );

            $this->session->set_userdata($session_data);

            // Redirecionar baseado no role
            $this->redirecionarPorRole();
        } else {
            $this->session->set_flashdata('error', 'Email ou senha incorretos.');
            redirect('login');
        }
    }

    /**
     * Redireciona usuário baseado no role
     */
    private function redirecionarPorRole()
    {
        $permissoes_id = $this->session->userdata('permissoes_id');
        
        // Carregar grupo de permissões para identificar role
        $this->load->model('Permissao_model');
        $permissoes = $this->Permissao_model->get($permissoes_id);
        
        if (!$permissoes) {
            $this->logout();
            return;
        }

        // Mapear roles baseado no nome do grupo de permissões
        $role = strtolower($permissoes->nome);
        
        switch ($role) {
            case 'admin':
            case 'administrador':
                redirect('admin');
                break;
            case 'advogado':
                redirect('advogado');
                break;
            case 'recepcionista':
            case 'tesoureiro':
                redirect('recepcao');
                break;
            case 'cliente':
                redirect('cliente');
                break;
            default:
                redirect('dashboard');
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}

