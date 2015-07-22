<?php 
class Category_Model extends FT_Model {
	public static $error=[];
	public static $rules = array(
			'name' => 	'required'
	);
	
	public function __construct() {
		parent::__construct();
		self::$_table = 'category';
		
		self::$field = array('name', 'activate', 'createdTime', 'updatedTime');
		$this->validate = new Validate_Library(self::$rules);
	}


	public function getError() {
		return self::$error;
	}

	public function getRules() {
		return self::$rules;
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

		$dataValidate = array (
				'name' 		=> $data['name']
		);

		$this->validate->dataValidate($dataValidate);
		//Merge 2 error array
		self::$error = array_merge($this->validate->getError(), self::$error);

		if(!empty(self::$error['name'])) {
			return false;
		}
		return true;
	}
}