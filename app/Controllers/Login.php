<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Login extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Realize o login',
        ];  
        return view('Login/index', $data);
    }

    public function create()
    {
        if(!$this->request->isAJAX()){
            return redirect()->back();
        }

        $response['token'] = csrf_hash(); //Envia token para o formulário

        $email = $this->request->getPost('email'); // Recupera os dados envidados pelo formulário
        $password = $this->request->getPost('password'); // Recupera os dados envidados pelo formulário

        $auth = service('auth');

        if($auth->login($email, $password) === false){
            $response['erro'] = "Por favor, verifique os erros abaixo e tente novamente.";
            $response['errors_model'] = ['credenciais' => 'Não encontramo suas credenciais de acesso.'];
    
            return $this->response->setJSON($response);
        }

        
        $userLogin = $auth->getUserLogin();
        session()->setFlashdata('success', "Olá $userLogin->name_user, que bom que está de volta!");

        if($userLogin->is_client){
            $response['redirect'] = 'ordens/my';
            return $this->response->setJSON($response);
        }

        $response['redirect'] = 'home';
        return $this->response->setJSON($response);
        
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url("login"));

    }
}
