<?php
class Validate_Library {
	public static $error=array();
	public static $rules=array();
	public static $message=array(
		'required'			=> "Field %s isn't be empty !",
		'min' 				=> "Field %s isn't be less than %d characters !" ,
		'natural_number' 	=> "Field %s must be natural number !",
		'email'				=> "Field %s must be email !"
	);
	public function __construct($rules) {
		self::$rules = $rules;
	}

	
}


?>