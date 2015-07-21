<?php
class FT_Model {
	public static $error=array();
	public static $rules=array();
	public static $_table;
	public static $pdo;
	public static $field;
	public static $message=array(
		'required'			=>	"Field %s isn't be empty !",
		'min' 				=>	"Field %s isn't be less than %d characters !" ,
		'natural_number' 	=>	"Field %s must be natural number !",
		'email'				=>	"Field %s must be email !"
	);

	public function __construct(){																						
		self::$_table;
		try {
			self::$pdo = new PDO('mysql:host=' . DB_HOST . ';' . 'dbname=' . DB_DATABASE, DB_USER, DB_PASSWORD);
		} catch (PDOException $e) {
			echo 'Connect failed :' . $e->getMessage();
		}
	}

	/*
		Return all Object
	*/

	public function getAll($limit = '',$sort = '') {																	
		$dbh = self::$pdo->query("SELECT * FROM " . self::$_table . ' ' . $sort . ' ' . $limit);
		$query = $dbh->fetchAll();
		return $query;
	}

	public function act($id,$activate) {
		$query= self::$pdo->query("UPDATE ".self::$_table." SET activate={$activate} where id='{$id}'");
	}

	/*
		update data into database
	*/

	public function update($idd,$data=array()) {																		
		$array=array();
		$sql="UPDATE ".self::$_table." SET ";
		foreach(self::$field as $value) {
			if ($value=='activate') {
				array_push($array,"$value="."{$data[$value]}");
			} else {
				array_push($array,"$value="."'{$data[$value]}'");
			}
		}
		$sql .= implode(",", $array)." where id='{$idd}'";
		$query=self::$pdo->query($sql);
	}

	/*
		insert data into database
	*/

	public function insert($data=array()) {																				
		$array= array();
		foreach(self::$field as $value) {
			if ($value=='activate') {
				array_push($array,"{$data[$value]}");
			} else {
				array_push($array,"'{$data[$value]}'");
			}
		}
		$sql = "INSERT INTO ".self::$_table;
		$sql .="(".implode(',', self::$field).") values ";
		$sql .= "(".implode(',', $array).")";
		$query=self::$pdo->query($sql);
	}

	/*
		Delete data from database
	*/

	public function delete($id) {																						
		$query= self::$pdo->query("delete  from ".self::$_table." where id= '{$id}'");
		// $query= self::$pdo->query("DELETE product,image from product inner join image on product.id = image.parentId where product.id = '{$id}'");
		return true;
	}

	/*
		Search follow name
	*/

	public function search($name,$sort='',$limit=''){																	
		$db = self::$pdo;
		$count='';
		$name = htmlentities($name,ENT_QUOTES);
		$query= $db->query("SELECT * FROM " . self::$_table . " WHERE name like '%$name%' or id like '%$name%' ".' '.$sort.' '. $limit);
		
		$count= $query->rowCount();
		if($count>0){
			return $query;
		}
		else{
			self::$error['search'] = "Not found !";
			return false;
		}
	}

	/*
		Get id, name and return data array 
	*/

	public function getId($id='',$name=''){																				
		$query= self::$pdo->query("select * from ".self::$_table." where id = '{$id}' or name = '{$name}'");
		$array= $query->fetch(PDO::FETCH_ASSOC);
		return $array;
	}

	/*
		Check data valid or invalid
	*/

	protected function dataValidate($data){																				
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

	protected function min($value ,$data){																				
		$count = $this->xrules($value,'min');
		if(strlen($data) < $count) {
			self::$error["{$value}"] = $this->ruleMessage($value , 'min');
			return false;
		}
		return true;
	}

	/*
		Check data empty
	*/

	protected function required($value ,$data){																			
		if(empty($data)) {
			self::$error["{$value}"] = $this->ruleMessage($value , 'required');
			return false;
		}
		return true;
	}

	/*
		Check email valid or invalid
	*/

	protected function email($value, $data){																			
		if(!filter_var($data,FILTER_VALIDATE_EMAIL)) {
			self::$error["{$value}"] = $this->ruleMessage($value , 'email');
			return false;
		}
		return true;
	}

	/*
		analyze rules and return array (not value)
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
		Get value with the corresponding rules
	*/

	protected function xrules($name,$nameRule){																			
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