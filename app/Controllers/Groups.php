<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Group;

class Groups extends BaseController
{ 

    protected $groupsModel;
    protected $groupsPermissionsModel;
    protected $permissionsModel;
    
    public function __construct()
    {
        $this->groupsModel = new \App\Models\GroupsModel();
        $this->groupsPermissionsModel = new \App\Models\GroupsPermissionsModel();
        $this->permissionsModel = new \App\Models\PermissionsModel();
    }

    public function index()
    {

        $data = [
            'title' => 'Listando os grupos de acessso ao sistema.',
        ];

        $this->groupsModel->find();

        return view('Groups/index', $data);
    
    }

    public function getGroups()
    {
        if(!$this->request->isAJAX()){
            return redirect()->back();
        }
        $atrib = [
            'id',
            'groupname',
            'description',
            'disposition',
            'deleted_at'
        ];

        $groups = $this->groupsModel->select($atrib)
                                ->withDeleted(true)
                                ->orderBy('id', 'DESC')
                                ->findAll();
        $data = [];
        
       

        foreach ($groups as $group) {

            $data[] = [
                "groupname" => anchor("groups/load/$group->id", esc($group->groupname), 'title="Exibir usuário '.esc($group->groupname).'"'),
                "description" => esc($group->description),
                "disposition" => $group->getSituation(),
            ];
        }

        $response = [
            'data' => $data
        ];

        return $this->response->setJSON($response);

    }

    public function create(int $id=null){

       $group = new Group();

        $data = [
        'title' => "Criando um novo grupo de acesso ".esc($group->groupname),
        'group' => $group,
        ];

        return view('Groups/create', $data);
    }

    public function insert(){

        if(!$this->request->isAJAX()){
            return redirect()->back();
        }

        $response['token'] = csrf_hash(); //Envia token para o formulário

        $post = $this->request->getPost(); // Recupera os dados envidados pelo formulário

        $group = new Group($post);

        if($this->groupsModel->save($group)){
            $linkCreate = anchor("groups/create", 'Cadastrar novo grupo de acesso', ['class' => 'btn btn-danger mt-2']);

            session()->setFlashdata('success', "Dados salvos com sucesso!<br> $linkCreate");
            $response['id'] = $this->groupsModel->getInsertID();
            return $this->response->setJSON($response);
        }

        $response['erro'] = "Por favor, verifique os erros abaixo e tente novamente.";
        $response['errors_model'] = $this->groupsModel->errors();

        return $this->response->setJSON($response);
       
    }

    public function load(int $id=null){

        $group = $this->getGroupOr404($id);
        
        $data = [
        'title' => "Detalhando o grupo de acesso ".esc($group->groupname),
        'group' => $group,
        ];

        return view('Groups/load', $data);
    }

