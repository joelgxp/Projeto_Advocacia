<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Criar roles
        $roles = [
            'admin',
            'advogado',
            'recepcionista',
            'tesoureiro',
            'cliente',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Criar permissions básicas
        $permissions = [
            'processos.view',
            'processos.create',
            'processos.edit',
            'processos.delete',
            'clientes.view',
            'clientes.create',
            'clientes.edit',
            'clientes.delete',
            'documentos.view',
            'documentos.create',
            'documentos.edit',
            'documentos.delete',
            'audiencias.view',
            'audiencias.create',
            'audiencias.edit',
            'audiencias.delete',
            'financeiro.view',
            'financeiro.create',
            'financeiro.edit',
            'financeiro.delete',
            'admin.access',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Atribuir permissions às roles
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo(Permission::all());

        $advogadoRole = Role::findByName('advogado');
        $advogadoRole->givePermissionTo([
            'processos.view',
            'processos.create',
            'processos.edit',
            'clientes.view',
            'clientes.create',
            'clientes.edit',
            'documentos.view',
            'documentos.create',
            'documentos.edit',
            'audiencias.view',
            'audiencias.create',
            'audiencias.edit',
        ]);

        $recepcaoRole = Role::findByName('recepcionista');
        $recepcaoRole->givePermissionTo([
            'processos.view',
            'clientes.view',
            'clientes.create',
            'clientes.edit',
            'audiencias.view',
            'audiencias.create',
            'audiencias.edit',
        ]);

        $tesoureiroRole = Role::findByName('tesoureiro');
        $tesoureiroRole->givePermissionTo([
            'processos.view',
            'clientes.view',
            'financeiro.view',
            'financeiro.create',
            'financeiro.edit',
        ]);

        $clienteRole = Role::findByName('cliente');
        $clienteRole->givePermissionTo([
            'processos.view',
            'documentos.view',
        ]);
    }
}




