<?php
class Category_Controller extends FT_Controller {
	public $user;
	public function __construct() {
		FT_Controller::__construct();
		$this->model->load('Category');
		$this->category= new Category_Model;
		
	}
	public function show() {
		$sort=''; $path='?';
		if(isset($_GET['s'])) { //			Check to sort follow id and name
			if($_GET['s']=='id') {
				$sort = "order by id ";
				$path = '?s=id&';
			}
			if($_GET['s']=='name') {
				$sort="order by name ";
				$path='?s=name&';
			}
		}
		if(isset($_GET['type'])) { 				//Check to sort ASC or DESC
			$sort=$sort.$_GET['type'];
			$path=$path.'type='.$_GET['type'].'&';
		}
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
		if(isset($_POST['OK'])) {
			$data['id']= $_GET['id'];
			$data['name'] = htmlentities($_POST['name'],ENT_QUOTES);
			$data['email'] = $_POST['email'];
			$data['activate'] = $_POST['activate'];
			$data['updatedTime'] = date("Y-m-d H:i:s");
			$arrayUser = $this->user->getId($data['id']);
			$data['createdTime'] =  $arrayUser['createdTime'];
			if(empty($_POST['pass'])) $data['password'] = $arrayUser['password']; 
			else $data['password'] = $_POST['pass'];
			$data['avatar'] = '';
			if($_FILES['file']['name']!='') {
				if($this->user->editValidate($data)){
					$_SESSION['success'] = 'You edited account successful !';
					$data['avatar'] = $_FILES['file']['name'];
					$this->user->update($data['id'],$data);
					header('Location:' . base_url . '/user/show');
				}
			} else {
				if(isset($_POST['img']) && $_POST['img']=='1') $data['avatar']='';
				else $data['avatar'] = $arrayUser['avatar'];
				if($this->user->editValidate($data)) {
					$_SESSION['success'] = 'You edited account successful !';
					$this->user->update($data['id'],$data);
					header('Location:'. base_url.'/user/show');
				}
			}
			$this->view->load('home/main',$data,'home/addUser',$this->user->getError());
		} else {
			$data=$this->user->getId($_GET['id']);
			$this->view->load('home/main',$data,'home/addUser');
		}
	}
	/*
		Add User
	*/
	public function add() {
		ob_start(); 
		if(isset($_POST['OK'])) {
			$data['name'] = htmlentities($_POST['name'],ENT_QUOTES);
			$data['password'] = $_POST['pass'];
			$data['email'] = $_POST['email'];
			$data['activate'] = $_POST['activate'];
			$data['avatar'] = $_FILES['file']['name'];
			$data['createdTime'] = $data['updatedTime']= date("Y-m-d H:i:s");
			
			if(!$this->user->is_nameUser($data['name'])) {
				if($this->user->editValidate($data)){
					$_SESSION['success'] = 'You added account successful !';
					$this->user->insert($data) ;
					header('Location:' . base_url . '/user/show');
				}
			}
			$this->view->load('home/main',$data,'home/addUser',$this->user->getError());
		} else {
			$this->view->load('home/main',array(),'home/addUser');
		}
		ob_end_flush();
	}
	/*
		Activate ,Deactivate and Delete
	*/
	public function process() {
		if(isset($_POST['activate'])) {
			if(!empty($_POST['c'])) {
				$act= $_POST['c'];
				foreach ($act as $val) {
					$this->user->act($val,'1');
				}
			} else {$_SESSION['activate'] = 'Please ! Click checkbox ';}
		}
		if(isset($_POST['deactivate'])) {
			if(!empty($_POST['c'])) {
				$act= $_POST['c'];
				foreach ($act as $val) {
					$this->user->act($val,'0');
				}
			} else {$_SESSION['activate'] = 'Please ! Click checkbox ';}
		}
		if(isset($_POST['delete'])) {
			if(!empty($_POST['c'])) {
				$del= $_POST['c'];
				foreach ($del as $val) {
					$this->user->delete($val);
				}
			} else {$_SESSION['activate'] = 'Please ! Click checkbox ';}
		}
		header("Location:".base_url."/user/show");
	}
}
?>