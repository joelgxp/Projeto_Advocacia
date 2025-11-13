<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@advocacia.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('123456'),
                'cpf' => '000.000.000-00',
                'ativo' => true,
            ]
        );
        $admin->assignRole('admin');

        // Criar usuário advogado de exemplo
        $advogado = User::firstOrCreate(
            ['email' => 'advogado@advocacia.com'],
            [
                'name' => 'Advogado Teste',
                'password' => Hash::make('123456'),
                'cpf' => '000.000.000-10',
                'telefone' => '(00) 00000-0000',
                'ativo' => true,
            ]
        );
        $advogado->assignRole('advogado');

        // Criar usuário recepcionista
        $recepcao = User::firstOrCreate(
            ['email' => 'recepcao@advocacia.com'],
            [
                'name' => 'Recepcionista',
                'password' => Hash::make('123456'),
                'cpf' => '444.444.444-44',
                'telefone' => '(22) 22222-2222',
                'ativo' => true,
            ]
        );
        $recepcao->assignRole('recepcionista');

        // Criar usuário tesoureiro
        $tesoureiro = User::firstOrCreate(
            ['email' => 'tesoureiro@advocacia.com'],
            [
                'name' => 'Tesoureiro',
                'password' => Hash::make('123456'),
                'cpf' => '111.111.111-11',
                'telefone' => '(11) 11111-1111',
                'ativo' => true,
            ]
        );
        $tesoureiro->assignRole('tesoureiro');
    }
}





