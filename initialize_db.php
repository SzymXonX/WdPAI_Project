<?php
require_once 'Database.php';
require_once __DIR__ . '/Src/models/User.php'; // Upewnij się, że ścieżka do pliku User.php jest poprawna

function initializeDatabase() {
    $database = new Database();
    $db = $database->connect();

    // Sprawdzenie, czy którakolwiek tabela zawiera dane
    $tables = ['users', 'incomes', 'expenses', 'categories', 'income_categories', 'summary'];
    foreach ($tables as $table) {
        $stmt = $db->query("SELECT COUNT(*) FROM $table");
        if ($stmt->fetchColumn() > 0) {
            echo "🔹 Baza danych już zawiera dane. Pomijam inicjalizację.\n";
            return;
        }
    }

    echo "⚡ Inicjalizacja bazy danych...\n";

    // Tworzenie użytkowników za pomocą klasy User
    $users = [
        new User(null, "koczurszymon@gmail.com", "1234", "Szymon", "Koczur", "user"),
        new User(null, "admin@example.com", "admin", "Admin", "Admin", "admin"),
        new User(null, "maria@o2.pl", "maria123", "Maria", "Koczur", "user")
    ];

    try {
        foreach ($users as $user) {
            $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->bindParam(':email', $user->getEmail());
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                $hashedPassword = password_hash($user->getPassword(), PASSWORD_BCRYPT);

                $stmt = $db->prepare("INSERT INTO users (email, password, first_name, last_name, role) 
                                      VALUES (:email, :password, :first_name, :last_name, :role)");
                $stmt->bindParam(':email', $user->getEmail());
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->bindParam(':first_name', $user->getFirstName());
                $stmt->bindParam(':last_name', $user->getLastName());
                $stmt->bindParam(':role', $user->getRole());
                $stmt->execute();

                echo "✅ Użytkownik " . $user->getEmail() . " dodany.\n";
            } else {
                echo "ℹ️ Użytkownik " . $user->getEmail() . " już istnieje.\n";
            }
        }

        // Wczytanie danych z `data.sql`
        $sql = file_get_contents(__DIR__ . '/data.sql');
        $db->exec($sql);
        echo "✅ Załadowano `data.sql`.\n";

    } catch (Exception $e) {
        echo "❌ Błąd inicjalizacji bazy: " . $e->getMessage() . "\n";
    }
}

// Uruchomienie funkcji inicjalizacji bazy danych
initializeDatabase();
?>
