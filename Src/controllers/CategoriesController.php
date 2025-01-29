<?php
require_once 'AppController.php';

class CategoriesController extends AppController {

    public function Categories() {
        $this->requireLogin();

        $this->render('categories');
    }

}

