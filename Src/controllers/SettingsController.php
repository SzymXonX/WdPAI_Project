<?php
require_once 'AppController.php';

class SettingsController extends AppController {
    public function settings() {
        $this->requireLogin();

        session_start();
        $user_id = $_SESSION['user_id'];

        $database = new Database();
        $db = $database->connect();

        $query = "SELECT first_name, last_name, email, password FROM users WHERE id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->render('settings', ['user' => $user]);
    }

    public function changeData() {
        $this->requireLogin();

        session_start();
        $user_id = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = trim($_POST['first_name']);
            $lastName = trim($_POST['last_name']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $confirmPassword = trim($_POST['confirm-password']);

            $messages = [];

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $messages[] = "Błąd: Nieprawidłowy format adresu e-mail.";
            }

            $database = new Database();
            $db = $database->connect();
            $stmt = $db->prepare("SELECT password FROM users WHERE id = :id");
            $stmt->bindParam(':id', $user_id);
            $stmt->execute();
            $currentPassword = $stmt->fetchColumn();

            if (!empty($password) || !empty($confirmPassword)) {
                if ($password !== $confirmPassword) {
                    $messages[] = "Błąd: Hasła nie są identyczne.";
                }

                if (empty($messages)) {
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                }
            } else {
                $hashedPassword = $currentPassword;
            }

            if (empty($messages)) {
                try {
                    $stmt = $db->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, password = :password WHERE id = :id");
                    $stmt->bindParam(':first_name', $firstName);
                    $stmt->bindParam(':last_name', $lastName);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $hashedPassword);
                    $stmt->bindParam(':id', $user_id);

                    if ($stmt->execute()) {
                        $messages[] = "Dane zostały zaktualizowane.";
                    } else {
                        $messages[] = "Błąd: Wystąpił błąd podczas aktualizacji danych.";
                    }
                } catch (Exception $e) {
                    $messages[] = "Błąd: " . $e->getMessage();
                }
            }

            $this->render('settings', ['messages' => $messages, 'user' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email
            ]]);
        }
    }
}