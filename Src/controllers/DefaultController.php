<?php

require_once 'AppController.php';

class DefaultController extends AppController {

    public function index()
    {
        $this->render('index');
    }
    
    public function register()
    {
        // wyświetlamy widok "register" (czyli plik public/views/register.php)
        $this->render('register');
    }

}