<?php
require_once 'AppController.php';

class SettingsController extends AppController {

    public function Settings() {
        // Sprawdzenie, czy użytkownik jest zalogowany
        $this->requireLogin();

        // Kod do obsługi strony main (np. renderowanie widoku)
        $this->render('settings');
    }

}