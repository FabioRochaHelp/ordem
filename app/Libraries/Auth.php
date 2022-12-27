<?php

namespace App\Libraries;

class Auth{

    private $user;
    private $usersModel;
    private $groupsUsersModel;

    public function __construct()
    {
        $this->usersModel = new \App\Models\UsersModel();
        $this->groupsUsersModel = new \App\Models\GroupsUsersModel();
    }


    public function login(string $email, string $password): bool
    {
        //Recupera o usuário por email
        $user = $this->usersModel->getUserEmail($email);

        if($user === null){
            return false;
        }

        // Verificação se a senha é válida
        if($user->verifyPassword($password) == false){
            return false;
        }
        
        //Verificação se o usuário esta ativo
        if($user->active == false){
            return false;
        }

        $this->setUserSession($user);

        return true;


    }

    

    //Pega usuário logado
    public function getUserLogin(){
        if($this->user === null){
            $this->user = $this->getUserSession();
        }

        return $this->user;
    }

    //Verifica se o usuário esta logado
    public function verifyUserLogin(){
        return $this->getUserLogin() != null;
    }

    //---------------Métodos PRIVATE------------------//
 
    //Seta usuário na sessão
    private function setUserSession(object $user):void{

        $session = session();

        //Antes de inserirmos o ID do usuário na sessão, devemos gerar um novo ID da sessão
        $session->regenerate();

        //Setamos na sessão o ID do usuário
        $session->set('user_id', $user->id);
    }

    //Pega usuário da sessão
    private function getUserSession()
    {
        if(session()->has('user_id') == false){
            return null;
        }

        $user = $this->usersModel->find(session()->get('user_id'));

        if($user == null || $user->active == false){
            return null;
        }

        $user = $this->definePermissionUserLogin($user);

        return $user;
        
    }

    private function isAdmin():bool{
        $groupAdmin = 1;

        $admin = $this->groupsUsersModel->userInGroup($groupAdmin, session()->get('user_id'));

        if($admin == null){
            return false;
        }

        return true;

    }

    private function isClient():bool{
        $groupClient = 2;

        $client = $this->groupsUsersModel->userInGroup($groupClient, session()->get('user_id'));

        if($client == null){
            return false;
        }

        return true;

    }

    private function definePermissionUserLogin($user):object{
        $user->is_admin = $this->isAdmin();

        if($user->is_admin == true){
            $user->is_client = false;
        }else{
            $user->is_client = $this->isClient();
        }

        if($user->is_admin == false && $user->is_client == false){
            $user->permissions = $this->getPermissionUserLogin();
        }

        return $user;

    }

    private function getPermissionUserLogin(){
        $permissionsUser = $this->usersModel->getPermissionUserLogin(session()->get('user_id'));

        return array_column($permissionsUser, 'permission');

    }

}