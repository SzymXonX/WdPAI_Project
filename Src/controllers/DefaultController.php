<?php

require_once 'AppController.php';

class DefaultController extends AppController {

    public function login()
    {
        $this->render('login');
    }
    
    public function register()
    {
        $this->render('register');
    }

    public function main()
    {
        $this->render('main');
    }

}