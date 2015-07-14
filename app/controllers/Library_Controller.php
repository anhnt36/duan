<?php if ( ! defined('PATH_SYSTEM')) die ('Bad requested!');
 
class Library_Controller extends FT_Controller
{
    public function indexAction()
    {
        // Tạo mới thư viện
        $this->library->load('upload');
         
        // Gọi đến phương thức upload
        $this->library->upload->upload();
    }
}