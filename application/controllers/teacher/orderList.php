<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orderlist extends NH_User_Controller {
   
   public $teacher_id;
   public function __construct(){
		/**
		 * 使用：
		 * 1. 读config：   config_item("subject");
		 * 2. 调redis：	  加载model/redis_model.php
		 */
        parent::__construct();
        $this->smarty->assign('site_url','http://'.__HOST__);
        $this->teacher_id = 1;
        $this->load->model('business/teacher/business_teacher','teacher_b');
        $this->load->model('model/teacher/model_teacher','teacher_m');
    }
    
	public function index(){
		$teach_status = config_item("round_teach_status");
		$param = array(
     			'teacher_id' => $this->teacher_id,
     			'teach_status' => isset($_GET['teach_status']) ? $_GET['teach_status'] : "0,1,2,3",
     			'course_type' => isset($_GET['course_type']) ? $_GET['course_type'] : "",
     			'id' => isset($_GET['id']) ? $_GET['id'] : '',
     			'title' => isset($_GET['title']) ? $_GET['title'] : '',
     			'start_time' => isset($_GET['start_time']) ? $_GET['start_time'] : '',
     			'end_time' => isset($_GET['end_time']) ? $_GET['end_time'] : '',
     			'limit' => isset($_GET['page']) ? (($_GET['page']-1)*10).',10' : '0,10',
     		);
		#1.今日列表
		$listArr = $this->teacher_b->round_list($param);
		#3.页面数据
		$data = array(
			'listArr' => $listArr,
			'active' => 'orderlist_index',
			'title' => '课程列表',
			'course_type_list' => $this->teacher_b->get_course_type(),
			'subject_list' => $this->teacher_b->get_subject(),
			'teach_status_list' => $teach_status,
			'teach_status_count' => $this->teacher_b->round_status_count(array('teacher_id'=>$this->teacher_id)),
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
		$round_id = $this->uri->segment(3,0);
		$param = array(
				'teacher_id' => $this->teacher_id,
     			'round_id' => isset($round_id) ? $round_id : '',
     			'order' => 3,
     		);
		$zjList = $this->teacher_b->class_list($param);
		$data = array(
			'zjList' => $zjList,
			'active' => 'orderlist_order_detail',
			'title' => '班次详情',
		);
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/teacherOrderList/order_detail.html');
	}
	
	public function order_manage(){
		$this->smarty->display('teacher/teacherOrderList/order_manage.html');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */