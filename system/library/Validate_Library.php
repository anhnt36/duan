<?php
class Validate_Library {
	
	public static $model = NULL;
	public static $user = NULL;
	public static $error = array();

	public static $message=array(
		'required'			=> "Field %s isn't be empty !",
		'min' 				=> "Field %s isn't be less than %d characters !" ,
		'natural_number' 	=> "Field %s must be natural number !",
		'email'				=> "Field %s must be email !"
	);
	public function __construct(){
		
	}
	public function getError(){
		return self::$_error;
	}
	/*
		Phân tích quy tắc và trả về mảng các quy tắc (không chứa giá trị)
	*/
	public function rules($name){
		$array=explode('|',self::$rules["$name"]);
		for ($i = 0; $i < count($array); $i++) {
			$array1= explode(':',$array[$i]);
			$array[$i]= $array1[0];
		}
		return $array;
	}
	/*
		Lấy giá trị của các quy tắc tương ứng
	*/
	public function xrules($name,$nameRule){
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
		Lấy cac loi tuong ung 
	*/
	public function ruleMessage($name,$nameRule) {
		if(is_null($this->xrules($name,$nameRule))) {
			return sprintf(self::$message[$nameRule],$name);
		} else {
			$error = $this->xrules($name,$nameRule);
			return sprintf(self::$message[$nameRule],$name,$error);
		}
	}

	public function dataValidate($data=array(),$rules=array()){
		$fieldRule = array_keys($rules);
		foreach ($fieldRule as $val) {
			$arrayRule = $this->rules($val);
			foreach ($arrayRule as $key) {
				$value = $this->xrules($val,$key);
				if($value) {
					if($key == 'min') {}
				}
			}
			

		}
	}

	public function min($data,$condition) {
		if($data < $condition) {
			
		}
	}
	
}
?>