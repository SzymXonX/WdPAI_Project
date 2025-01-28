<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Router::get('', 'DefaultController');
Router::get('login', 'DefaultController');
Router::get('register', 'DefaultController');
Router::get('main', 'MainController');
Router::get('settings', 'SettingsController');

Router::post('login_user', 'SecurityController');
Router::post('register_user', 'SecurityController');
Router::post('logout', 'SecurityController');
Router::post('changeData', 'SettingsController');

Router::run($path);
