<?php 
class User_Model extends FT_Model {
	public static $error=array();
	public static $rules = array(
				'name' 		=> 	'required|min:4',
				'password' 	=> 	'required|min:4',
				'email'		=>  'required|email'
	);
	public function __construct() {
		parent::__construct();
		self::$_table = 'user';
		
		self::$field = array('name', 'password', 'email', 'avatar', 'activate', 'createdTime', 'updatedTime');
		$this->validate = new Validate_Library(self::$rules);
	}
	/*
		Return error array
	*/
	public function getError() {
		return self::$error;
	}
	/*
		Return rules array
	*/
	public function getRules() {
		return self::$rules;
	}

	/*
		Check Username and Password
	*/
	public function check_user($name='', $pass='') {
		$db = self::$pdo;
		$query = $db->query("SELECT * FROM " . self::$_table . " WHERE password = '$pass' AND name = '$name'");
		$count = $query->rowCount();
		
		if($count > 0) return true;
		else return false;
	}

	/*
		Validate Login
	*/

	public function loginValidate($name, $pass) {
		if(!$this->check_user($name, $pass)) {
			self::$error['pass'] = 'Username or Password are incorrect!';
		}
		if(isset(self::$error['name']) || isset(self::$error['pass'])) {
			if(!empty(self::$error['name']) || !empty(self::$error['pass'])) {
				return false;
			}
		}
		return true;
	}

	/*
		Validate Edit
	*/
	public function editValidate($data=array()) {
		$getUser= $this->getId('', $data['name']);
		//check user exists
		if ($getUser) {
			if(!empty($data['id'])) { 		// Case : Edit User
				if($getUser['id'] != $data['id']) {
					self::$error['name'] = 'Username already exists !Please enter a different username!';
				}
			} else {		// Case : Add User
				self::$error['name'] = 'Username already exists !Please enter a different username!';
			}
		}
		
		$dataValidate = array(
				'name' 		=> $data['name'],
				'password' 	=> $data['password'],
				'email' 	=> $data['email']
		);
		$this->validate->dataValidate($dataValidate);
		self::$error = array_merge($this->validate->getError(), self::$error);

		if (!$this->validate->fileValidate()) {
			self::$error['file'] = "File must have ( gif , jpeg , jpg , png ) type";
		}

		if(isset(self::$error['name']) || isset(self::$error['password']) ||  isset(self::$error['email']) ||  isset(self::$error['file']) ) {

			if(!empty(self::$error['name']) || !empty(self::$error['password']) || !empty(self::$error['email'])  || !empty(self::$error['file'])) {
				return false;
			}
		}
		return true;
	}
}