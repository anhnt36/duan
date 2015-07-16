<?php if(!defined('PATH_SYSTEM')) die ('Bad request!');
function parseUrl() {
	if (isset($_GET['url'])) {
		//echo$_GET['url'];
		return $url= explode('/',(filter_var(rtrim($_GET['url'],'/'),FILTER_SANITIZE_URL)));
	}
}
/*
	automatically delete files in 'tmp' file
*/
function autoDeleteFile(){
	$dir = SOURCE.'/public/img/tmp/';
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
	        while (($file = readdir($dh)) !== false) {
	        	if(is_file($dir.$file)) {
	        		if((time()-filectime($dir.$file)) > 86400) {
	        			unlink($dir.$file);
	        		}
		        }
	        }
	        closedir($dh);
	    }
	}
}

function process() {
	$arrayUrl = parseUrl();
	$controller = empty($arrayUrl[0]) ? '': $arrayUrl[0];
	$action = empty($arrayUrl[1]) ? '' : $arrayUrl[1];
	if(empty($controller) && empty($action)){
		headerUrl('/user/home');
	}
	$controller = ucfirst(strtolower($controller)).'_Controller';
	$action = strtolower($action);
	if($action=='add') $action='edit';

	if(!file_exists(PATH_APPLICATION.'/controllers/'.$controller.'.php')) {
		die('Controller not found !!!');
	}
	require_once PATH_APPLICATION.'/controllers/'.$controller.'.php';

	if(!class_exists($controller)) {
		die('Controller not found !!!');
	}
	$controllerObject = new $controller();
	if (!method_exists($controllerObject, $action)) {
		die('Action not found !!!');
	}
	$controllerObject->{$action}();
}
/*
	Load Url
*/
function FT_load() {
	$config = require_once PATH_APPLICATION.'/config/init.php';
	autoDeleteFile();
	session_start();
	$arrayUrl = parseUrl();

	if(isset($_COOKIE['name'])){
		$_SESSION['name']= $_COOKIE['name'];
	}
	
	if(isset($_SESSION['name'])) {
		process();
	} else {
		if($arrayUrl[0] == 'user' && $arrayUrl[1] == 'login') {
			$controllerObject = new User_Controller();
			$controllerObject->login();
		} else {
			headerUrl('/user/login/');
		}
	}
}

function headerUrl($path) {
	header('Location:'.base_url.$path);
}