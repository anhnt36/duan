<?php 
class Product_Model extends FT_Model {
	public static $error=array();
	public static $rules = array(
				'name' 		=> 	'required',
				'price'		=> 	'required|natural_number'
	);
	public function __construct() {
		parent::__construct();
		self::$_table = 'product';
		
		self::$field= array('name', 'description', 'price', 'activate', 'createdTime', 'updatedTime');
		$this->validate = new Validate_Library(self::$rules);
	}
	/*
		Return errors array
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

	public function insertImage($image, $parentId) {
		$query=self::$pdo->query("INSERT INTO image (image,parentId) VALUES ('{$image}','{$parentId}')");
	}

	/*
		Update image into database
	*/
	public function updateImage($image, $parentId) {
		$query=self::$pdo->query("UPDATE image SET image= '$image' where parentId='{$parentId}'");
	}
	/*
		Delete product follow id 
	*/

	public function delete($id) {
		$query = self::$pdo->query("DELETE product,image from product left join image on product.id = image.parentId where product.id = '{$id}'");
	}
	/*
		Get product follow id or name
	*/
	public function getId($id = '', $name = ''){
		$query = self::$pdo->query("SELECT *,product.id from " . self::$_table . " left join image on product.id=image.
									parentId where product.id = '{$id}' or product.name = '{$name}'");
		$array = $query->fetch(PDO::FETCH_ASSOC);
		return $array;
	}

	/*
		Data valid or invalid
	*/

	public function editValidate($data = array()) {
		$getProduct = $this->getId('', $data['name']);
		//check product exists
		if ($getProduct) {
			if(!empty($data['id'])) { 		// Case : Edit User
				if($getProduct['id'] != $data['id']) {
					self::$error['name'] = 'ProductName already exists !Please enter a different ProductName!';
				}
			} else {		// Case : Add User
				self::$error['name'] = 'ProductName already exists !Please enter a different ProductName!';
			}
		}

		$dataValidate = array (
				'name' 		=> $data['name'],
				'price'		=> $data['price']
		);

		$this->validate->dataValidate($dataValidate);
		//Merge 2 error array
		self::$error = array_merge($this->validate->getError(), self::$error);

		if (!$this->validate->fileValidate()) {
			self::$error['file'] = "File must have ( gif , jpeg , jpg , png ) type";
		}
		if(isset(self::$error['name']) || isset(self::$error['price']) || isset(self::$error['file'])) {
			if(!empty(self::$error['name']) || !empty(self::$error['price'])  || !empty(self::$error['file'])) {
				return false;
			}
		}
		return true;
	}
}