<?php
require_once 'AppController.php';

class MainController extends AppController {

    public function Main() {
        $this->requireLogin();

        $this->render('main');
    }

}