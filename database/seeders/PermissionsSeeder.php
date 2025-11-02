<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Permissões de usuários
            ['name' => 'users.index', 'description' => 'Ver lista de usuários'],
            ['name' => 'users.create', 'description' => 'Criar usuários'],
            ['name' => 'users.edit', 'description' => 'Editar usuários'],
            ['name' => 'users.delete', 'description' => 'Deletar usuários'],
            ['name' => 'users.view', 'description' => 'Visualizar detalhes de usuários'],
            
            // Permissões de cargas (loads)
            ['name' => 'loads.index', 'description' => 'Ver lista de cargas'],
            ['name' => 'loads.create', 'description' => 'Criar cargas'],
            ['name' => 'loads.edit', 'description' => 'Editar cargas'],
            ['name' => 'loads.delete', 'description' => 'Deletar cargas'],
            ['name' => 'loads.view', 'description' => 'Visualizar detalhes de cargas'],
            
            // Permissões de transportadoras (carriers)
            ['name' => 'carriers.index', 'description' => 'Ver lista de transportadoras'],
            ['name' => 'carriers.create', 'description' => 'Criar transportadoras'],
            ['name' => 'carriers.edit', 'description' => 'Editar transportadoras'],
            ['name' => 'carriers.delete', 'description' => 'Deletar transportadoras'],
            ['name' => 'carriers.view', 'description' => 'Visualizar detalhes de transportadoras'],
            
            // Permissões de clientes (customers)
            ['name' => 'customers.index', 'description' => 'Ver lista de clientes'],
            ['name' => 'customers.create', 'description' => 'Criar clientes'],
            ['name' => 'customers.edit', 'description' => 'Editar clientes'],
            ['name' => 'customers.delete', 'description' => 'Deletar clientes'],
            ['name' => 'customers.view', 'description' => 'Visualizar detalhes de clientes'],
            
            // Permissões de funcionários (employees)
            ['name' => 'employees.index', 'description' => 'Ver lista de funcionários'],
            ['name' => 'employees.create', 'description' => 'Criar funcionários'],
            ['name' => 'employees.edit', 'description' => 'Editar funcionários'],
            ['name' => 'employees.delete', 'description' => 'Deletar funcionários'],
            ['name' => 'employees.view', 'description' => 'Visualizar detalhes de funcionários'],
            
            // Permissões de motoristas (drivers)
            ['name' => 'drivers.index', 'description' => 'Ver lista de motoristas'],
            ['name' => 'drivers.create', 'description' => 'Criar motoristas'],
            ['name' => 'drivers.edit', 'description' => 'Editar motoristas'],
            ['name' => 'drivers.delete', 'description' => 'Deletar motoristas'],
            ['name' => 'drivers.view', 'description' => 'Visualizar detalhes de motoristas'],
            
            // Permissões de corretores (brokers)
            ['name' => 'brokers.index', 'description' => 'Ver lista de corretores'],
            ['name' => 'brokers.create', 'description' => 'Criar corretores'],
            ['name' => 'brokers.edit', 'description' => 'Editar corretores'],
            ['name' => 'brokers.delete', 'description' => 'Deletar corretores'],
            ['name' => 'brokers.view', 'description' => 'Visualizar detalhes de corretores'],
            
            // Permissões de faturas (invoices)
            ['name' => 'invoices.index', 'description' => 'Ver lista de faturas'],
            ['name' => 'invoices.create', 'description' => 'Criar faturas'],
            ['name' => 'invoices.edit', 'description' => 'Editar faturas'],
            ['name' => 'invoices.delete', 'description' => 'Deletar faturas'],
            ['name' => 'invoices.view', 'description' => 'Visualizar detalhes de faturas'],
            
            // Permissões de relatórios
            ['name' => 'reports.index', 'description' => 'Ver relatórios'],
            ['name' => 'reports.view', 'description' => 'Visualizar relatórios'],
            ['name' => 'reports.export', 'description' => 'Exportar relatórios'],
            
            // Permissões de dashboard
            ['name' => 'dashboard.view', 'description' => 'Visualizar dashboard'],
            
            // Permissões administrativas
            ['name' => 'admin.access', 'description' => 'Acesso à área administrativa'],
            ['name' => 'admin.roles', 'description' => 'Gerenciar roles'],
            ['name' => 'admin.permissions', 'description' => 'Gerenciar permissões'],
            ['name' => 'admin.settings', 'description' => 'Gerenciar configurações do sistema'],
            
            // Permissões de assinaturas
            ['name' => 'subscriptions.view', 'description' => 'Visualizar assinaturas'],
            ['name' => 'subscriptions.manage', 'description' => 'Gerenciar assinaturas'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                ['description' => $permission['description']]
            );
        }

        $this->command->info('Permissions criadas com sucesso!');
    }
}

