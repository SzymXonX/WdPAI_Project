<?php

require_once 'src/controllers/DefaultController.php';
require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/MainController.php';
require_once 'src/controllers/SettingsController.php';
require_once 'src/controllers/CategoriesController.php';
require_once 'src/controllers/SummaryController.php';
require_once 'src/controllers/AdminController.php';

class Router
{

    public static $routes;

    public static function get($url, $view){
        self::$routes[$url] = $view;
    }

    public static function post($url, $view){
        self::$routes[$url] = $view;
    }

    public static function run($url){
        $urlParts = explode("/", $url);
        $action = $urlParts[0];

        if (!array_key_exists($action, self::$routes)) {
            die("Page not found");
        }

        $controller = self::$routes[$action];
        $object = new $controller;
        $action = $action ?: 'login';

        $id = $urlParts[1] ?? '';

        $object->$action($id);
    }
}


