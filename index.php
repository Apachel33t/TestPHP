<?php
define('__HOSTNAME__', 'localhost');
define('__USERNAME__', 'postgres');
define('__PASSWORD__', '123');
define('__DATABASENAME__', 'Test');
define('__UPLOAD_PATH__', __DIR__."\upload\\");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,HEAD,OPTIONS");
header("Access-Control-Allow-Headers: Origin,Content-Type,Accept,Authorization");
header("Access-Control-Allow-Headers: *");
header("Accept-Encoding: *");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,HEAD,OPTIONS");
    header("Access-Control-Allow-Headers: Origin,Content-Type,Accept,Authorization");
    header("Content-Type: *");
    header("Content-Length: 0");
}

spl_autoload_register(function ($className) {
   if (file_exists('System/'.$className.'.php')) {
       require_once 'System/'.$className.'.php';
   }
   elseif (file_exists('Controllers/'.$className.'.php')) {
       require_once 'Controllers/'.$className.'.php';
   }
   elseif (file_exists('Models/'.$className.'.php')) {
       require_once 'Models/'.$className.'.php';
   }
   elseif (file_exists($className.'.php')) {
       require_once $className.'.php';
   }
});

new App();