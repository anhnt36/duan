<?php 
class User_Model extends FT_Model {
	public static $error=[];
	public function getError(){
		return self::$error;
	}
	public function __construct() {
		parent::__construct();
		self::$_table = 'user';
		self::$rules = array(
				'username' => 	'required|min:4',
				'password' => 	'required|min:4',
				'email'		=>  'required|email'
		);
		self::$field= array('name','password','email','avatar','activate','createdTime','updatedTime');
	}
	/*
		Activate and Deactivate User
	*/
	
	public function is_nameUser($name) {
		$db = self::$pdo;
		$query= $db->query("select * from " . self::$_table . " where name = '{$name}'");
		$count= $query->rowCount();
		if($count>0){
			self::$error['name']= "Username is used !Please enter a different username!";
			return true;
		} else {
			return false;
		}
	}
	/*
		Check Username and Password
	*/
	public function check_user($name,$pass) {
		$db = self::$pdo;
		$query= $db->query("SELECT * FROM " . self::$_table . " WHERE password = '$pass' AND name = '$name'");
		$count= $query->rowCount();
		if($count>0) return true;
		else return false;
	}
	/*
		Return all user
	*/
	public function getAllUser($limit='',$sort='') {
		$dbh=self::$pdo->query("SELECT * FROM " . self::$_table.' '.$sort.' '. $limit);
		return $dbh;
	}
	/*
		Check valid : username, password ,email
	*/
	public function name_pass($name,$pass,$email){
		$nameRule=$this->rules('username',self::$rules);
		if(in_array('required',$nameRule)){
			if(empty($name)){
				self::$error['name'] = $this->ruleMessage('username','required');
			} else{
				if(in_array('min', $nameRule)) {
					if(strlen($name)<$this->xrules('username','min',self::$rules)) {
						self::$error['name'] = $this->ruleMessage('username','min');
					}
				}
			}
		}
		$passRule=$this->rules('password',self::$rules);

		if(in_array('required',$passRule)) {
			if(empty($pass)) {
				self::$error['pass']= $this->ruleMessage('password','required');
			} else{
				if(in_array('min', $passRule)) {
					if(strlen($pass)<$this->xrules('password','min',self::$rules)) {
						self::$error['pass']= $this->ruleMessage('password','min');
					}
				}
			}
		}
		$emailRule = $this->rules('email',self::$rules);
		if(in_array('required',$emailRule)) {
			if(empty($email)) {
				self::$error['email']= $this->ruleMessage('email','required');
			} else {
				if(in_array('email', $emailRule)) {
					if(!filter_var($email,FILTER_VALIDATE_EMAIL)) {
						self::$error['email'] = $this->ruleMessage('email','email');
					}
				}
			}
		}
	}
	/*
		Validate Login
	*/
	public function loginValidate($name,$pass) {
		$this->name_pass($name,$pass);
		if(!$this->check_user($name,$pass)) {
			self::$error['pass']='Username or Password are incorrect!';	
		
		}
		if(isset(self::$error['name']) || isset(self::$error['pass'])) {
			if(!empty(self::$error['name']) || !empty(self::$error['pass'])) {
				return false;
			} else return true;
		} else return true;
	}
	/*
		Validate Edit
	*/
	public function editValidate($data=array()) {
		if (!empty($data['id'])) {
			$getId= $this->getId('',$data['name']);
			if($getId) if($getId['id'] != $data['id']) {self::$error['name']= 'Username is used !Please enter a different username!';}
		}
		if(isset($data) && $data!=null){ 
			$this->name_pass($data['name'],$data['password'],$data['email']);
			if (!$this->fileValidate()) {self::$error['file'] = "File must have ( gif , jpeg , jpg , png ) type";}
			if(isset(self::$error['name']) || isset(self::$error['pass']) ||  isset(self::$error['email']) ||  isset(self::$error['file']) ) {
				if(!empty(self::$error['name']) || !empty(self::$error['pass']) || !empty(self::$error['email'])  || !empty(self::$error['file'])) {
					return false;
				} else return true;
			} else return true;
		} 
	}
}