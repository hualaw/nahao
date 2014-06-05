<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classroom extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/student/student_index');
    }

    /**
     * 进入教室
     */
	public function index()
	{  
	    header('content-type: text/html; charset=utf-8');
        $this->smarty->display('www/classRoom/index.html');
	}
	
	/**
	 * 我要学习
	 */
	public function study()
	{
	    $this->smarty->display('www/studentStudy/index.html');
	}
	
	/**
	 * 我要开课
	 */
	public function apply_teach()
	{
	    $this->smarty->display('www/studentStartClass/writeInfo.html');
	}
	
	/**
	 * 登陆
	 */
	public function login()
	{
	    $this->smarty->display('www/login/login.html');
	}
	
	/**
	 * 注册
	 */
	public function register()
	{
	    $this->smarty->display('www/login/reg.html');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */