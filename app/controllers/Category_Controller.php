<?php
class Category_Controller extends FT_Controller {
	public $user;
	public function __construct() {
		parent::__construct();
		$this->model->load('Category');
		$this->category= new Category_Model();
		self::$process = '/category/show';
		self::$object= $this->category;
		$this->validate = new Validate_Library($this->category->getRules());
	}
																												
	/*
		Show data
	*/
	public function show() {
		$this->showObject($this->category, '../category/show', 'category/listCategories');
	}

	/*
			Edit User
	*/

	public function edit() {
		$data = array();
		if(isset($_POST['OK'])) {
			$data = $this->dataInputForm();

			if(isset($_GET['id'])) {
				$data['id']= $_GET['id'];
				$arrayUser = $this->category->getId($data['id']);
				$data['createdTime'] =  $arrayUser['createdTime'];
				if($this->category->editValidate($data)){
					$this->category->update($data['id'], $data);
					header('Location:' . base_url . '/category/show');
				}
			} else {
				if($this->category->editValidate($data)) {
					$_SESSION['success'] = 'You added account successful !';
					$this->category->insert($data) ;
					header('Location:' . base_url . '/category/show');
				}
			}
			$this->view->load('home/main', $data, 'category/addCategory', $this->category->getError());
		} else {
			if(isset($_GET['id'])) {
				$data = $this->category->getId($_GET['id']);
			}
			$this->view->load('home/main', $data, 'category/addCategory');
		}
	}

	/*
			Add User
	*/

	public function add() {
		if(isset($_POST['OK'])) {
			$data = $this->dataInputForm();

			if($this->category->editValidate($data)) {

				$_SESSION['success'] = 'You added account successful !';
				$this->category->insert($data) ;
				header('Location:' . base_url . '/category/show');
			}
			$this->view->load('home/main', $data, 'category/addCategory', $this->category->getError());
		} else {
			$this->view->load('home/main', '', 'category/addCategory');
		}
	}
}