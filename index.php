<?php
define('PATH_SYSTEM',__DIR__.'/system');
define('PATH_APPLICATION',__DIR__.'/app');

require_once PATH_SYSTEM . '/config/config.php';
require_once PATH_SYSTEM . '/core/FT_Common.php';

function __autoload($class) {
    $arrayPath = array(
        PATH_SYSTEM . '/core/',
        PATH_SYSTEM . '/core/loader/'
    );

    foreach ($arrayPath as $path) {
        if(file_exists($path . "{$class}.php")) {
            require_once $path . "{$class}.php";
        }
    }
}

$controller1 = new FT_Controller();

session_start();
FT_load();
