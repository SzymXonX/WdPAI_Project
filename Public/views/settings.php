<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Jeśli użytkownik nie jest zalogowany, przekieruj go na stronę logowania
    $url = "http://$_SERVER[HTTP_HOST]/login";
    header("Location: $url");
    exit;
}
// Jeśli użytkownik jest zalogowany, kontynuuj wyświetlanie strony
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaveSpace</title>
    <link href="https://fonts.googleapis.com/css2?family=Anton+SC&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jua&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Public/css/settingsStyles.css">
    <link rel="icon" href="Public/Images/logo_bez_tla.png" type="image/png">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="Public/Images/logo_bez_tla.png" alt="SaveSpace Logo">
            <span>SaveSpace</span>
        </div>
        <ul class="nav-links">
            <li><a href="main">strona główna</a></li>
            <li><a href="#">wydatki</a></li>
            <li><a href="#">przychody</a></li>
            <li><a href="#">kategorie</a></li>
            <li><a href="settings" class="active">ustawienia</a></li>
        </ul>
    </nav>
    <main class="content">
        
    </main>
</body>
</html>
