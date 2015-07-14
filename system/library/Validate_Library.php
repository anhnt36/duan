<?php
class Validate_Library {
	
	public static $model=NULL;
	public static $user=NULL;
	public function __construct(){
		
	}
	public function getError(){
		return self::$_error;
	}

	public function fileValidate(){
		$typeFile=array('image/gif','image/jpeg','image/jpg','image/png');
		if(is_uploaded_file($_FILES['avatar']['tmp_name'])) {
			if(in_array($_FILES['avatar']['type'],$typeFile)) {
				move_uploaded_file($_FILES['avatar']['tmp_name'], 'public/img/'.$_FILES['avatar']['name']);
				return true;
			}
		}
		return false;
	}

	// public $messages = array(
	// 		'required' => 'this :attribute is not empty'
	// 	)
}
?>