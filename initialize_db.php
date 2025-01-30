<?php
require_once 'Database.php';

function initializeDatabase() {
    $database = new Database();
    $db = $database->connect();

    $users = [
        [
            "email" => "koczurszymon@gmail.com",
            "password" => "1234",
            "first_name" => "Szymon",
            "last_name" => "Koczur"
        ],
        [
            "email" => "admin@example.com",
            "password" => "admin",
            "first_name" => "Admin",
            "last_name" => "Admin"
        ],
        [
            "email" => "maria35maria@o2.pl",
            "password" => "maria123",
            "first_name" => "Maria",
            "last_name" => "Koczur"
        ]
    ];

    try {
        foreach ($users as $user) {
            $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->bindParam(':email', $user['email']);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                $hashedPassword = password_hash($user['password'], PASSWORD_BCRYPT);
                
                $stmt = $db->prepare("INSERT INTO users (email, password, first_name, last_name) 
                                      VALUES (:email, :password, :first_name, :last_name)");
                $stmt->bindParam(':email', $user['email']);
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->bindParam(':first_name', $user['first_name']);
                $stmt->bindParam(':last_name', $user['last_name']);
                $stmt->execute();

                echo "✅ Użytkownik " . $user['email'] . " dodany.\n";
            } else {
                echo "Użytkownik " . $user['email'] . " już istnieje.\n";
            }
        }

        // Wczytanie reszty danych z `data.sql`
        $sql = file_get_contents(__DIR__ . '/data.sql');
        $db->exec($sql);
        echo "✅ Załadowano `data.sql`.\n";

    } catch (Exception $e) {
        echo "❌ Błąd inicjalizacji bazy: " . $e->getMessage() . "\n";
    }
}

// Uruchomienie skryptu
initializeDatabase();
?>
