<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Criar tabela permissoes
 */
class Migration_Create_permissoes_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'idPermissoes' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'nome' => array(
                'type' => 'VARCHAR',
                'constraint' => '50'
            ),
            'permissoes' => array(
                'type' => 'TEXT'
            ),
            'situacao' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            )
        ));

        $this->dbforge->add_key('idPermissoes', TRUE);
        $this->dbforge->create_table('permissoes');
    }

    public function down()
    {
        $this->dbforge->drop_table('permissoes');
    }
}

