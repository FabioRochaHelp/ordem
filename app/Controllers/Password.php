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
            'title' => 'Esqeuci a minha senha',
        ];
        return view('Password/forgot');
    }
}
