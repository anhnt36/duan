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

		$this->helper = new FT_Helper_Loader;

		$this->config = new FT_Config_Loader;

		$this->controller = new FT_Controller_Loader;

		date_default_timezone_set('Asia/Ho_Chi_Minh');
	}

	/*
		Pagination
	*/
	public function pagination($number_row,$path){
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
					} else $_SESSION['error'] = "Note : You can't delete your account !";
				}
			} else {$_SESSION['activate'] = 'Please ! Click checkbox ';}
		}
		header("Location:".base_url.self::$process);
	}
	/*
		
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
		Sort follow id ,name ,activate ,price ,createdTime ,updatedTime
	*/
	public function check_sort(&$sort,&$path){

		if(isset($_GET['s'])) { //			Check to sort follow id and name
			if($_GET['s']=='id') {
				$sort = "order by id ";
				$path = '?s=id&';
			}
			if($_GET['s']=='activate') {
				$sort = "order by activate ";
				$path = '?s=activate&';
			}
			if($_GET['s']=='price') {
				$sort = "order by price ";
				$path = '?s=price&';
			}
			if($_GET['s']=='createdTime') {
				$sort = "order by createdTime ";
				$path = '?s=createdTime&';
			}
			if($_GET['s']=='updatedTime') {
				$sort = "order by updatedTime ";
				$path = '?s=updatedTime&';
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
	}
	/*
		Encode file image = md5(fileName) + md5(currentTime)
	*/
	public function encodeImage(){
		$time = md5(date("Y-m-d H:i:s"));
		$md5 = md5($_FILES['file']['name']).md5($time).'.'.substr($_FILES['file']['type'], 6);
		return $md5;
	}

	/*
		Handling file
	*/
	public function handlingFile(&$data, $arrayUser,$action='edit',$file='') {
				if($_FILES['file']['name'] == '') {
					if($action == 'edit') {
						if(!isset($_SESSION['fileImage']) || $_SESSION['fileImage'] == '') $data = $arrayUser;
						else {
							$data = $_SESSION['fileImage'];
						}
					}
					else {
						if(isset($_SESSION['fileImage']) && $_SESSION['fileImage'] != '') {
							$data = $_SESSION['fileImage'];
						} else $data='';
					}
				} else {
					if(isset($_SESSION['fileImage']) && $_SESSION['fileImage'] != '') {
						unlink('public/img/'.$_SESSION['fileImage']);
						unset($_SESSION['fileImage']);
					}
					if($file) {
						$data = 'tmp/'.$data;
						move_uploaded_file($_FILES['file']['tmp_name'],'public/img/'.$data);
						$_SESSION['fileImage'] = $data;
					}
				}
				if(isset($_POST['img']) && $_POST['img'] == '1') {
					
					if($_FILES['file']['name'] == '') { 
						if(isset($_SESSION['fileImage']) && $_SESSION['fileImage'] != '') {
							unlink('public/img/'.$_SESSION['fileImage']);
							unset($_SESSION['fileImage']);
						}
						$data = '';
					}
				}
	}
}