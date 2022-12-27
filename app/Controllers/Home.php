<?php

namespace App\Controllers;
use App\Libraries\Auth;

class Home extends BaseController
{
    public function index()
    {

        $data = [
            'title' => 'Home'
        ];

        return view('Home/index', $data);
    }

    public function login(){
        $auth = service('auth');

        $auth->login('julia@gmail.com', '123456');


        // $auth->verifyUserLogin();

        dd($auth->getUserLogin());

    }

    public function logout(){
        $auth = new Auth();
        $auth->logout();
        return redirect()->to(site_url('/'));

    }
}
