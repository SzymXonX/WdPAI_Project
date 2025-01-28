<?php
require_once 'AppController.php';

class MainController extends AppController {

    public function Main() {
        // Sprawdzenie, czy użytkownik jest zalogowany
        $this->requireLogin();

        // Kod do obsługi strony main (np. renderowanie widoku)
        $this->render('main');
    }

}