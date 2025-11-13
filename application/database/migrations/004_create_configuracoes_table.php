<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Criar tabela configuracoes
 */
class Migration_Create_configuracoes_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'idConfiguracao' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'nome' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => TRUE
            ),
            'valor' => array(
                'type' => 'TEXT',
                'null' => TRUE
            )
        ));

        $this->dbforge->add_key('idConfiguracao', TRUE);
        $this->dbforge->add_key('nome');
        $this->dbforge->create_table('configuracoes');
    }

    public function down()
    {
        $this->dbforge->drop_table('configuracoes');
    }
}

