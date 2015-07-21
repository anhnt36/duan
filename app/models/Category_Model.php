<?php 
class Category_Model extends FT_Model {
	public static $error=[];
	public function getError(){
		return self::$error;
	}

	public function __construct() {
		parent::__construct();
		self::$_table = 'category';
		self::$rules = array(
			'CategoryName' => 	'required'
		);
		self::$field = array('name', 'activate', 'createdTime', 'updatedTime');
	}
	/*
		Activate and Deactivate User
	*/
	
	public function is_nameCategory($name) {
		$db = self::$pdo;
		$query = $db->query("select * from " . self::$_table . " where name = '{$name}'");
		$count = $query->rowCount();
		if($count > 0) {
			self::$error['name'] = "CategoryName already exists! Please enter a different CategoryName!";
			return true;
		} else {
			return false;
		}
	}
	/*
		Check valid : username, password ,email
	*/
	public function nameValidate($name) {
		$nameRule = $this->rules('CategoryName', self::$rules);
		if(in_array('required', $nameRule)){
			if(empty($name)) {
				self::$error['name'] = $this->ruleMessage('CategoryName', 'required');
			}
			else{
				if(in_array('min', $nameRule)) {
					if(strlen($name) < $this->xrules('CategoryName', 'min', self::$rules)) {
						self::$error['name'] = $this->ruleMessage('CategoryName', 'min');
					}
				}
			}
		}
	}
	/*
		Validate Edit
	*/
	public function editValidate($data = array()) {
		$getCategory= $this->getId('', $data['name']);
		//check category exists
		if ($getCategory) {
			if(!empty($data['id'])) { 		// Case : Edit User
				if($getCategory['id'] != $data['id']) {
					self::$error['name'] = 'CategoryName already exists !Please enter a different CategoryName!';
				}
			} else {		// Case : Add User
				self::$error['name'] = 'CategoryName already exists !Please enter a different CategoryName!';
			}
		}

		if(isset($data) && $data!=null){ 
			$this->nameValidate($data['name']);
			if(isset(self::$error['name'])) {
				if(!empty(self::$error['name'])) {
					return false;
				}
			}
			return true;
		} 
	}
}