<?php
require_once 'AppController.php';

class SettingsController extends AppController {
    public function settings() {
        $this->requireLogin();

        session_start();
        $user_id = $_SESSION['user_id'];

        $database = new Database();
        $db = $database->connect();

        // Pobranie danych użytkownika
        $query = "SELECT first_name, last_name, email, password FROM users WHERE id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Jeśli użytkownik istnieje, przekazujemy jego dane do widoku
        $this->render('settings', ['user' => $user]);
    }

    public function changeData() {
        

    }
}