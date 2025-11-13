<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller de Dashboard
 * 
 * Redireciona para dashboard específico baseado no role
 */
class Dashboard extends MY_Controller {

    public function index()
    {
        $permissoes_id = $this->usuario->permissoes_id;
        
        $this->load->model('Permissao_model');
        $permissoes = $this->Permissao_model->get($permissoes_id);
        
        if (!$permissoes) {
            redirect('login');
        }

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
                redirect('login');
        }
    }
}

