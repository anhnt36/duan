<?php if(!defined('PATH_SYSTEM')) die ('Bad request!');
function parseUrl() {
	if (isset($_GET['url'])) {
		//echo$_GET['url'];
		return $url= explode('/', (filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)));
	}
}
/*
	automatically delete files in 'tmp' file
*/
function autoDeleteFile(){
	$dir = SOURCE . '/public/img/tmp/';
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
	        while (($file = readdir($dh)) !== false) {
	        	if(is_file($dir . $file)) {
	        		if((time() - filectime($dir.$file)) > 86400) {
	        			unlink($dir . $file);
	        		}
		        }
	        }
	        closedir($dh);
	    }
	}
}

function process() {
	$arrayUrl = parseUrl();
	if(empty($arrayUrl)){
		header("Location:" . base_url . '/user/show');
		die();
	}
	$controller = empty($arrayUrl[0]) ? '': $arrayUrl[0];
	$action = empty($arrayUrl[1]) ? '' : $arrayUrl[1];
	
	$controller = ucfirst(strtolower($controller)) . '_Controller';
	$action = strtolower($action);

	if(!file_exists(PATH_APPLICATION . '/controllers/'  .$controller . '.php')) {
		header("Location:" . base_url . '/user/show');
		die();
	}
	require_once PATH_APPLICATION . '/controllers/' . $controller . '.php';

	if(!class_exists($controller)) {
		header("Location:" . base_url . '/user/show');
		die();
	}
	$controllerObject = new $controller();
	if (!method_exists($controllerObject, $action)) {
		header("Location:" . base_url . '/user/show');
		die();
	}
	$controllerObject->{$action}();
}
/*
	Load Url
*/
function FT_load() {
	$config = require_once PATH_APPLICATION . '/config/init.php';
	autoDeleteFile();
	
	$arrayUrl = parseUrl();
	
	if(!empty($_SESSION['name'])) {
		process();
	} else {

		if(!empty($_COOKIE['name'])){
			$_SESSION['name'] = $_COOKIE['name'];
			$_SESSION['id'] = $_COOKIE['id'];
			$_SESSION['avatar'] = $_COOKIE['avatar'];
		} else {

			if($arrayUrl[0] == 'user' && $arrayUrl[1] == 'login') {
				$controllerObject = new User_Controller();
				$controllerObject->login();
			} else {
				headerUrl('/user/login');
			}
		}
	}
}

function headerUrl($path) {
	header('Location:'.base_url.$path);
}