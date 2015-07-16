<?php
define('PATH_SYSTEM',__DIR__.'/system');
define('PATH_APPLICATION',__DIR__.'/app');
define('SOURCE',__DIR__);

_autoload();
$controller1= new FT_Controller();



function _autoload(){
	require_once PATH_SYSTEM.'/config/config.php';
	require_once PATH_SYSTEM .'/core/FT_Common.php';
	require_once PATH_SYSTEM .'/core/FT_Controller.php';
	require_once PATH_SYSTEM .'/core/FT_Model.php';
	require_once PATH_SYSTEM.'/core/loader/FT_Config_Loader.php';
	require_once PATH_SYSTEM.'/core/loader/FT_Helper_Loader.php';
	require_once PATH_SYSTEM.'/core/loader/FT_Library_Loader.php';
	require_once PATH_SYSTEM.'/core/loader/FT_View_Loader.php';
	require_once PATH_SYSTEM.'/core/loader/FT_Model_Loader.php';
	require_once PATH_SYSTEM.'/core/loader/FT_Controller_Loader.php';
}


FT_load();
