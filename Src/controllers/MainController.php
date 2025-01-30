<?php
require_once 'AppController.php';
require_once __DIR__ . '/../models/Category.php';

class MainController extends AppController {
    public function main() {
        $this->requireLogin();
    
        session_start();
        $user_id = $_SESSION['user_id'];
    
        $selectedYear = $_GET['year'] ?? date('Y');
        $selectedMonth = $_GET['month'] ?? date('m');
    
        $summaryData = $this->getSummaryData($user_id, $selectedYear, $selectedMonth);
        $categories = $this->getCategories(); 
        $incomeCategories = $this->getIncomeCategories();
        $expenses = $this->getUserExpenses($user_id, $selectedYear, $selectedMonth);
        $incomes = $this->getUserIncomes($user_id, $selectedYear, $selectedMonth);
    
        if ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode([
                "summary" => $summaryData,
                "expenses" => $expenses,
                "incomes" => $incomes,
                "categories" => $categories,
                "incomeCategories" => $incomeCategories
            ]);
            exit();
        }
    
        $this->render('main', [
            'summaryData' => $summaryData,
            'categories' => $categories,
            'incomeCategories' => $incomeCategories,
            'expenses' => $expenses,
            'incomes' => $incomes,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth
        ]);
    }
    
    
    
    // Pobieranie danych sumarycznych
    private function getSummaryData($user_id, $year, $month) {
        $database = new Database();
        $db = $database->connect();
    
        $query = "SELECT total_income, total_expense, budget FROM summary 
                  WHERE user_id = :user_id AND year = :year AND month = :month";
    
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->bindParam(':month', $month, PDO::PARAM_INT);
        $stmt->execute();
    
        $summary = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $summary ?: ['total_income' => 0.00, 'total_expense' => 0.00, 'budget' => 0.00];
    }
    
    // Pobieranie kategorii
    private function getCategories() {
        $this->requireLogin();
    
        $database = new Database();
        $db = $database->connect();
    
        $query = "SELECT id, name FROM categories ORDER BY name ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();
    
        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = new Category($row['id'], $row['name']);
        }
    
        return $categories;
    }
    private function getIncomeCategories() {
        $this->requireLogin();
    
        $database = new Database();
        $db = $database->connect();
    
        $query = "SELECT id, name FROM income_categories ORDER BY name ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();
    
        $incomeCategories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $incomeCategories[] = new Category($row['id'], $row['name']);
        }
    
        return $incomeCategories;
    }
    
    // Dodawanie wydatków i przychodów na stronie głównej
    public function add() {
        $this->requireLogin();
    
        session_start();
        $user_id = $_SESSION['user_id'];
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transactionType = $_POST['transaction-type'] ?? null;
            $amount = trim($_POST['amount']);
            $category_id = $_POST['category'] ?? null;
            $description = trim($_POST['description']);
    
            $selectedYear = $_POST['selected-year'] ?? date('Y');
            $selectedMonth = $_POST['selected-month'] ?? date('m');
    
            $_SESSION['messages'] = [];
    
            if (empty($transactionType) || empty($amount) || empty($category_id)) {
                $_SESSION['messages'][] = "Błąd: Wszystkie pola muszą być wypełnione.";
            }
    
            if (!is_numeric($amount) || $amount <= 0) {
                $_SESSION['messages'][] = "Błąd: Kwota musi być liczbą większą od zera.";
            }
    
            if (!empty($_SESSION['messages'])) {
                header("Location: /main?year=$selectedYear&month=$selectedMonth");
                exit();
            }
    
            try {
                $database = new Database();
                $db = $database->connect();
    
                if ($transactionType === "expense") {
                    $query = "INSERT INTO expenses (user_id, amount, category_id, description, date)
                              VALUES (:user_id, :amount, :category_id, :description, NOW())";
                } else {
                    $query = "INSERT INTO incomes (user_id, amount, category_id, description, date)
                              VALUES (:user_id, :amount, :category_id, :description, NOW())";
                }                
    
                $stmt = $db->prepare($query);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':amount', $amount);
                $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
                $stmt->bindParam(':description', $description);
    
                if ($stmt->execute()) {
                    $_SESSION['messages'][] = "✅ Transakcja została dodana!";
                    $_SESSION['success'] = true;
                    header("Location: /main?year=$selectedYear&month=$selectedMonth");
                    exit();
                } else {
                    $_SESSION['messages'][] = "❌ Błąd: Wystąpił problem podczas dodawania transakcji.";
                }
            } catch (Exception $e) {
                $_SESSION['messages'][] = "❌ Błąd: " . $e->getMessage();
            }
    
            header("Location: /main?year=$selectedYear&month=$selectedMonth");
            exit();
        }
    }
    
    private function getUserExpenses($user_id, $year, $month) {
        $database = new Database();
        $db = $database->connect();
    
        $query = "SELECT e.id, e.amount, c.name AS category, e.description, e.date
                  FROM expenses e
                  JOIN categories c ON e.category_id = c.id
                  WHERE e.user_id = :user_id
                  AND EXTRACT(YEAR FROM e.date) = :year
                  AND EXTRACT(MONTH FROM e.date) = :month
                  ORDER BY e.date DESC";
    
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->bindParam(':month', $month, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getUserIncomes($user_id, $year, $month) {
        $database = new Database();
        $db = $database->connect();
    
        $query = "SELECT i.id, i.amount, ic.name AS category, i.description, i.date
                  FROM incomes i
                  JOIN income_categories ic ON i.category_id = ic.id
                  WHERE i.user_id = :user_id
                  AND EXTRACT(YEAR FROM i.date) = :year
                  AND EXTRACT(MONTH FROM i.date) = :month
                  ORDER BY i.date DESC";
    
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->bindParam(':month', $month, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function deleteTransaction() {
        $this->requireLogin();
    
        header('Content-Type: application/json');
    
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $transactionId = $data['id'] ?? null;
            $type = $data['type'] ?? null;
            $year = $data['year'] ?? date('Y');
            $month = $data['month'] ?? date('m');
    
            if (!$transactionId || !$type) {
                echo json_encode(["success" => false, "message" => "Nieprawidłowe dane"]);
                exit();
            }
    
            $database = new Database();
            $db = $database->connect();
    
            session_start();
            $user_id = $_SESSION['user_id'];
    
            if ($type === "expense") {
                $query = "DELETE FROM expenses WHERE id = :id AND user_id = :user_id";
            } else {
                $query = "DELETE FROM incomes WHERE id = :id AND user_id = :user_id";
            }
    
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $transactionId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                // Pobieramy nowe wartości dla aktualnego miesiąca
                $summaryData = $this->getSummaryData($user_id, $year, $month);
                $expenses = $this->getUserExpenses($user_id, $year, $month);
                $incomes = $this->getUserIncomes($user_id, $year, $month);
    
                echo json_encode([
                    "success" => true,
                    "newBudget" => number_format($summaryData['budget'], 2, '.', ' '),
                    "newIncome" => number_format($summaryData['total_income'], 2, '.', ' '),
                    "newExpense" => number_format($summaryData['total_expense'], 2, '.', ' '),
                    "expenses" => $expenses,
                    "incomes" => $incomes
                ]);
            } else {
                echo json_encode(["success" => false, "message" => "Nie znaleziono transakcji"]);
            }
        } catch (Exception $e) {
            echo json_encode(["success" => false, "message" => "Błąd serwera: " . $e->getMessage()]);
        }
        exit();
    }
    
    
    
    
    
    


}
