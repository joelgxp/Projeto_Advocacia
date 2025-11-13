<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base Controller
 * 
 * Controller base que verifica autenticação e carrega configurações
 * Baseado no padrão do MapOS
 */
class MY_Controller extends CI_Controller {

    protected $data = array();
    protected $usuario = null;

    public function __construct()
    {
        parent::__construct();
        
        // Carregar configurações do sistema (se tabela existir)
        try {
            $this->load->model('Configuracao_model');
            if ($this->db->table_exists('configuracoes')) {
                $this->Configuracao_model->loadConfiguracoes();
            }
        } catch (Exception $e) {
            // Ignorar erro se tabela não existir
            log_message('debug', 'Tabela configuracoes não encontrada: ' . $e->getMessage());
        }
        
        // Verificar autenticação (exceto para Login)
        if ($this->router->class !== 'Login') {
            $this->verificar_autenticacao();
        }
        
        // Carregar biblioteca de permissões (se necessário)
        try {
            $this->load->library('Permission');
        } catch (Exception $e) {
            log_message('error', 'Erro ao carregar biblioteca Permission: ' . $e->getMessage());
        }
        
        // Dados padrão para views
        $this->data['base_url'] = base_url();
        $this->data['site_url'] = site_url();
        $this->data['usuario'] = $this->usuario;
    }

    /**
     * Verifica se o usuário está autenticado
     */
    protected function verificar_autenticacao()
    {
        $usuario_id = $this->session->userdata('usuario_id');
        
        if (!$usuario_id) {
            redirect('login');
        }
        
        // Carregar dados do usuário
        $this->load->model('Usuario_model');
        $this->usuario = $this->Usuario_model->getById($usuario_id);
        
        if (!$this->usuario) {
            $this->session->sess_destroy();
            redirect('login');
        }
        
        $this->data['usuario'] = $this->usuario;
    }

    /**
     * Renderiza layout padrão
     * 
     * @param string $view Nome da view
     * @param array $data Dados adicionais
     */
    protected function layout($view, $data = array())
    {
        $this->data = array_merge($this->data, $data);
        $this->data['view'] = $view;
        
        $this->load->view('tema/topo', $this->data);
        $this->load->view('tema/menu', $this->data);
        $this->load->view('tema/conteudo', $this->data);
        $this->load->view('tema/rodape', $this->data);
    }

    /**
     * Verifica permissão do usuário
     * 
     * @param string $permissao Nome da permissão
     * @return bool
     */
    protected function verificar_permissao($permissao)
    {
        if (!$this->usuario) {
            return false;
        }
        
        return $this->permission->check($this->usuario->permissoes_id, $permissao);
    }
}

