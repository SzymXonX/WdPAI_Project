<?php

require_once 'AppController.php';
require_once __DIR__ .'/../models/User.php';
require_once 'Database.php'; // Dodaj to

class SecurityController extends AppController {

    public function login_user(){
        // Inicjalizacja połączenia z bazą danych
        require_once 'Database.php';
        $database = new Database();
        $db = $database->connect();

        // Jeśli żądanie nie jest typu POST, wyświetl widok logowania
        if (!$this->isPost()) {
            return $this->render('login');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
            // Zapytanie SQL do pobrania użytkownika po emailu
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Pobierz dane użytkownika
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$userData) {
                // Jeśli użytkownik o podanym emailu nie istnieje
                return $this->render('login', ['messages' => ['User with this email does not exist']]);
            }

            // Sprawdzenie hasła (hasła powinny być haszowane w bazie danych!)
            if (!password_verify($password, $userData['password'])) {
                return $this->render('login', ['messages' => ['Wrong password']]);
            }

            // Stwórz obiekt User na podstawie danych pobranych z bazy
            require_once __DIR__ . '/../models/User.php';
            $user = new User(
                $userData['id'],
                $userData['email'],
                $userData['password'], // Hasło jest już sprawdzone, tutaj podajemy je jako część obiektu
                $userData['first_name'],
                $userData['last_name']
            );

            // (Opcjonalnie) możesz przechowywać dane użytkownika w sesji
            session_start();
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_email'] = $user->getEmail();
            $_SESSION['user_name'] = $user->getFirstName() . ' ' . $user->getLastName();

            // Przekierowanie po zalogowaniu
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/main");

        } catch (PDOException $e) {
            // Obsługa błędów bazy danych
            return $this->render('login', ['messages' => ['Database error: ' . $e->getMessage()]]);
        }
    }

    public function register_user()
    {
        require_once 'Database.php';
        $database = new Database();
        $db = $database->connect();

        // Jeśli nie jest POST, wyświetl widok rejestracji
        if (!$this->isPost()) {
            return $this->render('register');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];

        // Walidacja danych
        if (empty($email) || empty($password) || empty($confirmPassword) || empty($firstName) || empty($lastName)) {
            return $this->render('register', ['messages' => ['All fields are required']]);
        }

        if ($password !== $confirmPassword) {
            return $this->render('register', ['messages' => ['Passwords do not match']]);
        }

        // Sprawdź, czy użytkownik już istnieje
        try {
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                return $this->render('register', ['messages' => ['User with this email already exists']]);
            }

            // Dodaj użytkownika do bazy
            $query = "INSERT INTO users (email, password, first_name, last_name) VALUES (:email, :password, :first_name, :last_name)";
            $stmt = $db->prepare($query);

            // Haszuj hasło przed zapisaniem w bazie
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':first_name', $firstName);
            $stmt->bindParam(':last_name', $lastName);

            $stmt->execute();

            // Przekierowanie po pomyślnej rejestracji
            return $this->render('register', ['messages' => ['Registration successful! You can now log in.']]);

        } catch (PDOException $e) {
            return $this->render('register', ['messages' => ['Database error: ' . $e->getMessage()]]);
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
    
        // Przekierowanie na stronę logowania
        header("Location: /login");
        exit;
    }
    


}