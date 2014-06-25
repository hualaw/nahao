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
        header("Content-type: text/html; charset=utf-8");
        $this->load->model('business/teacher/business_teacher','teacher_b');
        $this->load->model('model/teacher/model_teacher','teacher_m');
        if(!$this->is_login)
        {
            redirect(student_url().'login');
        }
        if(!($this->session->userdata('user_type')==1))
        {
        	exit('<script>alert("您还不是那好课堂的老师！");window.location.href="'.student_url().'";</script>');
        }
        $this->teacher_id = $this->session->userdata('user_id');
    }
    
	/**
	 * 老师端口首页
	 * 当天即将开课提醒
	 */
	public function index()
	{
		$page = $this->uri->segment(3,1);
		#1.分页
     	$this->load->library('pagination');
        $config = config_item('page_teacher');
        $config['base_url'] = '/index/index';
        $config['use_page_numbers'] = TRUE;
        $param = array(
     			'teacher_id' 	=> $this->teacher_id,
     			'begin_time' 	=> strtotime(date("Y-m-d")),
	     		'end_time' 		=> strtotime(date("Y-m-d",strtotime("+1 day"))),
	     		'parent_id' 	=> -2,
     			'counter' 		=> 1,
     		);
     	
     	$int_count = $this->teacher_b->class_list($param);
        $config['total_rows'] = $int_count;
        $config['per_page'] = 10;
        $this->pagination->initialize($config);
        $pageBar = $this->pagination->create_links();
        #2.今日列表
		$listArr = $this->teacher_b->class_list(array(
				'teacher_id'	=> $this->teacher_id,
				'begin_time' 	=> strtotime(date("Y-m-d")),
	     		'end_time' 		=> strtotime(date("Y-m-d",strtotime("+1 day"))),
	     		'parent_id' 	=> -2,
	     		'order' 		=> 2,
	     		'limit'			=> (($page-1)*$config['per_page']).','.$config['per_page'],
	     		'no_sort'		=> 1,//特殊处理，不排序章节
			));
		$weekarray = array("日","一","二","三","四","五","六");
		#3.页面数据
		$data = array(
			'listArr' 			=> $listArr,
			'active' 			=> 'index_index',
			'title' 			=> '今日上课',
			'host' 				=> 'http://'.$_SERVER ['HTTP_HOST'],
			'today_total' 		=> $int_count,
			'date' 				=> date('Y年m月d日',time()),
			'week' 				=> $weekarray[date('w')],
			'enter_classroom'	=> array(CLASS_STATUS_ENTER_ROOM,CLASS_STATUS_CLASSING),
			'pageBar'			=> $pageBar,
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