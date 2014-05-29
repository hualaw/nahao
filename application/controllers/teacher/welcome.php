<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends NH_User_Controller {

	public function __construct(){
		/**
		 * 使用：
		 * 1. 读config：   config_item("subject");
		 * 2. 调redis：	  加载model/redis_model.php
		 * 3. 
		 */
        parent::__construct();
        $this->load->model('business/teacher/business_teacher','teacher_b');
        $this->load->model('model/teacher/model_teacher','teacher_m');
    }
    
	/**
	 * 老师端口首页
	 * 当天即将开课提醒
	 */
	public function index()
	{
		#1.今日列表
		$listArr = $this->teacher_b->today_class(array('teacher_id'=>1));
		#3.页面数据
		$data = array(
			'listArr' => $listArr,
			'active' => 'welcome_index',
			'title' => '今日上课',
			'host' => 'http://'.$_SERVER ['HTTP_HOST'],
		);
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/teacherHomePage/index.html');
	}
	
	/**
	 * 我要开课
	 */
	public function apply_teach()
	{
	    $this->smarty->display('www/studentStartClass/writeInfo.html');
	}
}