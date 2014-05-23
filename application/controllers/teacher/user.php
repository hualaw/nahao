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
		#1.模块化和页面相关
		$nav = $this->load->view('teacher/nav',array(),true);
		$siteBar = $this->load->view('teacher/siteBar',array('active' => 'user_index'),true);
		$user_nav = $this->load->view('teacher/user/active',array('active' => 'user_index'),true);
		$info = $this->load->view('teacher/user/info',array(),true);
		$pos = $this->teacher_b->get_pos('个人资料');
		
		#2.页面数据
		$data = array(
			'nav' => $nav,
			'siteBar' => $siteBar,
			'info' => $info,
			'pos' => $pos,
			'user_nav' => $user_nav,
		);
		$this->load->view('teacher/user.php',$data);
	}
	
	/**
	 * 老师修改头像
	 */
	public function avater()
	{
		#1.模块化和页面相关
		$nav = $this->load->view('teacher/nav',array(),true);
		$siteBar = $this->load->view('teacher/siteBar',array('active' => 'user_avater'),true);
		$user_nav = $this->load->view('teacher/user/active',array('active' => 'user_avater'),true);
		$avater = $this->load->view('teacher/user/avater',array(),true);
		$pos = $this->teacher_b->get_pos('修改头像');
		
		#2.页面数据
		$data = array(
			'nav' => $nav,
			'siteBar' => $siteBar,
			'avater' => $avater,
			'pos' => $pos,
			'user_nav' => $user_nav,
		);
		$this->load->view('teacher/user_avater.php',$data);
	}
	
	/**
	 * 老师修改密码
	 */
	public function password()
	{
		#1.模块化和页面相关
		$nav = $this->load->view('teacher/nav',array(),true);
		$siteBar = $this->load->view('teacher/siteBar',array('active' => 'user_password'),true);
		$user_nav = $this->load->view('teacher/user/active',array('active' => 'user_password'),true);
		$password = $this->load->view('teacher/user/password',array(),true);
		$pos = $this->teacher_b->get_pos('修改头像');
		
		#2.页面数据
		$data = array(
			'nav' => $nav,
			'siteBar' => $siteBar,
			'password' => $password,
			'pos' => $pos,
			'user_nav' => $user_nav,
		);
		$this->load->view('teacher/user_password.php',$data);
	}
}