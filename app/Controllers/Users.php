<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\User;
use App\Models\UsersModel;
use App\Models\GroupsUsersModel;

class Users extends BaseController
{
    private $usersModel;

    public function __construct()
    {

        $this->usersModel = new \App\Models\UsersModel();
        $this->groupsUsersModel = new \App\Models\GroupsUsersModel();
        $this->groupsModel = new \App\Models\GroupsModel();
        
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
            'deleted_at'
        ];
        $users = $this->usersModel->select($atrib)
                                ->withDeleted(true)
                                ->orderBy('id', 'DESC')
                                ->findAll();
        $data = [];
        
       

        foreach ($users as $user) {

            if($user->avatar != null){

                $avatar = [
                    'src' => site_url("users/avatar/$user->avatar"),
                    'class' => 'rounded-circle img-fluid',
                    'alt' => esc($user->name_user),
                    'width' => '50',
                ];

            }else{
                $avatar = [
                    'src' => site_url("resources/img/user_not_image.png"),
                    'class' => 'rounded-circle img-fluid',
                    'alt' => "Usuário sem imagem",
                    'width' => '50',
                ];
            }

            $data[] = [
                "avatar" => $user->avatar = img($avatar),
                "name_user" => anchor("users/load/$user->id", esc($user->name_user), 'title="Exibir usuário '.esc($user->name_user).'"'),
                "email" => esc($user->email),
                "active" => $user->getSituation()   ,
            ];
        }

        $response = [
            'data' => $data
        ];

