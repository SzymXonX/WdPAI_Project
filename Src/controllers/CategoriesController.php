<?php
require_once 'AppController.php';
require_once 'Database.php';
require_once __DIR__ . '/../models/Category.php';



class CategoriesController extends AppController {

    public function categories() {
        $this->requireLogin();

        $database = new Database();
        $db = $database->connect();

        $categoriesStmt = $db->prepare("SELECT id, name FROM categories ORDER BY name ASC");
        $categoriesStmt->execute();
        $categories = [];
        while ($row = $categoriesStmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = new Category($row['id'], $row['name']);
        }

        $incomeCategoriesStmt = $db->prepare("SELECT id, name FROM income_categories ORDER BY name ASC");
        $incomeCategoriesStmt->execute();
        $incomeCategories = [];
        while ($row = $incomeCategoriesStmt->fetch(PDO::FETCH_ASSOC)) {
            $incomeCategories[] = new Category($row['id'], $row['name']);
        }

        $this->render('categories', [
            'categories' => $categories,
            'incomeCategories' => $incomeCategories
        ]);
    }

    public function addCategory() {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /categories");
            exit();
        }

        $categoryType = $_POST['category-type'] ?? 'expense';
        $categoryName = trim($_POST['category-name'] ?? '');

        if (empty($categoryName)) {
            $_SESSION['messages'][] = "Nazwa kategorii nie może być pusta!";
            header("Location: /categories");
            exit();
        }

        $database = new Database();
        $db = $database->connect();

        $table = ($categoryType === 'income') ? 'income_categories' : 'categories';
        $checkStmt = $db->prepare("SELECT id FROM $table WHERE name = :name");
        $checkStmt->bindParam(':name', $categoryName);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            $_SESSION['messages'][] = "Taka kategoria już istnieje!";
            header("Location: /categories");
            exit();
        }

        try {
            $stmt = $db->prepare("INSERT INTO $table (name) VALUES (:name)");
            $stmt->bindParam(':name', $categoryName);
            $stmt->execute();

            $_SESSION['messages'][] = "Kategoria '{$categoryName}' została dodana!";
        } catch (Exception $e) {
            $_SESSION['messages'][] = "Błąd: " . $e->getMessage();
        }

        header("Location: /categories");
        exit();
    }

    public function deleteCategory() {
        $this->requireLogin();
    
        $data = json_decode(file_get_contents("php://input"), true);
        $categoryId = $data['id'] ?? null;
        $categoryType = $data['type'] ?? 'expense';
    
        if (!$categoryId) {
            echo json_encode(["success" => false, "message" => "Brak ID kategorii."]);
            exit();
        }
    
        $database = new Database();
        $db = $database->connect();
        
        $table = ($categoryType === 'income') ? 'income_categories' : 'categories';
    
        $stmt = $db->prepare("SELECT id FROM $table WHERE id = :id");
        $stmt->bindParam(':id', $categoryId);
        $stmt->execute();
    
        if ($stmt->rowCount() === 0) {
            echo json_encode(["success" => false, "message" => "Kategoria nie istnieje."]);
            exit();
        }
    
        $stmt = $db->prepare("DELETE FROM $table WHERE id = :id");
        $stmt->bindParam(':id', $categoryId);
        
        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Błąd podczas usuwania kategorii."]);
        }
    
        exit();
    }
    
}
