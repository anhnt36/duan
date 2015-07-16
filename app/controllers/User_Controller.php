<?php
class User_Controller extends FT_Controller {
	public $user;
	public function __construct() {
		FT_Controller::__construct();
		$this->model->load('User');
		$this->user= new User_Model;
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
			if(isset($_POST['rememberUser'])) $data['remember'] = $_POST['rememberUser'];
			else $data['remember'] = '';
			
			if(!$this->user->loginValidate($data['name'],$data['password'])) {
				$this->view->load('home/login',$this->user->getError());
			} else {
				$admin = $this->user->getId('',$data['name']);
				$_SESSION['name'] = $admin['name'];
				$_SESSION['id'] = $admin['id'];
				$_SESSION['avatar'] = $admin['avatar'];
				
				if(isset($_POST['rememberUser'])) {
					setcookie("name", $data['name'], time()+86400);
					setcookie("password", $data['password'], time()+86400);
					setcookie("avatar", $data['avatar'], time()+86400);
				}

				headerUrl('/user/home');
			}
		}
		$this->view->load('home/login');
	}

	/*
						Logout system	
	*/
	public function logout() {
		unset($_SESSION['name']);
		unset($_SESSION['id']);
		unset($_SESSION['avatar']);
		
		if(isset($_COOKIE['name'])){
			setcookie("name", $data['name'], time()-86400);
			setcookie("password", $data['password'], time()-86400);
			setcookie("avatar", $data['avatar'], time()-86400);
		}
		header('Location:'. base_url.'/user/login/');
	}

	/*
						Edit SESSION User
	*/
	public function currentAccount($data=array()){
		$_SESSION['success'] = 'You edited account successful !';
		
		if($_SESSION['id'] == $data['id']) {
			$_SESSION['name'] = $data['name'];
			$_SESSION['avatar'] = $data['avatar'];
		}
	}

	/*
						Home page
	*/
	public function home() {
		$this->view->load('home/main');
	}

	/*
						Show ,sort and search list User
	*/
	public function show() {
		
		$sort=''; $path='?';
		$this->check_sort($sort,$path);

		if(!isset($_GET['search'])) {
			$db = $this->user->getAllUser('');
			$pagination = $this->pagination($db->rowCount(),'../user/show'.$path);
			$dblimit = $this->user->getAllUser($pagination['page_limit'],$sort);
			$this->view->load('home/main',$dblimit,'home/listUsers',$pagination);
		} else {
			$path = $path.'context='.$_GET['context'].'&search='.$_GET['search'].'&';
			$name = $_GET['context'];

			if($this->user->search($name)) {
				$db = $this->user->search($name);
				$pagination = $this->pagination($db->rowCount(),'../user/show'.$path);
				$pagination['valueSearch'] = $name;
				$dblimit = $this->user->search($name,$sort,$pagination['page_limit']);
				$this->view->load('home/main',$dblimit,'home/listUsers',$pagination);
			} else {
				$this->view->load('home/main',array(),'home/listUsers',$this->user->getError());
			}
		}
	}

	/*
						Edit User
	*/
	public function edit() {
		$data = array();
		// 	When enter submit add/edit
		if(isset($_GET['id'])) {
			if(!$this->user->getId($_GET['id'])) {
				header("Location:".base_url.'/user/error404');
				die();
			}
		}
		if(isset($_POST['OK'])) { 
			$data['name'] = htmlentities(trim($_POST['name']),ENT_QUOTES);
			$data['password'] = trim($_POST['pass']);
			$data['email'] = $_POST['email'];
			$data['activate'] = $_POST['activate'];
			$data['createdTime'] = $data['updatedTime']= date("Y-m-d H:i:s");
			$data['avatar'] = $this->encodeImage();

			// If case is editing
			if(isset($_GET['id'])) {																// Edit User

				$data['id'] = $_GET['id'];
				$arrayUser = $this->user->getId($data['id']);
				$data['createdTime'] =  $arrayUser['createdTime'];

				// In the case,if user don't enter password ,I will get the old password
				if(empty($_POST['pass'])) $data['password'] = $arrayUser['password']; 				//Not have to insert password
				else $data['password'] = $_POST['pass'];
				/*	
					Handling file if available
				*/
				$this->handlingFile($data['avatar'], $arrayUser['avatar'],'edit',$this->user->fileValidate());
				
				if($this->user->editValidate($data)) {		//check data valid or invalid
					if (strlen(strstr($data['avatar'],'tmp/'))) {
						$data['avatar'] = substr($data['avatar'], 4);
						copy('public/img/tmp/'.$data['avatar'],'public/img/'.$data['avatar']);
						unlink('public/img/tmp/'.$data['avatar']);
					}
					
					if($arrayUser['avatar']!='') if ($data['avatar'] != $arrayUser['avatar']) unlink(base_url.'public/img/'.$arrayUser['avatar']);
					
					unset($_SESSION['fileImage']);
					$this->currentAccount($data);
					$this->user->update($data['id'],$data);
					header('Location:' . base_url . '/user/show');
				}
			// If case is adding
			} else {			//Add User

				/*	
					Handling file if available
				*/
				$this->handlingFile($data['avatar'],'','add',$this->user->fileValidate());
				
				if($this->user->editValidate($data)){
					if (strlen(strstr($data['avatar'],'tmp/'))) {
						$data['avatar'] = substr($data['avatar'], 4);
						copy('public/img/tmp/'.$data['avatar'],'public/img/'.$data['avatar']);
						unlink('public/img/tmp/'.$data['avatar']);
					}
					unset($_SESSION['fileImage']);
					$_SESSION['success'] = 'You added account successful !';
					$this->user->insert($data) ;
					header('Location:' . base_url . '/user/show');
				}
			}
			$this->view->load('home/main',$data,'home/addUser',$this->user->getError());

		} else {		//Khi chưa nhấn submit

			if(isset($_SESSION['fileImage']) && $_SESSION['fileImage'] != '') {
				unlink('public/img/'.$_SESSION['fileImage']);
				unset($_SESSION['fileImage']);
			}
			if(isset($_GET['id'])) $data = $this->user->getId($_GET['id']);
			$this->view->load('home/main',$data,'home/addUser');
		}
	}
	/*
		Error: Page not found
	*/
	public function error404() {
		$this->view->load('home/error404');
	}

}
