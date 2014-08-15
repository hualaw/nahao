<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header('content-type: text/html; charset=utf-8');
class Classes extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/student/student_course');
        $this->load->model('business/student/student_order');
    }

    // 课程列表
    function index(){
    	$this->smarty->display('www/student_class/index.html');
    }
    // 课程列表----学科辅导
    function xueke(){
    	$this->smarty->display('www/student_class/index.html');
    }
    // 课程列表----素质教育
    function suzhi(){
    	$this->smarty->display('www/student_class/index.html');
    }
    // 课程详情页面
    function article(){
    	$this->smarty->display('www/student_class/article.html');
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */