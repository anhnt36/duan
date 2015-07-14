<?php if(!defined('PATH_SYSTEM')) die ('Bad requested!');

class FT_Controller {
	public $view = NULL;
	public $model = NULL;
	public $library = NULL;
	public $helper = NULL;
	public $config = NULL;
	public $controller = NULL;
	
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
}