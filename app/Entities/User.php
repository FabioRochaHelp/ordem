<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class User extends Entity
{
    protected $dates   = [
        'created_at', 
        'updated_at', 
        'deleted_at'
    ];

    public function getSituation(){

        if($this->deleted_at != null){
            $icon = '<span class="text-white"></span>&nbsp;<i class="fa fa-undo"></i>&nbsp;Restaurar';

            $situation = anchor("users/restoreDelete/$this->id", $icon, ['class' => 'btn btn-outline-danger btn-sm']);

            return $situation;
        }

        // ($user->active == true ? '<span class="text-success"><i class="fa fa-unlock"></i></span>&nbsp;Ativo' : '<span class="text-warning"><i class="fa fa-lock"></i></span>&nbsp;Inativo')

        if($this->active == true){
            return '<i class="fa fa-unlock text-success"></i></span>&nbsp;Ativo';
        }else{
            return '<i class="fa fa-lock text-warning"></i></span>&nbsp;Inativo';
        }

    }

    /**
     * Método que verifica se a senha é válida
     * 
     * 
     * @param string $password
     * @return boolean
     */
    public function verifyPassword(string $password):bool{

        return password_verify($password, $this->password_hash);

    }
}