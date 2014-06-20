<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends NH_User_Controller {

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
        if(!$this->is_login)
        {
            redirect(student_url().'login');
        }
        $this->teacher_id = $this->session->userdata('user_id');
        header("Content-type: text/html; charset=utf-8");
    }
    
	/**
	 * 老师端口首页
	 * 当天即将开课提醒
	 */
	public function index()
	{
		#1.今日列表
		$listArr = $this->teacher_b->today_class(array('teacher_id'=>$this->teacher_id));
		$weekarray = array("日","一","二","三","四","五","六");
		#3.页面数据
		$data = array(
			'listArr' => $listArr,
			'active' => 'index_index',
			'title' => '今日上课',
			'host' => 'http://'.$_SERVER ['HTTP_HOST'],
			'today_total' => count($listArr),
			'date' => date('Y年m月d日',time()),
			'week' => $weekarray[date('w')],
		);
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/teacherHomePage/index.html');
	}
	
//	/**
//	 * 我要开课
//	 */
//	public function apply_teach()
//	{
//		$param['stage'] = config_item('stage');
//		$param['teacher_title'] = config_item('teacher_title');
//		$param['teacher_type'] = config_item('teacher_type');
//		$param['subject'] = $this->subject->get_subjects();
//		$data = array(
//			'data' => $param,
//		);
//		$this->smarty->assign('data',$data);
//	    $this->smarty->display('www/studentStartClass/writeInfo.html');
//	}
//	
//	/**
//	 * 我要开课,提交
//	 */
//	public function apply_teach_doAdd()
//	{
//		
//		$param['question'] = $this->input->post('question');
//		
//	}
}