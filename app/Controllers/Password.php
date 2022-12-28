<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\App;

class Password extends BaseController
{

    private $userModel;

    public function __construct()
    {
        $this->userModel = new \App\Models\UsersModel();
    }


    public function forgot()
    {
        $data = [
            'title' => 'Esqueci a minha senha.',
        ];
        return view('Password/forgot', $data);
    }

    public function processForgot(){
        if(!$this->request->isAJAX()){
            return redirect()->back();
        }

        $response['token'] = csrf_hash(); //Envia token para o formulário

        $email = $this->request->getPost('email');

        $user = $this->userModel->getUserEmail($email);

        if($user === null || $user->active == false){
            $response['erro'] = 'Não encontramos uma conta válida com esse e-mail';
            return $this->response->setJSON($response);
        }

        $user->initPasswordReset();

        $this->userModel->save($user);

        $this->sendEmailResetPassword($user);

        return $this->response->setJSON([]);


    }

    public function resetSend(){
        $data = [
            'title' => 'E-mail de recuperação envidado para a sua caixa de entrada.',
        ];
        return view('Password/reset-send', $data);
    }


    /**
     * Metodo que envia email para o usário
     * 
     * @param object $user
     * @return void
     * 
     */
    private function sendEmailResetPassword(object $user) :void {
        $email = service('email');

        $email->setFrom('no-reply@ordem.com', 'Ordem de Serviço');
        $email->setTo($user->email);

        $email->setSubject('Redefinição da senha de acesso');

        $data = [
            'token' => $user->reset_token
        ];

        $message = view('Password/reset-email', $data);

        $email->setMessage($message);

        $email->send();
    }
}
