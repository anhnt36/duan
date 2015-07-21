<?php
class FT_Library_Loader{
	public function __construct(){
		$this->load('Validate');
		$this->load('Pagination');
	}
	public function load($library, $agrs = array()){
		if(empty($this->{$library})){
			$class= ucfirst($library) . '_Library';
			require_once(PATH_SYSTEM . '/library/' . $class . '.php');
			//$this->{$library}= new $class($agrs);
		}
	}
}