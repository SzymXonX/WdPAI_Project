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
                <li><a href="categories">kategorie</a></li>
                <li><a href="summary">podsumowanie</a></li>
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
            <li><a href="categories">kategorie</a></li>
            <li><a href="summary">podsumowanie</a></li>
            <li><a href="settings">ustawienia</a></li>
        </ul>
    </div>

    <main class="content">
        <!-- Wyświetlanie wiadomości na stronie -->
        <?php if (isset($_SESSION['messages']) && !empty($_SESSION['messages'])): ?>
            <div class="<?= isset($_SESSION['success']) ? 'success-messages' : 'error-messages' ?>">
                <?php foreach ($_SESSION['messages'] as $message): ?>
                    <p><?= htmlspecialchars($message); ?></p>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['messages'], $_SESSION['success']); ?>
        <?php endif; ?>

        <div class="dashboard-container">
            <div class="dashboard-row">
                <div class="summary-container">
                    <div class="date-selector">
                        <button id="prev-month" class="arrow-button">&lt;</button>
                        <span id="current-date" data-year="<?= $selectedYear ?>" data-month="<?= $selectedMonth ?>">
                            <?= $selectedYear . ' ' . strftime('%B', mktime(0, 0, 0, $selectedMonth, 1)); ?>
                        </span>
                        <button id="next-month" class="arrow-button">&gt;</button>
                    </div>


                    <div class="summary-items">
                        <div class="summary-item">
                            <span id="wydatki" class="summary-label">wydatki</span>
                            <input id="summary-wydatki" class="summary-value" type="text" value="<?= number_format($summaryData['total_expense'], 2, '.', ' ') . ' zł' ?>" readonly>
                        </div>
                        <div class="summary-item">
                            <span id="przychody" class="summary-label">przychody</span>
                            <input id="summary-przychody" class="summary-value" type="text" value="<?= number_format($summaryData['total_income'], 2, '.', ' ') . ' zł' ?>" readonly>
                        </div>
                        <div class="summary-item">
                            <span id="budzet" class="summary-label">budżet</span>
                            <input id="summary-budzet" class="summary-value" type="text" value="<?= number_format($summaryData['budget'], 2, '.', ' ') . ' zł' ?>" readonly>
                        </div>
                    </div>
                </div>


                <form class="form-container" method="POST" action="/add">
                    <div class="form-content">
                        <div class="form-row">
                            <div class="form-header">
                                <button type="button" class="transaction-type-btn active" data-type="expense">wydatek</button>
                                <button type="button" class="transaction-type-btn" data-type="income">przychód</button>
                            </div>

                            <div class="form-group">
                                <label for="amount">kwota</label>
                                <input type="number" id="amount" name="amount" step="0.01" placeholder="0.00" required>
                            </div>
                        </div>

                        <input type="hidden" id="transaction-type" name="transaction-type" value="expense">
                        <input type="hidden" id="selected-year" name="selected-year" value="<?= $selectedYear ?>">
                        <input type="hidden" id="selected-month" name="selected-month" value="<?= $selectedMonth ?>">


                        <div class="form-group">
                            <label for="category">Kategoria</label>
                            <select id="category" name="category">
                                <optgroup label="Kategorie wydatków" id="expense-categories">
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category->getId(); ?>"><?= htmlspecialchars($category->getName()); ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                                
                                <optgroup label="Kategorie przychodów" id="income-categories" style="display: none;">
                                    <?php foreach ($incomeCategories as $category): ?>
                                        <option value="<?= $category->getId(); ?>"><?= htmlspecialchars($category->getName()); ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            </select>
                        </div>

                        
                        <div class="form-group">
                            <label for="description">opis</label>
                            <input id="description" name="description" rows="3">
                        </div>

                        <button type="submit" class="add-button">dodaj</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="transactions-container">
            <div class="expenses-container">
                <h2 class="section-title">Wydatki</h2>
                <div class="transactions-list" id="expenses-list">
                    <?php if (!empty($expenses)): ?>
                        <?php foreach ($expenses as $expense): ?>
                            <div class="transaction" onclick="toggleTransactionDetails(this)">
                                <span class="transaction-category"><?= htmlspecialchars($expense['category']); ?></span>
                                <span class="transaction-amount negative">-<?= number_format($expense['amount'], 2, '.', ' ') ?> zł</span>
                                <span class="transaction-date"><?= date('d-m-Y', strtotime($expense['date'])); ?></span>

                                <div class="transaction-details">
                                    <p class="transaction-description"><?= htmlspecialchars($expense['description']); ?></p>
                                    <button class="delete-button" onclick="deleteTransaction(event, <?= $expense['id']; ?>, 'expense')">🗑 Usuń</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-transactions">Brak wydatków w tym miesiącu.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="incomes-container">
                <h2 class="section-title">Przychody</h2>
                <div class="transactions-list" id="incomes-list">
                    <?php if (!empty($incomes)): ?>
                        <?php foreach ($incomes as $income): ?>
                            <div class="transaction" onclick="toggleTransactionDetails(this)">
                                <span class="transaction-category"><?= htmlspecialchars($income['category']); ?></span>
                                <span class="transaction-amount positive">+<?= number_format($income['amount'], 2, '.', ' ') ?> zł</span>
                                <span class="transaction-date"><?= date('d-m-Y', strtotime($income['date'])); ?></span>

                                <div class="transaction-details">
                                    <p class="transaction-description"><?= htmlspecialchars($income['description']); ?></p>
                                    <button class="delete-button" onclick="deleteTransaction(event, <?= $income['id']; ?>, 'income')">🗑 Usuń</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-transactions">Brak przychodów w tym miesiącu.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>


    </main>

    <script src="public/js/menuscript.js"></script>
</body>
</html>
