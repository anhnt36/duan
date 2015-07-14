<?php
class FT_View_Loader {
	private $__content = array();
	public function load($main , $data=array() , $content ='' , $error=array()) {
		require_once PATH_APPLICATION.'/views/'.$main.'.php';
	}
	
}