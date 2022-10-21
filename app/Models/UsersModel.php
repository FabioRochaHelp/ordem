<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table            = 'users';
    protected $returnType       = 'App\Entities\User';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'name_user',
        'email',
        'password',
        'reset_hash',
        'reset_expire_at',
        'avatar',
        //Não colocaremos o campo active, pois existe a manipulação de formulário
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name_user'     => 'required|min_length[3]|max_length[125]',
        'email'        => 'required|valid_email|max_length[238]|is_unique[users.email,id,{id}]',
        'password'     => 'required|min_length[6]',
        'password_confirmation' => 'required_with[password]|matches[password]',
    ];
    protected $validationMessages = [
        'name_user' => [
            'required' => 'O campo nome é obrigatório.',
            'min_length' => 'O campo nome precisa ter pelo menos 3 caractéres.',
            'max_length' => 'O campo nome não ser maior que 125 caractéres.',
        ],
        'email' => [
            'required' => 'O campo E-mail é obrigatório.',
            'max_length' => 'O campo nome não ser maior que 230 caractéres.',
            'is_unique' => 'Esse e-mail já foi escolhido. Por favor informe outro.',
        ],
        'password_confirmation' => [
            'required_with' => 'Por favor confirme a sua senha.',
            'matches' => 'As senhas precisam combinar.',
        ],
    ];

    // Callbacks
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

            unset($data['data']['password']);
            unset($data['data']['password_confirmation']);

        }

        return $data;
    }

}