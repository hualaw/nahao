<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orderlist extends NH_User_Controller {

   public function __construct(){
		/**
		 * 使用：
		 * 1. 读config：   config_item("subject");
		 * 2. 调redis：	  加载model/redis_model.php
		 */
        parent::__construct();
        $this->smarty->assign('site_url',__HOST__);
        $this->load->model('business/teacher/business_teacher','teacher_b');
        $this->load->model('model/teacher/model_teacher','teacher_m');
    }
    
	public function index(){
		$teach_status = config_item("class_teach_status");
		$course_type = config_item("course_type");
		$param = array(
     			'teacher_id' => 1,
     			'parent_id' => -2,
     			'status' => isset($_GET['status']) ? $_GET['status'] : "1,2,3",
     			'round_id' => isset($_GET['round_id']) ? $_GET['round_id'] : '',
     			'round_title' => isset($_GET['round_title']) ? $_GET['round_title'] : '',
     			'begin_time' => isset($_GET['begin_time']) ? $_GET['begin_time'] : '',
     			'end_time' => isset($_GET['end_time']) ? $_GET['end_time'] : '',
     		);
		#1.今日列表
		$listArr = $this->teacher_b->class_list($param);
		
		#3.页面数据
		$data = array(
			'listArr' => $listArr,
			'active' => 'orderlist_index',
			'title' => '课程列表',
		);
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/teacherOrderList/index.html');
	}
	
	public function order_appraise(){
		$this->smarty->display('teacher/teacherOrderList/order_appraise.html');
	}
	
	public function order_count(){
		$this->smarty->display('teacher/teacherOrderList/order_count.html');
	}
	
	public function order_detail(){
		$this->smarty->display('teacher/teacherOrderList/order_detail.html');
	}
	
	public function order_manage(){
		$this->smarty->display('teacher/teacherOrderList/order_manage.html');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */