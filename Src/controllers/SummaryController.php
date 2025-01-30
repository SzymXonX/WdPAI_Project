<?php

require_once 'AppController.php';
require_once 'Database.php';

class SummaryController extends AppController {

    public function summary() {
        $this->requireLogin();
    
        session_start();
        $user_id = $_SESSION['user_id'];

        $selectedYear = $_GET['year'] ?? date('Y');
        $selectedMonth = $_GET['month'] ?? date('m');

        $expensesSummary = $this->getExpensesSummary($user_id, $selectedYear, $selectedMonth);
        $incomesSummary = $this->getIncomesSummary($user_id, $selectedYear, $selectedMonth);

        if ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode([
                "expensesSummary" => $expensesSummary,
                "incomesSummary" => $incomesSummary
            ]);
            exit();
        }

        $this->render('summary', [
            'expensesSummary' => $expensesSummary,
            'incomesSummary' => $incomesSummary,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth
        ]);
    }

    private function getExpensesSummary($user_id, $year, $month) {
        $database = new Database();
        $db = $database->connect();

        $query = "
            SELECT c.name AS category, COALESCE(SUM(e.amount), 0) AS total
            FROM categories c
            LEFT JOIN expenses e ON e.category_id = c.id 
                AND e.user_id = :user_id
                AND EXTRACT(YEAR FROM e.date) = :year 
                AND EXTRACT(MONTH FROM e.date) = :month
            GROUP BY c.name
            ORDER BY total DESC
        ";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':month', $month);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getIncomesSummary($user_id, $year, $month) {
        $database = new Database();
        $db = $database->connect();

        $query = "
            SELECT ic.name AS category, COALESCE(SUM(i.amount), 0) AS total
            FROM income_categories ic
            LEFT JOIN incomes i ON i.category_id = ic.id 
                AND i.user_id = :user_id
                AND EXTRACT(YEAR FROM i.date) = :year 
                AND EXTRACT(MONTH FROM i.date) = :month
            GROUP BY ic.name
            ORDER BY total DESC
        ";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':month', $month);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
