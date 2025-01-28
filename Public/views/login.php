<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
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
            <div class="login-container">
                <form class="login" action="/login_user" method="POST">
                    <div class="messages">
                        <?php
                            if(isset($messages)){
                                foreach($messages as $message){
                                    echo $message;
                                }
                            }
                        ?>
                    </div>
                    <label for="email">e-mail</label>
                    <input id="email" type="text" name="email" required>

                    <label for="password">hasło</label>
                    <div class="password_image">
                        <input id="password" type="password" name="password" required>
                        <img src="Public/Images/closed_eye_password.png" alt="eye" id="eye">
                    </div>

                    <a id="forgot-password-link" href="#">zapomniałeś hasła?</a>
                    <button id="login-button" type="submit">zaloguj</button>
                </form>
                <a id="no-account-link" href="register">nie masz konta?</a>
            </div>
        </div>
    </div>
    <script src="Public/js/script.js"></script>
</body>
</html>