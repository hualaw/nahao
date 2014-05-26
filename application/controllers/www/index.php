<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/student/student_index');
    }

    /**
     * 首页获取轮的列表信息
     */
	public function index()
	{  
        header('content-type: text/html; charset=utf-8');
        $array_data = $this->student_index->get_course_latest_round_list();
        $this->smarty->assign('array_data', $array_data);
        $this->smarty->display('www/index.html');
	}
	
	/**
	 * 我要学习
	 */
	public function study()
	{
	    $this->smarty->display('www/study/index.html');
	}
	
	/**
	 * 我要开课
	 */
	public function apply_teach()
	{
	    $this->smarty->display('www/startClass/writeInfo.html');
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