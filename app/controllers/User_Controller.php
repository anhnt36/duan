<?php
class User_Controller extends FT_Controller {
	public $user;

	public function __construct() {
		FT_Controller::__construct();
		$this->model->load('User');
		$this->user = new User_Model;
		self::$process = '/user/show';
		self::$object= $this->user;
	}

	/*
						Login system	
	*/

	public function login() {
		if(isset($_POST['OK'])){
			$data['name'] = trim($_POST['nameUser']);
			$data['password'] = trim($_POST['password']);

			if(!$this->user->loginValidate($data['name'], $data['password'])) {
				$this->view->load('home/login', $this->user->getError());
			} else {
				$admin = $this->user->getId('', $data['name']);
				
				$_SESSION['name'] = $admin['name'];
				$_SESSION['id'] = $admin['id'];
				$_SESSION['avatar'] = $admin['avatar'];
				
				if(!empty($_POST['rememberUser'])) {
					setcookie("name", $admin['name'], time() + 86400);
					setcookie("id", $admin['id'], time() + 86400);
					setcookie("avatar", $admin['avatar'], time() + 86400);
				} 
				headerUrl('/user/show');
			}
		}
		$this->view->load('home/login');
	}

	/*
						Logout system	
	*/

	public function logout() {
		ob_start();
		unset($_SESSION['name']);
		unset($_SESSION['id']);
		unset($_SESSION['avatar']);

		if(!empty($_COOKIE['name'])) {
			setcookie("name", '', time() - 86400);
			setcookie("id", '', time() - 86400);
			setcookie("avatar", '', time() - 86400);
		}

		header("location: " . base_url . "/user/login");
		ob_flush();
	}

	/*
						Edit SESSION currentUser
	*/

	public function currentAccount($data=array()){
		$_SESSION['success'] = 'You edited account successful !';
		
		if($_SESSION['id'] == $data['id']) {
			$_SESSION['name'] = $data['name'];
			$_SESSION['avatar'] = $data['avatar'];
		}
	}

	/*
						Show ,sort and search list User
	*/

	public function show() {
		$this->showObject($this->user, '../user/show', 'home/listUsers');
	}

	/*
						Edit User
	*/

	public function edit() {
		$data = array();
		//if id don't exist
		if(isset($_GET['id'])) {
			if(!$this->user->getId($_GET['id'])) {
				header("Location:".base_url.'/user/error404');
			}
		}
		// 	When enter submit add/edit
		if(isset($_POST['OK'])) { 
			$data = $this->dataInputForm();

			$data['id'] = $_GET['id'];
			$arrayUser = $this->user->getId($data['id']);
			$data['createdTime'] =  $arrayUser['createdTime'];

			// file hidden
			$data['fileImage'] = '';
			if (!empty($_POST['fileImage'])) {
				$data['fileImage'] = $_POST['fileImage'];
			}
			// In the case,if user don't enter password ,I will get the old password
			if(empty($_POST['pass'])) {
				$data['password'] = $arrayUser['password']; 				//Not have to insert password
			}
			else {
				$data['password'] = $_POST['pass'];
			}
			/*
				Handling file if available
			*/
			$this->handlingFile($data['avatar'], $arrayUser['avatar'], 'edit', $this->user->fileValidate(), $data['fileImage']);

			if($this->user->editValidate($data)) {		//check data valid or invalid
				$this->deleteFile($data['avatar'], $arrayUser['avatar'], 'edit');

				$this->currentAccount($data);
				$this->user->update($data['id'], $data);
				header('Location:' . base_url . '/user/show');
			}
			$this->view->load('home/main', $data, 'home/addUser', $this->user->getError());

		} else {		//before pressing submit
			if(isset($_GET['id'])) $data = $this->user->getId($_GET['id']);
			$this->view->load('home/main', $data, 'home/addUser');
		}
	}

	/*
						Add User
	*/

	public function add() {
		if(isset($_POST['OK'])) { 
			$data = $this->dataInputForm();
			/*
				Handling file if available
			*/
			$data['fileImage'] = '';
			if (!empty($_POST['fileImage'])) {
				$data['fileImage'] = $_POST['fileImage'];
			}
			$this->handlingFile($data['avatar'],'','add',$this->user->fileValidate(),$data['fileImage']);
			
			if($this->user->editValidate($data)){
				$this->deleteFile($data['avatar'] , '' ,'add');

				$_SESSION['success'] = 'You added account successful !';
				$this->user->insert($data) ;
				header('Location:' . base_url . '/user/show');
			} else {
				$this->view->load('home/main',$data,'home/addUser',$this->user->getError());
			}
		} else {		//before pressing submit
			$this->view->load('home/main','','home/addUser');
		}
	}

	/*
		Error: Page not found
	*/

	public function error404() {
		$this->view->load('home/error404');
	}
}
