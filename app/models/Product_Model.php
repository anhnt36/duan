<?php 
class Product_Model extends FT_Model {
	public function __construct() {
		parent::__construct();
		self::$_table = 'product';
		self::$rules = array(
				'name' 		=> 	'required',
				'price'		=> 	'required|natural_number'
		);
		self::$field= array('name','description','price','activate','createdTime','updatedTime');
	}
	/*
		Return errors array
	*/
	public function getError() {
		return self::$error;
	}
	
	/*
		Do nameProduct have in database ?
	*/
	public function is_nameProduct($name){
		$query = self::$pdo->query("select * from product where name='{$name}'");
		$count = $query->rowCount();

		if($count > 0) {
			self::$error['name'] = "ProductName is used !Please enter a different ProductName!";
			return true;
		}
		else return false;
	}
	/*
		Insert image into database
	*/
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
		Product valid or invalid
	*/
	public function checkProduct($name, $price) {
		$nameRule = $this->rules('name', self::$rules);
		if(in_array('required', $nameRule)) {
			if(empty($name)) {
				self::$error['name'] = $this->ruleMessage('name', 'required');
			}
		}
		$priceRule = $this->rules('price', self::$rules);
		if(in_array('required', $priceRule)) {
			if(empty($price)) {
				self::$error['price'] = $this->ruleMessage('price', 'required');
			}
			else {
				if(in_array('natural_number', $priceRule)) {
					if(!filter_var($price, FILTER_VALIDATE_INT ) || $price < 0) {
						self::$error['price'] = $this->ruleMessage('price', 'natural_number');
					}
				}
			}
		}
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

		if(isset($data) && $data!=null) { 
			$this->checkProduct($data['name'], $data['price']);
			
			if (!$this->fileValidate()) self::$error['file'] = "File must have ( gif , jpeg , jpg , png ) type";

			if(isset(self::$error['name']) || isset(self::$error['price']) || isset(self::$error['file'])) {
				if(!empty(self::$error['name']) || !empty(self::$error['price'])  || !empty(self::$error['file'])) {
					return false;
				}
			}
			return true;
		} else {
			self::$error['name'] = $this->ruleMessage('name', 'required');
			self::$error['price'] = $this->ruleMessage('price', 'required');
			return false;
		}
	}
}