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
        try {
            // Log de debug
            log_message('debug', 'Login processar chamado. POST: ' . json_encode($this->input->post()));
            
            // Aceitar tanto 'email' quanto 'usuario' no formulário
            $this->form_validation->set_rules('usuario', 'Usuário/Email', 'required');
            $this->form_validation->set_rules('senha', 'Senha', 'required');

            if ($this->form_validation->run() == FALSE) {
                log_message('debug', 'Validação falhou: ' . validation_errors());
                $this->index();
                return;
            }

            // Tentar pegar 'usuario' primeiro, depois 'email' (compatibilidade)
            $usuario_input = $this->input->post('usuario') ?: $this->input->post('email');
            $senha = $this->input->post('senha');
            
            log_message('debug', 'Tentando login para: ' . $usuario_input);

            $usuario = $this->Usuario_model->verificarLogin($usuario_input, $senha);
            
            log_message('debug', 'Resultado verificarLogin: ' . ($usuario ? 'SUCESSO' : 'FALHOU'));

            if ($usuario) {
                // Criar sessão
                $session_data = array(
                    'usuario_id' => isset($usuario->id) ? $usuario->id : null,
                    'usuario_nome' => isset($usuario->nome) ? $usuario->nome : '',
                    'usuario_email' => isset($usuario->usuario) ? $usuario->usuario : (isset($usuario->email) ? $usuario->email : ''),
                    'usuario_nivel' => isset($usuario->nivel) ? strtolower($usuario->nivel) : null,
                    'permissoes_id' => isset($usuario->permissoes_id) ? $usuario->permissoes_id : null,
                    'logged_in' => TRUE
                );

                if (!$session_data['usuario_id']) {
                    log_message('error', 'Usuário sem ID válido após login');
                    throw new Exception('Usuário sem ID válido');
                }

                log_message('debug', 'Criando sessão: ' . json_encode($session_data));
                
                $this->session->set_userdata($session_data);
                
                log_message('debug', 'Sessão criada. Redirecionando...');

                // Redirecionar baseado no role
                $this->redirecionarPorRole();
            } else {
                log_message('warning', 'Login falhou para: ' . $usuario_input);
                $this->session->set_flashdata('error', 'Usuário ou senha incorretos.');
                redirect('login');
            }
        } catch (Exception $e) {
            log_message('error', 'Erro no login: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Erro ao processar login. Tente novamente.');
            redirect('login');
        }
    }

    /**
     * Redireciona usuário baseado no role
     */
    private function redirecionarPorRole()
    {
        try {
            // Primeiro tentar usar 'nivel' (campo direto da tabela)
            $nivel = $this->session->userdata('usuario_nivel');
            
            if ($nivel) {
                // Mapear roles baseado no campo 'nivel'
                $role = strtolower(trim($nivel));
                
                switch ($role) {
                    case 'admin':
                    case 'administrador':
                        redirect('admin');
                        return;
                    case 'advogado':
                        redirect('advogado');
                        return;
                    case 'recepcionista':
                    case 'tesoureiro':
                        redirect('recepcao');
                        return;
                    case 'cliente':
                        redirect('cliente');
                        return;
                }
            }
            
            // Se não tiver 'nivel', tentar usar 'permissoes_id' (sistema antigo)
            $permissoes_id = $this->session->userdata('permissoes_id');
            
            if ($permissoes_id) {
                // Carregar grupo de permissões para identificar role
                $this->load->model('Permissao_model');
                $permissoes = $this->Permissao_model->get($permissoes_id);
                
                if ($permissoes && isset($permissoes->nome)) {
                    $role = strtolower(trim($permissoes->nome));
                    
                    switch ($role) {
                        case 'admin':
                        case 'administrador':
                            redirect('admin');
                            return;
                        case 'advogado':
                            redirect('advogado');
                            return;
                        case 'recepcionista':
                        case 'tesoureiro':
                            redirect('recepcao');
                            return;
                        case 'cliente':
                            redirect('cliente');
                            return;
                    }
                }
            }
            
            // Se não encontrou nenhum, redireciona para dashboard genérico
            redirect('dashboard');
        } catch (Exception $e) {
            log_message('error', 'Erro ao redirecionar por role: ' . $e->getMessage());
            // Em caso de erro, redireciona para dashboard genérico
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

