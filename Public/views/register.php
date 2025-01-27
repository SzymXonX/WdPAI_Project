<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="Public/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Anton+SC&display=swap" rel="stylesheet">
    <link rel="icon" href="Public/Images/logo_bez_tla.png" type="image/png">
</head>
<body>
    <div class="container">
        <div class="left-container">
            <div class="image-container">
                <img src="Public/Images/logo.png" alt="Logo">
            </div>
        </div>
        <div class="right-container">
            <div class="register-container">
                <form>
                    <label for="email">e-mail</label>
                    <input id="email" type="text" required>
                    <label for="password">hasło</label>
                    <div class="password_image">
                        <input id="password" type="password" required>
                        <img src="Public/Images/closed_eye_password.png" alt="eye" id="eye">
                    </div>
                    <label for="confirm-password">potwierdź hasło</label>
                    <div class="password_image">
                        <input id="confirm-password" type="password" required>
                        <img src="Public/Images/closed_eye_password.png" alt="eye" id="confirm-eye">
                    </div>
                    <button id="register-button" type="submit">zarejestruj</button>
                </form>
                <a id="login-link" href="index">masz już konto?</a>
            </div>
        </div>
    </div>
    <script src="Public/js/script.js"></script>
</body>
</html>
