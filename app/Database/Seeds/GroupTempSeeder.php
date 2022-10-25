<?php

namespace App\Database\Seeds;

use App\Models\GroupsModel;
use CodeIgniter\Database\Seeder;
use CodeIgniter\Model;
use Config\App;

class GroupTempSeeder extends Seeder
{
    public function run()
    {
        
        $groupModel = new \App\Models\GroupsModel();

        $groups = [
                [
                    'groupname' => 'Administrador',
                    'description' => 'Grupo com acesso total ao sistema',
                    'disposition' => false,
                ],
                [
                    'groupname' => 'Clientes',
                    'description' => 'Esse grupo é destinado para atribuição de clientes, pois eles poderão logar no sistema para acessar as suas Ordens de Serviços.',
                    'disposition' => false,
                ],
                [
                    'groupname' => 'Atendentes',
                    'description' => 'Esse grupo acessa o sistema para realizar atendimento aos clientes.',
                    'disposition' => true,
                ],
            ];
        
            foreach ($groups as $group) {
                $groupModel->insert($group);
            }

            echo "Grupos criados com sucesso";
        

    }
}