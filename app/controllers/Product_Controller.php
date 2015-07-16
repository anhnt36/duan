<?php
class Product_Controller extends FT_Controller {

	public function __construct() {
		FT_Controller::__construct();
		$this->model->load('Product');
		$this->product= new Product_Model();
		self::$process = '/product/show';
		self::$object= $this->product;
	}

	/*
		Show data
	*/
	public function show() {
		$sort=''; $path='?';
		$this->check_sort($sort,$path);
		if(!isset($_GET['search'])) {
			$db = $this->product->getAllProduct('');
			$pagination = $this->pagination(count($db),'../product/show'.$path);
			$dblimit = $this->product->getAllProduct($pagination['page_limit'],$sort);
			$this->view->load('home/main',$dblimit,'product/listProducts',$pagination);
		} else {
			$path = $path.'context='.trim($_GET['context']).'&search='.$_GET['search'].'&';
			$name = trim($_GET['context']);
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

	/*
		Edit and add Product
	*/
	public function edit() {
		$data = array();

		if(isset($_GET['id'])) {
			if(!$this->product->getId($_GET['id'])) {
				header("Location:".base_url.'/user/error404');
				die();
			}
		}
		if(isset($_POST['OK'])) {
			$data['name'] = htmlentities(trim($_POST['name']),ENT_QUOTES);
			$data['description'] = $_POST['description'];
			$data['price'] = $_POST['price'];
			$data['image'] = $this->encodeImage();
			$data['activate'] = $_POST['activate'];
			$data['createdTime'] = $data['updatedTime'] = date("Y-m-d H:i:s");

			if(isset($_GET['id'])) {
				$data['id']= $_GET['id'];
				$products = $this->product->getId($data['id']);
				$data['createdTime'] =  $products['createdTime'];
				
				/*	
					Handling file if available
				*/
				$this->handlingFile($data['image'], $products['image'],'edit',$this->product->fileValidate());

				if($this->product->editValidate($data)) {
					if (strlen(strstr($data['image'],'tmp/'))) {
						$data['image'] = substr($data['image'], 4);
						copy('public/img/tmp/'.$data['image'],'public/img/'.$data['image']);
						unlink('public/img/tmp/'.$data['image']);
					}
					
					if($products['image']!='') if ($data['image'] != $products['image']) unlink(base_url.'public/img/'.$products['image']);
					
					unset($_SESSION['fileImage']);
					$_SESSION['success'] = 'You edited product successful !';
					$this->product->update($data['id'],$data);
					$this->product->updateImage($data['image'],$data['id']) ;
					header('Location:' . base_url . '/product/show');

				}
			} else {
				/*	
					Handling file if available
				*/
				$this->handlingFile($data['image'],'','add',$this->product->fileValidate());

				if($this->product->editValidate($data)) {
					if (strlen(strstr($data['image'],'tmp/'))) {
						$data['image'] = substr($data['image'], 4);
						copy('public/img/tmp/'.$data['image'],'public/img/'.$data['image']);
						unlink('public/img/tmp/'.$data['image']);
					}
					unset($_SESSION['fileImage']);
					$_SESSION['success'] = 'You added product successful !';
					$this->product->insert($data) ;
					$products = $this->product->getId('',$data['name']);
					$this->product->insertImage($data['image'],$products['id']) ;
					header('Location:' . base_url . '/product/show');
				}
			}
			$this->view->load('home/main',$data,'product/addProduct',$this->product->getError());
		} else {
			if(isset($_SESSION['fileImage']) && $_SESSION['fileImage'] != '') {
				unlink('public/img/'.$_SESSION['fileImage']);
				unset($_SESSION['fileImage']);
			}
			if(isset($_GET['id'])) $data=$this->product->getId($_GET['id']);
			$this->view->load('home/main',$data,'product/addProduct');
		}
	}
}