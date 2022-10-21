<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Users extends BaseController
{
    private $usersModel;

    public function __construct()
    {

        $this->usersModel = new \App\Models\UsersModel();
        
    }

    public function index()
    {

        $data = [
            'title' => 'Listando os usuários do sistema.',
        ];

        $this->usersModel->find();

        return view('Users/index', $data);
    
    }
    public function getUsers()
    {
        if(!$this->request->isAJAX()){
            return redirect()->back();
        }
        $atrib = [
            'id',
            'name_user',
            'email',
            'active',
            'avatar',
        ];
        $users = $this->usersModel->select($atrib)->findAll();
        $data = [];
        
       

        foreach ($users as $user) {

            $data[] = [
                "avatar" => $user->avatar,
                "name_user" => anchor("users/load/$user->id", esc($user->name_user), 'title="Exibir usuário '.esc($user->name_user).'"'),
                "email" => esc($user->email),
                "active" => ($user->active == true ? '<span class="text-success"><i class="fa fa-unlock"></i>&nbsp;Ativo</span>' : '<span class="text-warning"><i class="fa fa-lock"></i>&nbsp;Inativo</span>')
            ];
        }

        $response = [
            'data' => $data
        ];

        return $this->response->setJSON($response);

    }

    public function load(int $id=null){

        $user = $this->getUserOr404($id);
        
        $data = [
        'title' => "Detalhando o usuário ".esc($user->name_user),
        'user' => $user,
        ];

        return view('Users/load', $data);
    }

    public function edit(int $id=null){

        $user = $this->getUserOr404($id);

        $data = [
        'title' => "Editando o usuário ".esc($user->name_user),
        'user' => $user,
        ];

        return view('Users/edit', $data);
    }

    public function update(){

        if(!$this->request->isAJAX()){
            return redirect()->back();
        }

        $response['token'] = csrf_hash(); //Envia token para o formulário

        $post = $this->request->getPost(); // Recupera os dados envidados pelo formulário
        unset($post['password']); // Bypass temporário
        unset($post['password_confirmation']); // Bypass temporário

        $user = $this->getUserOr404($post['id']); // Verificar se o usuário existe

        $user->fill($post); // Carrega o objeto com os dados vindo do Post

        if($user->hasChanged() == false){
            $response['info'] = "Não há dados para serem atualizados";
            return $this->response->setJSON($response);
        }

        if($this->usersModel->protect(false)->save($user)){

            return $this->response->setJSON($response);
        }

        $response['erro'] = "Por favor, verifique os erros abaixo e tente novamente.";
        $response['errors_model'] = $this->usersModel->errors;

        return $this->response->setJSON($response);
        
         // $response['erro'] = "Essa é uma mensagem de infomação erro";
        // $response['error_model'] = [
        //  'nome' => "Nome obrigatório",
        //  'email' => "Email obrigatório",
        //  'password' => "A senha é curta",
        // ];
    }



    private function getUserOr404(int $id=null){

    /**
     * Método que recupera usuário
     * 
     * @param integer $id
     * @return Exceptions|object
     */

        if(!$id || !$user = $this->usersModel->withDeleted(true)->find($id)){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o usuário $id");
        }
        return $user;
    }
}