        return $this->response->setJSON($response);

    }

    public function create(){

        $user = new User();
        
        $data = [
        'title' => "Criando novo usuário ",
        'user' => $user,
        ];

        return view('Users/create', $data);
    }

    public function insert(){

        if(!$this->request->isAJAX()){
            return redirect()->back();
        }

        $response['token'] = csrf_hash(); //Envia token para o formulário

        $post = $this->request->getPost(); // Recupera os dados envidados pelo formulário

        $user = new User($post);

        if($this->usersModel->protect(false)->save($user)){
            $linkCreate = anchor("users/create", 'Cadastrar novo usuário', ['class' => 'btn btn-danger mt-2']);

            session()->setFlashdata('success', "Dados salvos com sucesso!<br> $linkCreate");
            $response['id'] = $this->usersModel->getInsertID();
            return $this->response->setJSON($response);
        }

        $response['erro'] = "Por favor, verifique os erros abaixo e tente novamente.";
        $response['errors_model'] = $this->usersModel->errors();

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

        $user = $this->getUserOr404($post['id']); // Verificar se o usuário existe

        if(empty($post['password'])){ //Se não foi infomardo a senha, removemos do $post
            unset($post['password']); 
            unset($post['password_confirmation']); 
        }

        $user->fill($post); // Carrega o objeto com os dados vindo do Post

        if($user->hasChanged() == false){
            $response['info'] = "Não há dados para serem atualizados";
            return $this->response->setJSON($response);
        }

        if($this->usersModel->protect(false)->save($user)){
            session()->setFlashdata('success', 'Dados salvos com sucesso!');
            return $this->response->setJSON($response);
        }

        $response['erro'] = "Por favor, verifique os erros abaixo e tente novamente.";
        $response['errors_model'] = $this->usersModel->errors();

        return $this->response->setJSON($response);
      
    }

    public function editImage(int $id=null){

        $user = $this->getUserOr404($id);

        $data = [
        'title' => "Alterando a imagem do usuário ".esc($user->name_user),
        'user' => $user,
        ];

        return view('Users/edit-image', $data);
    }

    public function upload(){

        if(!$this->request->isAJAX()){
            return redirect()->back();
        }

        $response['token'] = csrf_hash(); //Envia token para o formulário

        $validation = service('validation');

        $rules =  [
            'avatar' => 'uploaded[avatar]|max_size[avatar,1024]|ext_in[avatar,png,jpg,jpeg,webp]',
            
        ];
        $message = [   // Errors
            'avatar' => [
                'uploaded' => 'Escolha uma imagem',
                'max_size' => 'Escolha uma imagem de no máximo 1024',
                'ext_in' => 'Escolha uma imagem png, jpg, jpeg ou webp',
            ],
        ];

        $validation->setRules($rules, $message);

        if($validation->withRequest($this->request)->run() == false){
            $response['erro'] = "Por favor, verifique os erros abaixo e tente novamente.";
            $response['errors_model'] = $validation->getErrors();
    
            return $this->response->setJSON($response);
        }


        $post = $this->request->getPost(); // Recupera os dados envidados pelo formulário

        $user = $this->getUserOr404($post['id']); // Verificar se o usuário existe

        $avatar = $this->request->getFile('avatar');

        list($width, $heigth) = getimagesize($avatar->getPathName());
        
        if($width < "300" || $heigth < "300"){
            $response['erro'] = "Por favor, verifique os erros abaixo e tente novamente.";
            $response['errors_model'] = ['size' => "A imagem não pode ser menor que 300 X 300 pixels"];
    
            return $this->response->setJSON($response);
        }

        $path = $avatar->store('users');

        // C:\wamp\www\ordem\writable\uploads/users/1666379579_12a057f4010433a8b4d6.jpg
        $path = WRITEPATH."uploads/$path";

        //Trata a imagem
        $this->resourceImage($path);
        
        //Recupero a possível imagem
        $imageOld = $user->avatar;                        

        $user->avatar = $avatar->getName();

        $this->usersModel->save($user);

        //Remove imagem antiga do File System
        if($imageOld != null){
            $this->removeImageFileSystem($imageOld);
        }

        session()->setFlashdata('success', 'Imagem atualizada com sucesso!');

        return $this->response->setJSON($response);
      
    }

    public function avatar(string $avatar = null){
        if($avatar != null){
            $this->loadFile('users', $avatar);
        }
    }

    public function delete(int $id=null){

        $user = $this->getUserOr404($id);

        if($user->deleted_at != null){
            return redirect()->back()->with('info', "Esse usuário já encontra-se excluído.");
        }
        
        if($this->request->getMethod() === 'post'){

            $this->usersModel->delete($user->id);

            if($user->avatar!=null){
                $this->removeImageFileSystem($user->avatar);
            }

            $user->avatar = null;
            $user->active = false;

            $this->usersModel->protect(false)->save($user);

            return redirect()->to(site_url("users"))->with('success', "Usuário $user->name_user excluído com sucesso!");
        }

        $data = [
        'title' => "Excluindo o usuário ".esc($user->name_user),
        'user' => $user,
        ];

        return view('Users/delete', $data);
    }

    public function restoreDelete(int $id=null){

        $user = $this->getUserOr404($id);

        if($user->deleted_at == null){
            return redirect()->back()->with('info', "Apenas usuários excluídos podem ser recuperados.");
        }
        
        $user->deleted_at = null;
        $this->usersModel->protect(false)->save($user);

        return redirect()->back()->with('success', "Usuário $user->name_user recuperado com sucesso.");

        
    }

    public function groups(int $id=null){

        $user = $this->getUserOr404($id);

        $user->groups = $this->groupsUsersModel->getGroupsUsers($user->id, 5);
        $user->pager = $this->groupsUsersModel->pager;

        $data = [
        'title' => "Gerenciando os grupos de acesso do usuário ".esc($user->name_user),
        'user' => $user,
        ];


        // Quando o usuário for um cliente, podemos retornar a View de 
        //exibição do usuário infromando que ele é um cliente que não é possível adicioná-lo a outros grupos.
        if(in_array(2, array_column($user->groups,'group_id'))) {
            return redirect()->to(site_url("users/load/$user->id"))->with('info', 'Esse usuário é um cliente, não é necessário atribuí-lo a um grupo de acesso');
        }
        
        if(!empty($user->groups)){
            $groupsExists = array_column($user->groups, 'group_id');
            $data['groupsAvailable'] = $this->groupsModel->where('id != ', 2)->whereNotIn('id', $groupsExists)->findAll();
        }else{
            $data['groupsAvailable'] = $this->groupsModel->where('id != ', 2)->findAll();
        }

        return view('Users/groups', $data);
    }

    public function saveGroups(){
        if(!$this->request->isAJAX()){
            return redirect()->back();
        }

        $response['token'] = csrf_hash(); //Envia token para o formulário

        $post = $this->request->getPost(); // Recupera os dados envidados pelo formulário

        $user = $this->getUserOr404($post['id']); // Verificar se o usuário existe

        if(empty($post['groups_id'])){
            $response['erro'] = "Por favor, verifique os erros abaixo e tente novamente.";
            $response['errors_model'] = ['group_id' => 'Escolha um ou mais grupos para salvar'];
    
            return $this->response->setJSON($response);
        }


        if(in_array(2, $post['groups_id'])){
            $response['erro'] = "Por favor, verifique os erros abaixo e tente novamente.";
            $response['errors_model'] = ['group_id' => 'O grupo de Clientes não pode ser atribuído de forma manual.'];
    
            return $this->response->setJSON($response);
        }

        if(in_array(1, $post['groups_id'])){
            $groupAdmin =[
                'groups_id' => 1,
                'users_id' => $user->id,
            ];

            $this->groupsUsersModel->insert($groupAdmin);
            $this->groupsUsersModel->where('groups_id !=', 1)
                                   ->where('users_id', $user->id)
                                   ->delete(); 

            session()->setFlashdata('success', 'Dados salvos com sucesso!');
            session()->setFlashdata('info', 'Notamos que o grupo Administrador foi selecionado, sendo assim, não é necessário selecionar outro grupo.');

            return $this->response->setJSON($response);
        }


        $groupsPush = [];

        foreach($post['groups_id'] as $group){
            array_push($groupsPush,[
                'groups_id' => $group,
                'users_id' => $user->id,
            ]);
        }


        $this->groupsUsersModel->insertBatch($groupsPush);
        session()->setFlashdata('success', 'Dados salvos com sucesso!');
        return $this->response->setJSON($response);
    }

    public function removeGroups(int $main_id = null){
        if($this->request->getMethod()=== 'post'){
            $groupUser = $this->getGroupUser($main_id);

            if($groupUser->groups_id == 2){
                return redirect()->to(site_url("users/load/$groupUser->users_id"))->with("info", "Não é permitida da exclusão do usuário do grupo de Clientes");
            }
        }

        $this->groupsUsersModel->delete($main_id);

        return redirect()->back()->with("success", "Usuário removido do grupo de acesso com sucesso!");
        
    }

    public function editPassword() {
        $data = [
            'title' => 'Edite a sua senha de acesso.',
        ];

        return view('users/edit-password', $data);
    }

    public function updatePassword() {
        if(!$this->request->isAJAX()){
            return redirect()->back();
        }

        $response['token'] = csrf_hash(); //Envia token para o formulário

        $current_password = $this->request->getPost('current_password');

        $user = user_logged();

        if($user->verifyPassword($current_password) === false){
            $response['erro'] = "Por favor, verifique os erros abaixo e tente novamente.";
            $response['errors_model'] = ['current_password' => 'Senha atual inválida'];
            return $this->response->setJSON($response); 
        }

        $user->fill($this->request->getPost());

        if($user->hasChanged() == false){
            $response['info'] = "Não há dados para serem atualizados";
            return $this->response->setJSON($response);
        }

        if($this->usersModel->save($user)){
            $response['success'] = 'Senha alterada com sucesso.';
            return $this->response->setJSON($response);
        }

        $response['erro'] = "Por favor, verifique os erros abaixo e tente novamente.";
        $response['errors_model'] = $this->usersModel->errors();

        return $this->response->setJSON($response);

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
    private function getGroupUser(int $main_id=null){

    /**
     * Método que recupera o registro do grupo associado ao usuário
     * 
     * @param integer $main_id
     * @return Exception|object
     */

        if(!$main_id || !$groupUser = $this->groupsUsersModel->withDeleted(true)->find($main_id)){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o registro de associação ao grupo de acesso $main_id");
        }
        return $groupUser;
    }

    private function resourceImage(string $path){
        // Redimensionando a imagem
        service('image')->withFile($path)
                        ->fit(300, 300, 'center')
                        ->save($path);
    }

    private function removeImageFileSystem(string $imageOld){

        $path = WRITEPATH."uploads/users/$imageOld";

        if(is_file($path)){
            unlink($path);
        }
    }
}