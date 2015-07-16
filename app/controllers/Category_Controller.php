<?php
class Category_Controller extends FT_Controller {
	public $user;
	public function __construct() {
		FT_Controller::__construct();
		$this->model->load('Category');
		$this->category= new Category_Model;
		self::$process = '/category/show';
		self::$object= $this->category;
	}
	
	/*
		Show data
	*/
	public function show() {
		$sort=''; $path='?';
		$this->check_sort($sort,$path);
		if(!isset($_GET['search'])) {
			$db = $this->category->getAllCategory('');
			$pagination = $this->pagination($db->rowCount(),'../category/show'.$path);
			$dblimit = $this->category->getAllCategory($pagination['page_limit'],$sort);
			$this->view->load('home/main',$dblimit,'category/listCategories',$pagination);
		} else {
			$path = $path.'context='.$_GET['context'].'&search='.$_GET['search'].'&';
			$name = $_GET['context'];
			if($this->category->search($name)) {
				$db = $this->category->search($name);
				$pagination = $this->pagination($db->rowCount(),'../category/show'.$path);
				$pagination['valueSearch'] = $name;
				$dblimit = $this->category->search($name,$sort,$pagination['page_limit']);
				$this->view->load('home/main',$dblimit,'category/listCategories',$pagination);
			} else {
				$this->view->load('home/main',array(),'category/listCategories',$this->category->getError());
			}
		}
	}

	/*
			Edit User
	*/

	public function edit() {
		$data = array();
		if(isset($_POST['OK'])) {
			$data['name'] = htmlentities($_POST['name'],ENT_QUOTES);
			$data['activate'] = $_POST['activate'];
			$data['createdTime'] = $data['updatedTime']= date("Y-m-d H:i:s");
				
			if(isset($_GET['id'])) {
				$data['id']= $_GET['id'];
				$arrayUser = $this->category->getId($data['id']);
				$data['createdTime'] =  $arrayUser['createdTime'];
				if($this->category->editValidate($data)){
					$this->category->update($data['id'],$data);
					header('Location:' . base_url . '/category/show');
				}
			} else {
				if($this->category->editValidate($data)) {
					$_SESSION['success'] = 'You added account successful !';
					$this->category->insert($data) ;
					header('Location:' . base_url . '/category/show');
				}
			}
			$this->view->load('home/main',$data,'category/addCategory',$this->category->getError());
		} else {
			if(isset($_GET['id'])) $data=$this->category->getId($_GET['id']);
			$this->view->load('home/main',$data,'category/addCategory');
		}
	}
}