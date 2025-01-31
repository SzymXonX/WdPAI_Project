<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $url = "http://$_SERVER[HTTP_HOST]/login";
    header("Location: $url");
    exit;
}
$database = new Database();
$db = $database->connect();

$stmt = $db->prepare("SELECT getRole(:user_id)");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$role = $stmt->fetchColumn();

$isAdmin = ($role === 'admin');
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaveSpace</title>
    <link href="https://fonts.googleapis.com/css2?family=Anton+SC&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jua&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/categoriesStyles.css">
    <link rel="icon" href="public/images/logo_bez_tla.png" type="image/png">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="public/images/logo_bez_tla.png" alt="SaveSpace Logo">
            <span>SaveSpace</span>
        </div>
        <ul class="nav-links">
            <li><a href="main">strona główna</a></li>
            <li><a href="categories" class="active">kategorie</a></li>
            <li><a href="summary">podsumowanie</a></li>
            <li><a href="settings">ustawienia</a></li>
            <?php if ($isAdmin): ?> 
                <li><a href="admin">admin</a></li>
            <?php endif; ?>
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
            <li><a href="categories" class="active">kategorie</a></li>
            <li><a href="summary">podsumowanie</a></li>
            <li><a href="settings">ustawienia</a></li>
            <?php if ($isAdmin): ?> 
                <li><a href="admin">admin</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <main class="content">
        <?php if (isset($_SESSION['messages']) && !empty($_SESSION['messages'])): ?>
            <div class="<?= isset($_SESSION['success']) && $_SESSION['success'] ? 'success-messages' : 'error-messages' ?>">
                <?php foreach ($_SESSION['messages'] as $message): ?>
                    <p><?= htmlspecialchars($message); ?></p>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['messages'], $_SESSION['success']); ?>
        <?php endif; ?>

        <div class="category-form-container">
            <form class="form-container" method="POST" action="/addCategory">
                <div class="form-header">
                    <button type="button" class="transaction-type-btn active" data-type="expense">wydatek</button>
                    <button type="button" class="transaction-type-btn" data-type="income">przychód</button>
                </div>

                <input type="hidden" id="category-type" name="category-type" value="expense">

                <div class="form-group">
                    <label for="category-name">nazwa kategorii</label>
                    <input type="text" id="category-name" name="category-name" required>
                </div>

                <button type="submit" class="add-button">dodaj kategorię</button>
            </form>
        </div>

        <div class="categories-container">
            <div class="expenses-container">
                <h2 class="section-title">kategorie wydatków</h2>
                <ul id="expense-category-list">
                    <?php foreach ($categories as $category): ?>
                        <li>
                            <span><?= htmlspecialchars($category->getName()); ?></span>
                            <button class="delete-category-btn" onclick="deleteCategory(<?= $category->getId(); ?>, 'expense')">usuń</button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="incomes-container">
                <h2 class="section-title">kategorie przychodów</h2>
                <ul id="income-category-list">
                    <?php foreach ($incomeCategories as $category): ?>
                        <li>
                            <span><?= htmlspecialchars($category->getName()); ?></span>
                            <button class="delete-category-btn" onclick="deleteCategory(<?= $category->getId(); ?>, 'income')">usuń</button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

    </main>

    <script src="public/js/categoriesScript.js"></script>
</body>
</html>
