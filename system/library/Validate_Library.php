<?php
class Validate_Library {
	public static $error=array();
	public static $rules=array();
	public static $message=array(
		'required'			=>	"Field %s isn't be empty !",
		'min' 				=>	"Field %s isn't be less than %d characters !" ,
		'natural_number' 	=>	"Field %s must be natural number !",
		'email'				=>	"Field %s must be email !"
	);

	public function __construct($law) {
		self::$rules = $law;
	}

	public function getError() {
		return self::$error;
	}
	/*
		Check data valid or invalid
	*/

	public function dataValidate($data){																				
		$arrayField = array_keys(self::$rules);
		foreach ($arrayField as $value) {
			$arrayRule = $this->rules("{$value}");

			foreach ($arrayRule as $rule) {
				if (!$this->$rule($value , $data["{$value}"])) {
					break;
				}
			}
		}
	}

	/*
		check the minimum number of characters
	*/

	protected function min($value ,$data) {																				
		$count = $this->xrules($value,'min');
		if(strlen($data) < $count) {
			self::$error["{$value}"] = $this->ruleMessage($value , 'min');
			return false;
		}
		return true;
	}

	/*
		check the minimum number of characters
	*/

	protected function natural_number($value, $data) {																				
		
		if(!filter_var($data,FILTER_VALIDATE_INT) || $data < 0) {
			self::$error["{$value}"] = $this->ruleMessage($value , 'natural_number');
			return false;
		}
		else return true;
	}

	/*
		Check data empty
	*/

	protected function required($value ,$data) {																			
		if(empty($data)) {
			self::$error["{$value}"] = $this->ruleMessage($value , 'required');
			return false;
		}
		return true;
	}

	/*
		Check email valid or invalid
	*/

	protected function email($value, $data) {																			
		if(!filter_var($data,FILTER_VALIDATE_EMAIL)) {
			self::$error["{$value}"] = $this->ruleMessage($value , 'email');
			return false;
		}
		return true;
	}

	/*
		analyze rules and return array (not value)
	*/

	public function rules($name) {																						
		$array=explode('|',self::$rules["$name"]);
		for ($i = 0; $i < count($array); $i++) {
			$array1= explode(':',$array[$i]);
			$array[$i]= $array1[0];
		}
		return $array;
	}

	/*
		Get value with the corresponding rules
	*/

	protected function xrules($name,$nameRule) {																			
		$array = explode('|',self::$rules["$name"]);
		for ($i = 0 ; $i < count($array) ; $i++) {
			$array[$i] = explode(':',$array[$i]);
			if($array[$i][0] == $nameRule){
				if (isset($array[$i][1])) return  $array[$i][1];
			}
		}
		return false;
	}

	/*
		Get error message with the corresponding rules
	*/

	protected function ruleMessage($name, $nameRule) {																	
		if(is_null($this->xrules($name, $nameRule))) {
			return sprintf(self::$message[$nameRule], $name);
		} else {
			$error = $this->xrules($name, $nameRule);
			return sprintf(self::$message[$nameRule], $name, $error);
		}
	}

	/*
		Check file valid or invalid
	*/

	public function fileValidate() {																						
		$typeFile = array('image/gif' , 'image/jpeg' , 'image/jpg' , 'image/png');
		
		if(empty($_FILES['file']['name'])) {
			return true;
		}
		
		if(in_array($_FILES['file']['type'] , $typeFile)) {
			return true;
		}
		return false;
	}
	
}


?>