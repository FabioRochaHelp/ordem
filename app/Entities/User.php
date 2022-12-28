<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Libraries\Token;

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

    /**
     * Método que verifica se o usuário logado tem permissão para determinada rota.
     * 
     * 
     * @param string $password
     * @return boolean
     */

    public function permissionFor(string $prermission):bool{
        if($this->is_admin == true){
            return true;
        }

        if(empty($this->permissions)){
            return false;
        }

        if(in_array($prermission, $this->permissions) ==  false){
            return false;
        }

        return true;

    }

    /**
     * Método que inicia a recuperação de senha
     * 
     * @return void
     */
    public function initPasswordReset() : void {
        $token = new Token();

        $this->reset_token = $token->getValue();

        $this->reset_hash = $token->getHash();

        $this->reset_expire_at = date('Y-m-d H:i:s', time() + 7200);

    }
}