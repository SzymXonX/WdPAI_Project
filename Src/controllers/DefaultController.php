<?php

require_once 'AppController.php';

class DefaultController extends AppController {

    public function index()
    {
        $this->render('index');
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