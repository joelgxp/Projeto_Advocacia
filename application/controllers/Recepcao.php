<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Recepcao - Dashboard
 */
class Recepcao extends MY_Controller {

    public function index()
    {
        $this->load->model('Processo_model');
        $this->load->model('Cliente_model');

        $data = array(
            'total_processos' => $this->Processo_model->count(),
            'total_clientes' => $this->Cliente_model->count()
        );

        $this->layout('recepcao/dashboard', $data);
    }
}