    public function edit(int $id=null){

        $group = $this->getGroupOr404($id);

        if($group->id < 3){
            return redirect()->back()->with('alert', 'O grupo <b>' . esc($group->groupname).' </b>não pode ser editado ou excluído, pois eles não pode
            ter suas permissões revogadas');
        }
        
        $data = [
        'title' => "Editando o grupo de acesso ".esc($group->groupname),
        'group' => $group,
        ];

        return view('Groups/edit', $data);
    }

    public function update(){

        if(!$this->request->isAJAX()){
            return redirect()->back();
        }

        $response['token'] = csrf_hash(); //Envia token para o formulário

        $post = $this->request->getPost(); // Recupera os dados envidados pelo formulário

        $group = $this->getGroupOr404($post['id']); // Verificar se o usuário existe


        if($group->id < 3){
          
            $response['erro'] = "Por favor, verifique os erros abaixo e tente novamente.";
            $response['errors_model'] = ['group' => 'O grupo <b class="text-white">'. esc($group->groupname) .' </b>não pode ser editado ou excluído, pois ele não pode
            ter suas permissões revogadas'];
    
            return $this->response->setJSON($response);
        }
       

        $group->fill($post); // Carrega o objeto com os dados vindo do Post

        if($group->hasChanged() == false){
            $response['info'] = "Não há dados para serem atualizados";
            return $this->response->setJSON($response);
        }

        if($this->groupsModel->protect(false)->save($group)){
            session()->setFlashdata('success', 'Dados salvos com sucesso!');
            return $this->response->setJSON($response);
        }

        $response['erro'] = "Por favor, verifique os erros abaixo e tente novamente.";
        $response['errors_model'] = $this->groupsModel->errors();

        return $this->response->setJSON($response);
      
    }

    public function delete(int $id=null){

        $group = $this->getGroupOr404($id);

        if($group->deleted_at != null){
            return redirect()->back()->with('info', "Esse grupo já encontra-se excluído.");
        }

        if($group->id < 3){
            return redirect()->back()->with('alert', 'O grupo <b>' . esc($group->groupname).' </b>não pode ser editado ou excluído, pois eles não pode
            ter suas permissões revogadas');
        }
        
        if($this->request->getMethod() === 'post'){

            $this->groupsModel->delete($group->id);

            return redirect()->to(site_url("groups"))->with('success', 'Usuário' . esc($group->groupname) . 'excluído com sucesso!');
        }

        $data = [
        'title' => "Excluindo o grupo de acesso ".esc($group->groupname),
        'group' => $group,
        ];

        return view('groups/delete', $data);
    }

    public function restoreDelete(int $id=null){

        $group = $this->getGroupOr404($id);

        if($group->deleted_at == null){
            return redirect()->back()->with('info', "Apenas grupos excluídos podem ser recuperados.");
        }
      
        
        $group->deleted_at = null;

        $this->groupsModel->protect(false)->save($group);

        return redirect()->back()->with('success', 'Grupo ' . esc($group->groupname) . ' recuperado com sucesso.');
        
    }

    public function permissions(int $id=null){

        $group = $this->getGroupOr404($id);

        if($group->id < 3){
            return redirect()
                    ->back()
                    ->with('info', 'Para grupo <b>' . esc($group->groupname).' </b>não é necessário atribuir permissões.');
        }

        if($group->id >2){
            $group->permissions = $this->groupsPermissionsModel->getPermissionsGroups($group->id, 5);
            $group->pager = $this->groupsPermissionsModel->pager;
        }

        $data = [
        'title' => "Gerenciando as permissões do grupo de acesso ".esc($group->groupname),
        'group' => $group,
        ];

        if(!empty($group->permissions)){
            $permissionsExists = array_column($group->permissions, 'permissions_id');
            $data['permissionsAvailable'] = $this->permissionsModel->whereNotIn('id', $permissionsExists)->findAll();
        }else{
            $data['permissionsAvailable'] = $this->permissionsModel->findAll();
        }

        return view('Groups/permissions', $data);
    }

    public function savePermissions(){
        if(!$this->request->isAJAX()){
            return redirect()->back();
        }

        $response['token'] = csrf_hash(); //Envia token para o formulário

        $post = $this->request->getPost(); // Recupera os dados envidados pelo formulário

        $group = $this->getGroupOr404($post['id']); // Verificar se o usuário existe

        if(empty($post['permissions_id'])){
            $response['erro'] = "Por favor, verifique os erros abaixo e tente novamente.";
            $response['errors_model'] = ['permissions_id' => 'Escolha uma ou mais permissões para salvar'];
    
            return $this->response->setJSON($response);
        }

        $permissionPush = [];

        foreach($post['permissions_id'] as $permission){
            array_push($permissionPush,[
                'groups_id' => $group->id,
                'permissions_id' => $permission,
            ]);
        }

        $this->groupsPermissionsModel->insertBatch($permissionPush);
        session()->setFlashdata('success', 'Dados salvos com sucesso!');
        return $this->response->setJSON($response);
    }

    public function removePermissions(int $main_id=null){

        
        if($this->request->getMethod() === 'post'){

            $this->groupsPermissionsModel->delete($main_id);

            return redirect()->back()->with('success', 'Permissão removida com sucesso!');
        }

        return redirect()->back();

    }
    

    private function getGroupOr404(int $id=null){

        /**
         * Método que recupera o grupo de acesso
         * 
         * @param integer $id
         * @return Exceptions|object
         */
    
            if(!$id || !$group = $this->groupsModel->withDeleted(true)->find($id)){
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o grupo $id");
            }
            return $group;
        }
}