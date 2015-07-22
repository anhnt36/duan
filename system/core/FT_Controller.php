<?php if(!defined('PATH_SYSTEM')) die ('Bad requested!');

class FT_Controller {
	public $view = NULL;
	public $model = NULL;
	public $library = NULL;
	public $helper = NULL;
	public $config = NULL;
	public $controller = NULL;
	public static $process='';
	public static $object='';

	public function __construct($is_controller=true) {																			
		$this->view = new FT_View_Loader;

		$this->model = new FT_Model_Loader;

		$this->library = new FT_Library_Loader;
		$this->library->load('validate');

		$this->helper = new FT_Helper_Loader;

		$this->controller = new FT_Controller_Loader;

		date_default_timezone_set('Asia/Ho_Chi_Minh');
	}

	/*
		Handling activate and deactivate
	*/

	private function buckaction($status = 0) {																						
		if(!empty($_POST['c'])) {
			$act= $_POST['c'];
			foreach ($act as $val) {
				self::$object->act($val, $status);
			}
		} else {
			$_SESSION['activate'] = 'Please ! Click checkbox ';
		}
	}

	/*
		Pagination
	*/

	protected function pagination($number_row, $path){																				
		$links = new Pagination_Library(PERPAGE,'page'); 
		$links->set_total($number_row);
		return array(
			'page_links' => $links->page_links($path), 
			'page_limit' => $links->get_limit()
		);
	}

	/*
		Click activate ,deactivate and delete
	*/

	public function process() {																												
		if(isset($_POST['activate'])) {
			$this->buckaction(0);
		}

		if(isset($_POST['deactivate'])) {
			$this->buckaction(1);
		}

		if(isset($_POST['delete'])) {
			if(!empty($_POST['c'])) {
				$del= $_POST['c'];

				foreach ($del as $val) {
					if ($val != $_SESSION['id']) {
						self::$object->delete($val);
					} else {
						$_SESSION['error'] = "Note : You can't delete your account !";
					}
				}

			} else {
				$_SESSION['activate'] = 'Please ! Click checkbox ';
			}
		}

		header("Location:".base_url.self::$process);
	}

	/*
		Sort follow id ,name ,activate ,price ,createdTime ,updatedTime
	*/

	protected function check_sort(&$sort, &$path){																						

		$arrayElement = array('id', 'activate', 'price', 'createdTime', 'updatedTime', 'name');
		if(isset($_GET['s'])) { //			Check to sort follow id and name
			if(!in_array($_GET['s'], $arrayElement)) {
				$_GET['s'] = 'id';
			}
		} else {
			$_GET['s'] = 'id';
		}

		$this->sort($sort, $path, $_GET['s']);

		if(isset($_GET['type'])) { 				//Check to sort ASC or DESC
			if(!in_array($_GET['type'], array('ASC', 'DESC'))) {
				$_GET['type'] = 'ASC';
			}
		} else {
			$_GET['type'] = 'ASC';
		}

		$sort = $sort . $_GET['type'];
		$path = $path . 'type=' . $_GET['type'] . '&';
	}

	/*
		function sub of function checksort
	*/

	private function sort(&$sort, &$path, $element) {																			
		$sort = "order by {$element} ";
		$path = "?s={$element}&";
	}

	/*
		Encode file image = md5(fileName) + md5(currentTime)
	*/

	protected function encodeImage(){																							
		if($_FILES['file']['name'] != '') {
			$time = md5(date("Y-m-d H:i:s"));
			$md5 = md5($_FILES['file']['name']) . $time . '.' . substr($_FILES['file']['type'], 6);
			return $md5;
		} else {
			return NULL;
		}
		
	}

	/*
		Handling file
	*/

	protected function handlingFile(&$data , $oldImage , $action = 'edit' , $file='' ,&$fileImage ) {						

		if (!empty($_POST['fileImage'])) {
			$fileImage = $_POST['fileImage'];
		}
		if($_FILES['file']['name'] == '') {

			if($action == 'edit') {

				if(empty($fileImage)) {
					$data = $oldImage;
				}
				else {
					$data = $fileImage;
				}
			} else {

				if(!empty($fileImage)) {
					$data = $fileImage;
				} else {
					$data = NULL;
				}
			}
		} else {

			if(!empty($fileImage)) {
				if (strlen(strstr($fileImage,'tmp/'))) {
					if(file_exists('public/img/' . $fileImage)) {
						unlink('public/img/' . $fileImage) ;
					}
				}
			}
			if($file) {
				$data = 'tmp/' . $data;
				move_uploaded_file($_FILES['file']['tmp_name'], 'public/img/' . $data);
			}
		}
	}

	/*
		Show Object
	*/

	protected function showObject($object ,$linkShow,$linkList) {																		
		$sort = '';
		$path = '?';
		$this->check_sort($sort,$path);

		if(!isset($_GET['search'])) {

			$db = $object->getAll('');
			$pagination = $this->pagination(count($db), $linkShow. $path);
			$dblimit = $object->getAll($pagination['page_limit'], $sort);
			$this->view->load('home/main', $dblimit, $linkList, $pagination);
		} else {

			$path = $path . 'context=' . $_GET['context'] . '&search=' . $_GET['search'] . '&';
			$name = $_GET['context'];

			if($object->search($name)) {
				$db = $object->search($name);
				$pagination = $this->pagination(count($db), $linkShow . $path);
				$pagination['valueSearch'] = $name;
				$dblimit = $object->search($name, $sort, $pagination['page_limit']);
				$this->view->load('home/main', $dblimit, $linkList, $pagination);
			} else {
				$this->view->load('home/main', array(), $linkList, $object->getError());
			}
		}
	}

	/*
		Show Object
	*/

	protected function dataInputForm() {																							
		$data = array();
		$data['name'] = htmlentities(trim($_POST['name']),ENT_QUOTES);
		$data['activate'] = $_POST['activate'];
		$data['createdTime'] = $data['updatedTime']= date("Y-m-d H:i:s");
		return $data;
	}

	/*
		Delete file in folder 'tmp' and the old image file in folder image
	*/

	protected function deleteFile(&$image , $oldImage , $action, $fileImage) {																	
		if(isset($_POST['img']) && $_POST['img'] == '1') {
			if($fileImage != '') {
				if($_FILES['file']['name'] == '') {
					if(file_exists('public/img/'.$fileImage)) {
						unlink('public/img/'.$fileImage);
						$image = NULL;
					}
				}
			}
		}

		if (strlen(strstr($image , 'tmp/'))) {
			$image = substr($image, 4);
			copy('public/img/tmp/' . $image , 'public/img/' . $image);
			unlink('public/img/tmp/' . $image);
		} else $image == NULL;

		if($action == 'edit') {
			if($oldImage != '' && $image != $oldImage) {
				if(file_exists('public/img/' . $oldImage)) { 
					unlink('public/img/' . $oldImage);
				}
			}
		}
	}
}