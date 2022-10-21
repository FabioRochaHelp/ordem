<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersFakerSeeder extends Seeder
{
    public function run()
    {
        $usersModel = new \App\Models\UsersModel();

        $faker = \Faker\Factory::create();

        $createUsers = 10000;
        $usersPush = [];

        for ($i=0; $i < $createUsers; $i++) { 

            array_push($usersPush, [
                'name_user' => $faker->unique()->name,
                'email' => $faker->unique()->email,
                'password_hash' => '123456',
                'active' => $faker->numberBetween(0,1),
            ]);

        }

        $usersModel->skipValidation(true) //bypass na validação
                   ->protect(false) // bypass nas proteção dos campos allowedFields
                   ->insertBatch($usersPush); 

        echo "$createUsers usuários criados com sucesso!";
    }
}
