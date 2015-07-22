<?php
class Product_Controller extends FT_Controller {

	public function __construct() {																				
		parent::__construct();
		$this->model->load('Product');
		$this->product = new Product_Model();
		self::$process = '/product/show';
		self::$object = $this->product;
		$this->validate = new Validate_Library($this->product->getRules());
	}

	/*
		Show data
	*/

	public function show() {																					
		$this->showObject($this->product, '../product/show', 'product/listProducts');
	}

	private function formatData() {
		$data = $this->dataInputForm();
		$data['description'] = $_POST['description'];
		$data['price'] = $_POST['price'];
		$data['image'] = $this->encodeImage();
		return $data;
	}

	/*
		Edit and add Product
	*/

	public function edit() {																						
		$data = array();
		//if id don't exist
		if(isset($_GET['id'])) {
			if(!$this->product->getId($_GET['id'])) {
				header("Location:" . base_url . '/user/error404');
			}
		}
		if(isset($_POST['OK'])) {
			$data = $this->formatData();

			$data['id'] = $_GET['id'];
			$products = $this->product->getId($data['id']);
			$data['createdTime'] = $products['createdTime'];
			/*
				Handling file if available
			*/
			$data['fileImage'] = '';
			if (!empty($_POST['fileImage'])) {
				$data['fileImage'] = $_POST['fileImage'];
			}
			$this->handlingFile($data['image'], $products['image'], 'edit', $this->validate->fileValidate(), $data['fileImage']);

			if($this->product->editValidate($data)) {
				$this->deleteFile($data['image'], $products['image'], 'edit', $data['fileImage']);

				$_SESSION['success'] = 'You edited product successful !';
				$this->product->update($data['id'], $data);
				$this->product->updateImage($data['image'], $data['id']) ;
				header('Location:' . base_url . '/product/show');

			}
			$this->view->load('home/main', $data, 'product/addProduct', $this->product->getError());
		} else {
			if(isset($_GET['id'])) {
				$data = $this->product->getId($_GET['id']);
				$data['check'] = 0;
			}
			$this->view->load('home/main', $data, 'product/addProduct');
		}
	}


	/*
		Add Product
	*/

	public function add() {																						
		if(isset($_POST['OK'])) {
			$data = $this->formatData();

			$data['fileImage'] = '';
			if (!empty($_POST['fileImage'])) {
				$data['fileImage'] = $_POST['fileImage'];
			}
			
			$this->handlingFile($data['image'], '', 'add', $this->validate->fileValidate(), $data['fileImage']);

			if($this->product->editValidate($data)) {
				$this->deleteFile($data['image'], '', 'add','');
				
				$_SESSION['success'] = 'You added product successful !';
				$this->product->insert($data) ;
				$products = $this->product->getId('', $data['name']);
				$this->product->insertImage($data['image'], $products['id']) ;
				header('Location:' . base_url . '/product/show');
			}
			else {
				$this->view->load('home/main', $data, 'product/addProduct', $this->product->getError());
			}
		} else {
			$this->view->load('home/main', '', 'product/addProduct');
		}
	}
}