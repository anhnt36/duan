<?php
class FT_Controller_Loader{
	public function __construct() {
		$this->load('User');
	}

	public function load($controller) {
		if(!file_exists(PATH_APPLICATION . '/controllers/' . $controller . '_Controller.php')) {
			die('Controller not found !!!');
		}
		require_once PATH_APPLICATION . '/controllers/'.$controller.'_Controller.php';
	}
}