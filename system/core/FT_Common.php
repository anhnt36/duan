<?php if(!defined('PATH_SYSTEM')) die ('Bad request!');
function parseUrl() {
	if (isset($_GET['url'])) {
		//echo$_GET['url'];
		return $url= explode('/',(filter_var(rtrim($_GET['url'],'/'),FILTER_SANITIZE_URL)));
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
	// var_dump($controllerObject->{$action}());
}

function FT_load() {
	$config = require_once PATH_APPLICATION.'/config/init.php';

	session_start();
	$arrayUrl = parseUrl();

	if(isset($_COOKIE['name'])){
		$_SESSION['name']= $_COOKIE['name'];
	}
	if(!isset($_SESSION['_id'])){
		$_SESSION['_id']= 'ASC';
		
	}
	if (!isset($_SESSION['_name'])) {
		$_SESSION['_name']= 'ASC';
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