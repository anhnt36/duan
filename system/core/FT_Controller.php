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
		Phân trang
	*/
	public function pagination($number_row,$path){
		$links = new Pagination_Library(PERPAGE,'page'); 
		$links->set_total($number_row);
		return array(
			'page_links' => $links->page_links($path), 
			'page_limit' => $links->get_limit()
		);
	}

	public function process() {
		if(isset($_POST['activate'])) {
			if(!empty($_POST['c'])) {
				$act= $_POST['c'];
				foreach ($act as $val) {
					self::$object->act($val,'0');
				}
			} else {$_SESSION['activate'] = 'Please ! Click checkbox ';}
		}
		if(isset($_POST['deactivate'])) {
			if(!empty($_POST['c'])) {
				$act= $_POST['c'];
				foreach ($act as $val) {
					self::$object->act($val,'1');
				}
			} else {$_SESSION['activate'] = 'Please ! Click checkbox ';}
		}
		if(isset($_POST['delete'])) {
			if(!empty($_POST['c'])) {
				$del= $_POST['c'];
				foreach ($del as $val) {
					self::$object->delete($val);
				}
			} else {$_SESSION['activate'] = 'Please ! Click checkbox ';}
		}
		header("Location:".base_url.self::$process);
	}

	public function check_sort(&$sort,&$path){
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
	}
}