<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_login extends NH_User_Controller {

	public function __construct(){
        parent::__construct();
        header("Content-type: text/html; charset=utf-8");
        $this->load->model('business/common/business_login');
    }
    
    /**
	 * 管理员模拟老师登陆到学生端，理论上是所有用户
	 */ 
    public function login_without_pwd(){
    	$user_id = $this->uri->segment(3,0);
    	$code = $this->uri->segment(4,0);
    	if(empty($user_id) || empty($code)){exit('<script>alert("用户id和加密串不能为空");window.location.href="'.student_url().'";</script>');}
    	$this->business_login->login_without_pwd($user_id,$code);
    	redirect(student_url());
    }
}