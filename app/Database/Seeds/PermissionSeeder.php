<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissionsModel = new \App\Models\PermissionsModel();

        $permissions = [
            [
                'permission' => 'listar-usuarios',
            ],
            [
                'permission' => 'criar-usuarios',
            ],
            [
                'permission' => 'editar-usuarios',
            ],
            [
                'permission' => 'excluir-usuarios',
            ],

        ];

        foreach ($permissions as $permission) {
            $permissionsModel->protect(false)->insert($permission);
        }

        echo 'Permissões criadas com sucesso.';
    }
}