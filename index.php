<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Router::get('', 'DefaultController');
Router::get('login', 'DefaultController');
Router::get('register', 'DefaultController');

Router::get('main', 'MainController');

Router::get('settings', 'SettingsController');

Router::get('categories', 'CategoriesController');

Router::get('summary', 'SummaryController');

Router::get('admin', 'AdminController');
Router::get('editUser', 'AdminController');
Router::get('deleteUser', 'AdminController');
Router::get('getUser', 'AdminController'); 



Router::post('login_user', 'SecurityController');
Router::post('register_user', 'SecurityController');
Router::post('logout', 'SecurityController');

Router::post('changeData', 'SettingsController');

Router::post('add', 'MainController');

Router::post('deleteTransaction', 'MainController');

Router::post('addCategory', 'CategoriesController');
Router::post('deleteCategory', 'CategoriesController');

Router::post('editUser', 'AdminController');
Router::post('updateUser', 'AdminController');


Router::run($path);
