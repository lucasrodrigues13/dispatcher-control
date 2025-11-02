<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'description' => 'Administrador com acesso total ao sistema',
            ],
            [
                'name' => 'Dispatcher',
                'description' => 'Despachante que gerencia cargas e transportadoras',
            ],
            [
                'name' => 'Carrier',
                'description' => 'Transportadora que recebe e gerencia cargas',
            ],
            [
                'name' => 'Broker',
                'description' => 'Corretor que intermedia cargas',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }

        $this->command->info('Roles criadas com sucesso!');
    }
}

