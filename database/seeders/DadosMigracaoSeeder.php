<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Processo;
use App\Models\Advogado;
use App\Models\Vara;
use App\Models\Especialidade;
use Spatie\Permission\Models\Role;

class DadosMigracaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Este seeder migra os dados do banco antigo para o novo formato Laravel
     * Execute apenas uma vez após a migração das tabelas
     */
    public function run(): void
    {
        // Verificar se a tabela antiga existe
        if (!DB::getSchemaBuilder()->hasTable('usuarios')) {
            $this->command->info('Tabela usuarios não encontrada. Pulando migração de dados.');
            return;
        }

        $this->command->info('Iniciando migração de dados...');

        // Migrar usuários
        $this->migrarUsuarios();
        
        // Migrar clientes
        $this->migrarClientes();
        
        // Migrar varas
        $this->migrarVaras();
        
        // Migrar especialidades
        $this->migrarEspecialidades();
        
        // Migrar advogados
        $this->migrarAdvogados();
        
        // Migrar processos
        $this->migrarProcessos();

        $this->command->info('Migração de dados concluída!');
    }

    private function migrarUsuarios()
    {
        $this->command->info('Migrando usuários...');
        
        $usuariosAntigos = DB::table('usuarios')->get();
        
        foreach ($usuariosAntigos as $usuarioAntigo) {
            // Mapear nivel para role
            $roleMap = [
                'admin' => 'admin',
                'Advogado' => 'advogado',
                'Recepcionista' => 'recepcionista',
                'Tesoureiro' => 'tesoureiro',
                'Cliente' => 'cliente',
            ];
            
            $roleName = $roleMap[$usuarioAntigo->nivel] ?? 'cliente';
            
            // Converter senha MD5 para bcrypt
            // Como não temos a senha original, vamos criar uma nova ou manter a hash
            // Na prática, o usuário precisará redefinir a senha
            $password = Hash::make('123456'); // Senha padrão temporária
            
            $user = User::firstOrCreate(
                ['email' => $usuarioAntigo->usuario],
                [
                    'name' => $usuarioAntigo->nome,
                    'password' => $password,
                    'cpf' => $usuarioAntigo->cpf,
                    'ativo' => true,
                ]
            );
            
            if ($user->wasRecentlyCreated) {
                $role = Role::where('name', $roleName)->first();
                if ($role) {
                    $user->assignRole($role);
                }
            }
        }
    }

    private function migrarClientes()
    {
        $this->command->info('Migrando clientes...');
        
        if (!DB::getSchemaBuilder()->hasTable('clientes_old')) {
            return;
        }
        
        $clientesAntigos = DB::table('clientes_old')->get();
        
        foreach ($clientesAntigos as $clienteAntigo) {
            Cliente::firstOrCreate(
                ['cpf_cnpj' => $clienteAntigo->cpf],
                [
                    'nome' => $clienteAntigo->nome,
                    'tipo_pessoa' => $clienteAntigo->tipo_pessoa == 'Pessoa Física' ? 'PF' : 'PJ',
                    'email' => $clienteAntigo->email,
                    'telefone' => $clienteAntigo->telefone,
                    'endereco' => $clienteAntigo->endereco,
                    'observacoes' => $clienteAntigo->obs,
                    'ativo' => true,
                ]
            );
        }
    }

    private function migrarVaras()
    {
        $this->command->info('Migrando varas...');
        
        if (!DB::getSchemaBuilder()->hasTable('varas_old')) {
            return;
        }
        
        $varasAntigas = DB::table('varas_old')->get();
        
        foreach ($varasAntigas as $varaAntiga) {
            Vara::firstOrCreate(
                ['nome' => $varaAntiga->nome],
                ['ativo' => true]
            );
        }
    }

    private function migrarEspecialidades()
    {
        $this->command->info('Migrando especialidades...');
        
        if (!DB::getSchemaBuilder()->hasTable('especialidades_old')) {
            return;
        }
        
        $especialidadesAntigas = DB::table('especialidades_old')->get();
        
        foreach ($especialidadesAntigas as $especialidadeAntiga) {
            Especialidade::firstOrCreate(
                ['nome' => $especialidadeAntiga->nome],
                ['ativo' => true]
            );
        }
    }

    private function migrarAdvogados()
    {
        $this->command->info('Migrando advogados...');
        
        if (!DB::getSchemaBuilder()->hasTable('advogados_old')) {
            return;
        }
        
        $advogadosAntigos = DB::table('advogados_old')->get();
        
        foreach ($advogadosAntigos as $advogadoAntigo) {
            $user = User::where('cpf', $advogadoAntigo->cpf)->first();
            
            if ($user) {
                Advogado::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'foto' => $advogadoAntigo->foto,
                        'biografia' => $advogadoAntigo->biografia,
                        'ativo' => true,
                    ]
                );
            }
        }
    }

    private function migrarProcessos()
    {
        $this->command->info('Migrando processos...');
        
        if (!DB::getSchemaBuilder()->hasTable('processos_old')) {
            return;
        }
        
        $processosAntigos = DB::table('processos_old')->get();
        
        foreach ($processosAntigos as $processoAntigo) {
            $cliente = Cliente::where('cpf_cnpj', $processoAntigo->cliente)->first();
            $advogado = Advogado::whereHas('user', function($query) use ($processoAntigo) {
                $query->where('cpf', $processoAntigo->advogado);
            })->first();
            $vara = Vara::find($processoAntigo->vara);
            $especialidade = Especialidade::find($processoAntigo->tipo_acao);
            
            if ($cliente && $advogado && $vara && $especialidade) {
                Processo::firstOrCreate(
                    ['numero_processo' => $processoAntigo->num_processo],
                    [
                        'cliente_id' => $cliente->id,
                        'advogado_id' => $advogado->id,
                        'vara_id' => $vara->id,
                        'especialidade_id' => $especialidade->id,
                        'status' => strtolower($processoAntigo->status),
                        'data_abertura' => $processoAntigo->data_abertura,
                        'data_peticao' => $processoAntigo->data_peticao,
                        'data_audiencia' => $processoAntigo->data_audiencia,
                        'hora_audiencia' => $processoAntigo->hora_audiencia,
                        'quantidade_audiencias' => $processoAntigo->audiencias,
                        'observacoes' => $processoAntigo->obs,
                    ]
                );
            }
        }
    }
}




