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
    <link rel="stylesheet" href="public/css/adminStyles.css">
    <link rel="icon" href="public/images/logo_bez_tla.png" type="image/png">
</head>
<body>
    <nav class="navbar">
            <div class="logo">
                <img src="public/images/logo_bez_tla.png" alt="SaveSpace logo">
                <span>SaveSpace</span>
            </div>
            <ul class="nav-links">
                <li><a href="main" >strona główna</a></li>
                <li><a href="categories">kategorie</a></li>
                <li><a href="summary">podsumowanie</a></li>
                <li><a href="settings">ustawienia</a></li>
                <li><a href="admin" class="active">Admin</a></li>
            </ul>
            <div class="menu-icon" id="menu-toggle">
                <svg width="40" height="35" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="m0.78125 3.51562h20.3125c0.4315 0 0.78125-0.349756 0.78125-0.78125v-1.95312c0-0.431494-0.34975-0.78125-0.78125-0.78125h-20.3125c-0.431494 0-0.78125 0.349756-0.78125 0.78125v1.95312c0 0.431494 0.349756 0.78125 0.78125 0.78125zm0 7.81248h20.3125c0.4315 0 0.78125-0.349756 0.78125-0.78125v-1.95312c0-0.431494-0.34975-0.78125-0.78125-0.78125h-20.3125c-0.431494 0-0.78125 0.349756-0.78125 0.78125v1.95312c0 0.431494 0.349756 0.78125 0.78125 0.78125zm0 7.81248h20.3125c0.4315 0 0.78125-0.349756 0.78125-0.78125v-1.95312c0-0.431494-0.34975-0.78125-0.78125-0.78125h-20.3125c-0.431494 0-0.78125 0.349756-0.78125 0.78125v1.95312c0 0.431494 0.349756 0.78125 0.78125 0.78125z" fill="black"/>
                </svg>
            </div>
    </nav>

    <div class="sidebar-menu" id="sidebar-menu">
        <ul>
            <li><a href="main" >strona główna</a></li>
            <li><a href="categories">kategorie</a></li>
            <li><a href="summary">podsumowanie</a></li>
            <li><a href="settings">ustawienia</a></li>
            <li><a href="admin" class="active">Admin</a></li>
        </ul>
    </div>

    <main class="admin-container">
        <h1 class="admin-header">Panel Administratora</h1>

        <table class="users-table">
            <thead>
                <tr>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>Email</th>
                    <th>Rola</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td data-label="Imię"><?= htmlspecialchars($user['first_name']) ?></td>
                        <td data-label="Nazwisko"><?= htmlspecialchars($user['last_name']) ?></td>
                        <td data-label="Email"><?= htmlspecialchars($user['email']) ?></td>
                        <td data-label="Rola">
                            <span class="role-badge <?= $user['role'] === 'admin' ? 'role-admin' : 'role-user' ?>">
                                <?= strtoupper($user['role']) ?>
                            </span>
                        </td>
                        <td data-label="Akcje">
                            <button class="edit-button" onclick="editUser(<?= $user['id'] ?>)">Edytuj</button>
                            <?php if ($user['role'] !== 'admin'): ?>
                                <button class="delete-button" data-user-id="<?= $user['id'] ?>">Usuń</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>


        </table>

        <!-- Formularz edycji użytkownika -->
        <div id="edit-user-modal" class="edit-user-container" style="display: none;">
            <h2>Edytuj użytkownika</h2>
            <form id="edit-user-form" class="edit-user-form">
                <input type="hidden" id="edit-user-id">
                
                <label for="edit-first-name">Imię:</label>
                <input type="text" id="edit-first-name" required>

                <label for="edit-last-name">Nazwisko:</label>
                <input type="text" id="edit-last-name" required>

                <label for="edit-email">Email:</label>
                <input type="email" id="edit-email" required>

                <label for="edit-role">Rola:</label>
                <select id="edit-role">
                    <option value="user">Użytkownik</option>
                    <option value="admin">Administrator</option>
                </select>

                <label for="edit-password">Nowe hasło:</label>
                <input type="password" id="edit-password" placeholder="Pozostaw puste, aby nie zmieniać" autocomplete="new-password">


                <button type="submit" class="save-button">Zapisz zmiany</button>
            </form>
        </div>
    </main>
    <script src="public/js/adminScript.js"></script>
</body>
</html>
