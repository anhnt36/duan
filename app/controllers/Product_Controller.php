<?php
class Product_Controller extends FT_Controller {

	public function __construct() {
		FT_Controller::__construct();
		$this->model->load('Product');
		$this->product= new Product_Model();

	}
	public function show(){
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
			$db = $this->product->getAllProduct('');
			$pagination = $this->pagination(count($db),'../product/show'.$path);
			$dblimit = $this->product->getAllProduct($pagination['page_limit'],$sort);
			$this->view->load('home/main',$dblimit,'product/listProducts',$pagination);
		} else {
			$path = $path.'context='.$_GET['context'].'&search='.$_GET['search'].'&';
			$name = $_GET['context'];
			if($this->product->search($name)) {
				$db = $this->product->search($name);
				// var_dump($db);
				// die();
				$pagination = $this->pagination($db->rowCount(),'../product/show'.$path);
				$pagination['valueSearch'] = $name;
				$dblimit = $this->product->search($name,$sort,$pagination['page_limit']);
				$this->view->load('home/main',$dblimit,'product/listProducts',$pagination);
			} else {
				$this->view->load('home/main',array(),'product/listProducts',$this->user->getError());
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
			$data['createdTime'] = $data['updatedTime']= date("Y-m-d H:i:s");
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
			$validate = new Validate_Library();

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

	public function process() {
		if(isset($_POST['activate'])) {
			if(!empty($_POST['c'])) {
				$act= $_POST['c'];
				foreach ($act as $val) {
					$this->product->act($val,'1');
				}
			} else {$_SESSION['activate'] = 'Please ! Click checkbox ';}
		}
		if(isset($_POST['deactivate'])) {
			if(!empty($_POST['c'])) {
				$act= $_POST['c'];
				foreach ($act as $val) {
					$this->product->act($val,'0');
				}
			} else {$_SESSION['activate'] = 'Please ! Click checkbox ';}
		}
		if(isset($_POST['delete'])) {
			if(!empty($_POST['c'])) {
				$del= $_POST['c'];
				$add= 'left join productimage on product.id=productimage.id_product';
				foreach ($del as $val) {
					$this->product->delete($val,$add,'product.');
				}
			} else {$_SESSION['activate'] = 'Please ! Click checkbox ';}
		}
		header("Location:".base_url."/product/show");
	}
}