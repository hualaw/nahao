<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
		$listArr = $this->teacher_b->get_today_round(array('user_id'=>1));
		#2.模块化和页面相关
		$nav = $this->load->view('teacher/nav',array(),true);
		$siteBar = $this->load->view('teacher/siteBar',array(),true);
		$listStr = $this->load->view('teacher/index/today_list',array('list'=>$listArr),true);
		$pos = $this->teacher_b->get_pos('今日上课');
		
		#3.页面数据
		$data = array(
			'nav' => $nav,
			'siteBar' => $siteBar,
			'today_list' => $listStr,
			'pos' => $pos,
		);
		$this->load->view('teacher/index.php',$data);
	}
}