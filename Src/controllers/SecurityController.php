<?php

ob_start();

require_once 'AppController.php';
require_once __DIR__ . '/../models/User.php';
require_once 'Database.php';

class SecurityController extends AppController {

    public function login_user(){
        require_once 'Database.php';
        $database = new Database();
        $db = $database->connect();

        if (!$this->isPost()) {
            return $this->render('login');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$userData) {
                return $this->render('login', ['messages' => ['User with this email does not exist']]);
            }

            if (!password_verify($password, $userData['password'])) {
                return $this->render('login', ['messages' => ['Wrong password']]);
            }

            require_once __DIR__ . '/../models/User.php';
            $user = new User(
                $userData['id'],
                $userData['email'],
                $userData['password'],
                $userData['first_name'],
                $userData['last_name']
            );

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_email'] = $user->getEmail();
            $_SESSION['user_name'] = $user->getFirstName() . ' ' . $user->getLastName();

            ob_end_clean();

            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/main");
            exit();

        } catch (PDOException $e) {
            return $this->render('login', ['messages' => ['Database error: ' . $e->getMessage()]]);
        }
    }

    public function register_user()
    {
        require_once 'Database.php';
        $database = new Database();
        $db = $database->connect();

        if (!$this->isPost()) {
            return $this->render('register');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm-password'];
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];

        if (empty($email) || empty($password) || empty($confirmPassword) || empty($firstName) || empty($lastName)) {
            return $this->render('register', ['messages' => ['All fields are required']]);
        }

        if ($password !== $confirmPassword) {
            return $this->render('register', ['messages' => ['Passwords do not match']]);
        }

        try {
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                return $this->render('register', ['messages' => ['User with this email already exists']]);
            }

            $query = "INSERT INTO users (email, password, first_name, last_name) VALUES (:email, :password, :first_name, :last_name)";
            $stmt = $db->prepare($query);

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':first_name', $firstName);
            $stmt->bindParam(':last_name', $lastName);

            $stmt->execute();

            return $this->render('register', ['messages' => ['Registration successful! You can now log in.']]);

        } catch (PDOException $e) {
            return $this->render('register', ['messages' => ['Database error: ' . $e->getMessage()]]);
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
    
        header("Location: /login");
        exit;
    }
}