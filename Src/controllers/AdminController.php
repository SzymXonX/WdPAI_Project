<?php
require_once 'AppController.php';
require_once __DIR__ . '/../models/User.php';

class AdminController extends AppController {

    public function admin() {
        $this->requireAdmin();

        $database = new Database();
        $db = $database->connect();

        $query = "SELECT id, first_name, last_name, email, role FROM users ORDER BY role DESC, last_name ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin', ['users' => $users]);
    }

    public function getUser() {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Brak ID użytkownika"]);
            exit;
        }
    
        $userId = $_GET['id'];
    
        $database = new Database();
        $db = $database->connect();
    
        $query = "SELECT id, first_name, last_name, email, role FROM users WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user) {
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "Nie znaleziono użytkownika"]);
        }
        exit;
    }

    public function updateUser() {
        header('Content-Type: application/json');
    
        $data = json_decode(file_get_contents("php://input"), true);
        $userId = $data['id'] ?? null;
        $firstName = $data['first_name'] ?? null;
        $lastName = $data['last_name'] ?? null;
        $email = $data['email'] ?? null;
        $role = $data['role'] ?? null;
        $password = $data['password'] ?? null;
    
        if (!$userId || !$firstName || !$lastName || !$email || !$role) {
            echo json_encode(["success" => false, "message" => "Nieprawidłowe dane wejściowe."]);
            exit();
        }
    
        try {
            $database = new Database();
            $db = $database->connect();
    
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $query = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, role = :role, password = :password WHERE id = :id";
            } else {
                $query = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, role = :role WHERE id = :id";
            }
    
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':first_name', $firstName);
            $stmt->bindParam(':last_name', $lastName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':role', $role);
    
            if (!empty($password)) {
                $stmt->bindParam(':password', $hashedPassword);
            }
    
            if ($stmt->execute()) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "message" => "Nie udało się zaktualizować użytkownika."]);
            }
        } catch (Exception $e) {
            echo json_encode(["success" => false, "message" => "Błąd serwera: " . $e->getMessage()]);
        }
    }
    
    
    

    public function deleteUser() {
        $this->requireAdmin();

        header('Content-Type: application/json');

        $userId = $_GET['id'] ?? null;
        if (!$userId) {
            echo json_encode(["success" => false, "message" => "Nie podano ID użytkownika"]);
            exit();
        }

        $database = new Database();
        $db = $database->connect();

        // Sprawdzenie, czy użytkownik jest adminem
        $query = "SELECT role FROM users WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user['role'] === 'admin') {
            echo json_encode(["success" => false, "message" => "Nie można usunąć administratora"]);
            exit();
        }

        // Usunięcie użytkownika
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Nie udało się usunąć użytkownika"]);
        }
        exit();
    }


    private function getAllUsers() {
        $database = new Database();
        $db = $database->connect();

        $stmt = $db->query("SELECT id, email, first_name, last_name, role FROM users ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function editUser() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['id'];
            $email = $_POST['email'];
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $role = $_POST['role'];

            $database = new Database();
            $db = $database->connect();

            $stmt = $db->prepare("UPDATE users SET email = :email, first_name = :first_name, last_name = :last_name, role = :role WHERE id = :id");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':first_name', $firstName);
            $stmt->bindParam(':last_name', $lastName);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':id', $userId);

            if ($stmt->execute()) {
                header("Location: admin");
                exit();
            }
        }

        $userId = $_GET['id'];
        $user = $this->getUserById($userId);
        $this->render('editUser', ['user' => $user]);
    }

    private function getUserById($id) {
        $database = new Database();
        $db = $database->connect();

        $stmt = $db->prepare("SELECT id, email, first_name, last_name, role FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function requireAdmin() {
        session_start();

        if (!isset($_SESSION['user_id']) || !$this->isAdmin($_SESSION['user_id'])) {
            header("Location: main");
            exit();
        }
    }

    private function isAdmin($userId) {
        $database = new Database();
        $db = $database->connect();

        $stmt = $db->prepare("SELECT getRole(:user_id)");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        $role = $stmt->fetchColumn();
        return $role === 'admin';
    }
}
