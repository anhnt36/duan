<?php
class FT_Model {
	public static $_table;
	public static $pdo;
	public static $field;
	

	public function __construct(){																						
		self::$_table;
		try {
			self::$pdo = new PDO('mysql:host=' . DB_HOST . ';' . 'dbname=' . DB_DATABASE, DB_USER, DB_PASSWORD);
		} catch (PDOException $e) {
			echo 'Connect failed :' . $e->getMessage();
		}
		$this->library = new FT_Library_Loader;
		$this->library->load('validate');
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

	
}