<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $url = "http://$_SERVER[HTTP_HOST]/login";
    header("Location: $url");
    exit;
}
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
            <li><a href="categories">kategorie</a></li>
            <li><a href="#">podsumowanie</a></li>
            <li><a href="settings" class="active">ustawienia</a></li>
        </ul>
        <div class="menu-icon" id="menu-toggle">
            <svg width="40" height="35" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0.78125 3.51562H21.0938C21.5252 3.51562 21.875 3.16587 21.875 2.73438V0.78125C21.875 0.349756 21.5252 0 21.0938 0H0.78125C0.349756 0 0 0.349756 0 0.78125V2.73438C0 3.16587 0.349756 3.51562 0.78125 3.51562ZM0.78125 11.3281H21.0938C21.5252 11.3281 21.875 10.9784 21.875 10.5469V8.59375C21.875 8.16226 21.5252 7.8125 21.0938 7.8125H0.78125C0.349756 7.8125 0 8.16226 0 8.59375V10.5469C0 10.9784 0.349756 11.3281 0.78125 11.3281ZM0.78125 19.1406H21.0938C21.5252 19.1406 21.875 18.7909 21.875 18.3594V16.4062C21.875 15.9748 21.5252 15.625 21.0938 15.625H0.78125C0.349756 15.625 0 15.9748 0 16.4062V18.3594C0 18.7909 0.349756 19.1406 0.78125 19.1406Z" fill="black"/>
            </svg>
        </div>
    </nav>

    <div class="sidebar-menu" id="sidebar-menu">
        <ul>
            <li><a href="main">strona główna</a></li>
            <li><a href="categories">kategorie</a></li>
            <li><a href="#">podsumowanie</a></li>
            <li><a href="settings" class="active">ustawienia</a></li>
        </ul>
    </div>



    <main class="content">
        <div class="container">
            <form action="/changeData" method="POST">
                <?php if (isset($messages) && is_array($messages)): ?>
                    <?php foreach ($messages as $message): ?>
                        <p style="color: <?= (stripos($message, 'błąd') !== false || stripos($message, 'error') !== false) ? 'red' : 'green'; ?>;">
                            <?= htmlspecialchars($message); ?>
                        </p>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="input-group">
                    <label for="first_name">imię</label>
                    <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? ''); ?>" required>
                </div>
                <div class="input-group">
                    <label for="last_name">nazwisko</label>
                    <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? ''); ?>" required>
                </div>
                <div class="input-group">
                    <label for="email">e-mail</label>
                    <input type="email" id="email" name="email" autocomplete="new-email" value="<?= htmlspecialchars($user['email'] ?? ''); ?>" required>
                </div>
                <div class="input-group">
                    <label for="password">nowe hasło</label>
                    <input id="password" type="password" name="password" autocomplete="new-password">
                    <img src="Public/Images/closed_eye_password.png" alt="eye" id="eye">
                </div>
                <div class="input-group">
                    <label for="confirm-password">powtórz hasło</label>
                    <input id="confirm-password" type="password" name="confirm-password" autocomplete="new-password">
                    <img src="Public/Images/closed_eye_password.png" alt="eye" id="confirm-eye">
                </div>
                <div class="buttons">
                    <button type="submit" class="btn btn-save">zmień dane</button>
                </div>
            </form>
            <form action="/logout" method="POST">
                <div class="buttons">
                    <button type="submit" class="btn btn-logout">wyloguj</button>
                </div>
            </form>
        </div>
    </main>
    <script src="Public/js/menuScript.js"></script>
</body>
</html>
