<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->model('business/teacher/business_teacher','teacher_b');
		$this->load->model('model/teacher/model_teacher','teacher_m');
    }
    
	/**
	 * 老师个人资料
	 */
	public function index()
	{
		#2.页面数据
		$data = array(
			'title' => '个人资料',
			'active' => 'user_index',
		);
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/user.html');
	}
	
	/**
	 * 老师修改头像
	 */
	public function avater()
	{
		#2.页面数据
		$data = array(
			'title' => '修改头像',
			'active' => 'user_avater',
		);
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/user_avater.html');
	}
	
	/**
	 * 老师修改密码
	 */
	public function password()
	{
		#2.页面数据
		$data = array(
			'title' => '修改密码',
			'active' => 'user_password',
		);
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/user_password.html');
	}
}