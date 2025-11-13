<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Criar tabela usuarios
 * 
 * Adaptada do Laravel para CodeIgniter 3
 */
class Migration_Create_usuarios_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'nome' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => TRUE
            ),
            'senha' => array(
                'type' => 'VARCHAR',
                'constraint' => '255'
            ),
            'cpf' => array(
                'type' => 'VARCHAR',
                'constraint' => '14',
                'null' => TRUE
            ),
            'telefone' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ),
            'permissoes_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'ativo' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ),
            'dataCadastro' => array(
                'type' => 'DATETIME'
            ),
            'dataAlteracao' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            )
        ));

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('email');
        $this->dbforge->create_table('usuarios');
    }

    public function down()
    {
        $this->dbforge->drop_table('usuarios');
    }
}

