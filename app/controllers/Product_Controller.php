<?php
class Product_Controller extends FT_Controller {

	public function __construct() {
		FT_Controller::__construct();
		$this->model->load('Product');
		$this->product= new Product_Model();
		self::$process = '/product/show';
		self::$object= $this->product;
	}
	public function show() {
		$sort=''; $path='?';
		$this->check_sort($sort,$path);
		if(!isset($_GET['search'])) {
			$db = $this->product->getAllProduct('');
			$pagination = $this->pagination(count($db),'../product/show'.$path);
			$dblimit = $this->product->getAllProduct($pagination['page_limit'],$sort);
			$this->view->load('home/main',$dblimit,'product/listProducts',$pagination);
		} else {
			$path = $path.'context='.$_GET['context'].'&search='.$_GET['search'].'&';
			$name = $_GET['context'];
			if($this->product->search($name)) {
				$db = $this->product->search($name);
				$pagination = $this->pagination($db->rowCount(),'../product/show'.$path);
				$pagination['valueSearch'] = $name;
				$dblimit = $this->product->search($name,$sort,$pagination['page_limit']);
				$this->view->load('home/main',$dblimit,'product/listProducts',$pagination);
			} else {
				$this->view->load('home/main',array(),'product/listProducts',$this->product->getError());
			}
		}
		
	}
	public function add() {
		ob_start(); 
		if(isset($_POST['OK'])) {
			$data['name'] = htmlentities($_POST['name'],ENT_QUOTES);
			$data['description'] = $_POST['description'];
			$data['price'] = $_POST['price'];
			$data['image'] = $_FILES['file']['name'];
			$data['activate'] = $_POST['activate'];
			$data['createdTime'] = $data['updatedTime'] = date("Y-m-d H:i:s");
			if(!$this->product->is_nameProduct($data['name'])) {
				if($this->product->editValidate($data)) {
					$_SESSION['success'] = 'You added product successful !';
					$this->product->insert($data) ;
					$products = $this->product->getId('',$data['name']);
					$this->product->insertImage($data['image'],$products['id']) ;
					header('Location:' . base_url . '/product/show');
				} 
			}
			$this->view->load('home/main',$data,'product/addProduct',$this->product->getError());
		} else {
			$this->view->load('home/main',array(),'product/addProduct');
		}
		ob_end_flush();
	}
	public function edit() {
		if(isset($_POST['OK'])) {
			$data['id']= $_GET['id'];
			$data['name'] = htmlentities($_POST['name'],ENT_QUOTES);
			$data['description'] = $_POST['description'];
			$data['price'] = $_POST['price'];
			$data['activate'] = $_POST['activate'];
			$data['updatedTime'] = date("Y-m-d H:i:s");
			$products = $this->product->getId($data['id']);
			$data['createdTime'] =  $products['createdTime'];
			$data['image'] = '';

			if(!empty($_FILES['file']['name'])) {
				if($this->product->editValidate($data)){
					$_SESSION['success'] = 'You edited product successful !';
					$data['image'] = $_FILES['file']['name'];
					$this->product->update($data['id'],$data);
					$this->product->updateImage($data['image'],$data['id']) ;
					header('Location:' . base_url . '/product/show');
				}
			} else {
				$array = $this->product->getId($_GET['id']);
				if(isset($_POST['img']) && $_POST['img']=='1') $data['image']='';
				else $data['image'] = $products['image'];
				if($this->product->editValidate($data)) {
					$_SESSION['success'] = 'You edited product successful !';
					$this->product->update($data['id'],$data);
					$this->product->updateImage($data['image'],$data['id']) ;
					header('Location:'. base_url.'/product/show');
				}
			}
			$this->view->load('home/main',$data,'product/addProduct',$this->product->getError());
		} else {
			$data=$this->product->getId($_GET['id']);
			$this->view->load('home/main',$data,'product/addProduct');
		}
	}
}