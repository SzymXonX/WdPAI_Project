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
    <title>savespace</title>
    <link href="https://fonts.googleapis.com/css2?family=Anton+SC&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jua&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/mainStyles.css">
    <link rel="icon" href="public/images/logo_bez_tla.png" type="image/png">
</head>
<body>
    <nav class="navbar">
            <div class="logo">
                <img src="public/images/logo_bez_tla.png" alt="savespace logo">
                <span>SaveSpace</span>
            </div>
            <ul class="nav-links">
                <li><a href="main" class="active">strona główna</a></li>
                <li><a href="#">wydatki</a></li>
                <li><a href="#">przychody</a></li>
                <li><a href="categories">kategorie</a></li>
                <li><a href="settings">ustawienia</a></li>
            </ul>
            <div class="menu-icon" id="menu-toggle">
                <svg width="40" height="35" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="m0.78125 3.51562h20.3125c0.4315 0 0.78125-0.349756 0.78125-0.78125v-1.95312c0-0.431494-0.34975-0.78125-0.78125-0.78125h-20.3125c-0.431494 0-0.78125 0.349756-0.78125 0.78125v1.95312c0 0.431494 0.349756 0.78125 0.78125 0.78125zm0 7.81248h20.3125c0.4315 0 0.78125-0.349756 0.78125-0.78125v-1.95312c0-0.431494-0.34975-0.78125-0.78125-0.78125h-20.3125c-0.431494 0-0.78125 0.349756-0.78125 0.78125v1.95312c0 0.431494 0.349756 0.78125 0.78125 0.78125zm0 7.81248h20.3125c0.4315 0 0.78125-0.349756 0.78125-0.78125v-1.95312c0-0.431494-0.34975-0.78125-0.78125-0.78125h-20.3125c-0.431494 0-0.78125 0.349756-0.78125 0.78125v1.95312c0 0.431494 0.349756 0.78125 0.78125 0.78125z" fill="black"/>
                </svg>
            </div>
    </nav>

    <div class="sidebar-menu" id="sidebar-menu">
        <ul>
            <li><a href="main" class="active">strona główna</a></li>
            <li><a href="#">wydatki</a></li>
            <li><a href="#">przychody</a></li>
            <li><a href="categories">kategorie</a></li>
            <li><a href="settings">ustawienia</a></li>
        </ul>
    </div>

    <main class="content">
        <div class="dashboard-container">
            <div class="dashboard-row">
                <div class="summary-container">
                    <div class="summary-item">
                        <span id="summary-wydatki" class="summary-label">WYDATKI</span>
                        <input id="summary-wydatki" class="summary-value" type="text" value="2000.00 zł" readonly>
                    </div>
                    <div class="summary-item">
                        <span id="summary-przychody" class="summary-label">PRZYCHODY</span>
                        <input id="summary-przychody" class="summary-value" type="text" value="4500.00 zł" readonly>
                    </div>
                    <div class="summary-item">
                        <span id="summary-budzet" class="summary-label">BUDŻET</span>
                        <input id="summary-budzet" class="summary-value" type="text" value="2500.00 zł" readonly>
                    </div>
                </div>

                <form class="form-container">
                    <div class="form-content">
                        <div class="form-row">
                            <div class="form-header">
                                <button type="button" class="transaction-type-btn active" data-type="expense">Wydatek</button>
                                <button type="button" class="transaction-type-btn" data-type="income">Przychód</button>
                            </div>

                            <div class="form-group">
                                <label for="amount">Kwota</label>
                                <input type="number" id="amount" step="0.01" placeholder="0.00">
                            </div>
                        </div>

                        <input type="hidden" id="transaction-type" name="transaction-type" value="expense">

                        <div class="form-group">
                            <label for="category">kategoria</label>
                            <select id="category">
                                <option value="food">jedzenie</option>
                                <option value="transport">transport</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">opis</label>
                            <textarea id="description" rows="3"></textarea>
                        </div>

                        <button class="add-button">dodaj</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="transactions-container">
            <div class="expenses-container">
                <h2 class="section-title">wydatki</h2>
                <div class="transactions-list" id="expenses-list">
                    <!-- wydatki -->
                </div>
            </div>

            <div class="incomes-container">
                <h2 class="section-title">przychody</h2>
                <div class="transactions-list" id="incomes-list">
                    <!-- przychody -->
                </div>
            </div>
        </div>
    </main>

    <script src="public/js/menuscript.js"></script>
</body>
</html>
