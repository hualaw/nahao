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
        if(!$this->is_login)
        {
            redirect('http://www.nahaodev.com/login');
        }
        header("Content-type: text/html; charset=utf-8");
    }
    
	public function index(){
		$teach_status = config_item("round_teach_status");
		$page = $this->uri->segment(3,1);
		//分页
     	$this->load->library('pagination');
        $config = config_item('page_teacher');
        $config['suffix'] = '/?'.$this->input->server('QUERY_STRING');
        $config['base_url'] = '/orderList/index';
        $param = array(
     			'teacher_id' 	=> $this->teacher_id,
     			'teach_status' 	=> isset($_GET['teach_status']) ? $_GET['teach_status'] : "0,1,2,3",
     			'course_type' 	=> isset($_GET['course_type']) ? $_GET['course_type'] : "",
     			'id' 			=> isset($_GET['id']) ? $_GET['id'] : '',
     			'title' 		=> isset($_GET['title']) ? $_GET['title'] : '',
     			'start_time' 	=> isset($_GET['start_time']) ? $_GET['start_time'] : '',
     			'end_time' 		=> isset($_GET['end_time']) ? $_GET['end_time'] : '',
     			'counter' 		=> 1,
     		);
     	$int_count = $this->teacher_b->round_list($param);
        $config['total_rows'] = $int_count;
        $config['per_page'] = 1;
        $this->pagination->initialize($config);
        $pageBar = $this->pagination->create_links();
        //内容
		$param = array(
     			'teacher_id' 	=> $this->teacher_id,
     			'teach_status' 	=> isset($_GET['teach_status']) ? $_GET['teach_status'] : "0,1,2,3",
     			'course_type' 	=> isset($_GET['course_type']) ? $_GET['course_type'] : "",
     			'id' 			=> isset($_GET['id']) ? $_GET['id'] : '',
     			'title' 		=> isset($_GET['title']) ? $_GET['title'] : '',
     			'start_time' 	=> isset($_GET['start_time']) ? $_GET['start_time'] : '',
     			'end_time' 		=> isset($_GET['end_time']) ? $_GET['end_time'] : '',
     			'limit' 		=> !empty($page) ? (($page-1)*$config['per_page']).','.$config['per_page'] : '0,'.$config['per_page'],
     		);
		#1.列表
		$listArr = $this->teacher_b->round_list($param);
		
		#3.页面数据
		$data = array(
			'listArr' 				=> $listArr,
			'active' 				=> 'orderlist_index',
			'title' 				=> '课程列表',
			'course_type_list' 		=> $this->teacher_b->get_course_type(),
			'subject_list' 			=> $this->teacher_b->get_subject(),
			'teach_status_list' 	=> $teach_status,
			'teach_status_count' 	=> $this->teacher_b->round_status_count(array('teacher_id'=>$this->teacher_id)),
			'pageBar'				=> $pageBar,
		);
		
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/teacherOrderList/index.html');
	}
	
	//评价
	public function comment(){
		#1.课堂信息
		$class_id 	= $this->uri->segment(3,0);
		$param 		= array(
				'teacher_id' => $this->teacher_id,
     			'id' => isset($class_id) ? $class_id : '',
     		);
		$zjList 	= $this->teacher_b->class_list($param);
		$arr 		= array_pop($zjList);
		$jInfo 		= isset($arr['jArr'][0]) ? $arr['jArr'][0] : '';
		#2.评价信息
		$comment 	= $this->teacher_b->class_comment(array('class_id'=>$class_id));
		
		$data = array(
			'jInfo' 	=> $jInfo,
			'active' 	=> 'orderlist_appraise',
			'title' 	=> '课程评价',
			'comment' 	=> $comment,
			'total' 	=> count($comment),
		);
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/teacherOrderList/order_appraise.html');
	}
	
	//答题统计
	public function count(){
		#1.课堂信息
		$class_id 		= $this->uri->segment(3,0);
		$sequence_id 	= $this->uri->segment(4,1);
		$param 			= array(
				'teacher_id' => $this->teacher_id,
     			'id' => isset($class_id) ? $class_id : '',
     		);
		$zjList 		= $this->teacher_b->class_list($param);
		$arr 			= array_pop($zjList);
		$jInfo 			= isset($arr['jArr'][0]) ? $arr['jArr'][0] : '';
		#2.统计信息
		$sequence_num 		= $this->teacher_b->get_sequence(array('class_id' => $class_id));//获取批次
		$total 				= $this->teacher_b->class_question(array('class_id'=>$class_id,'counter'=>2));//当前课所有题数目,含没出过。
		$question 			= $this->teacher_b->class_question(array('class_id'=>$class_id,'sequence'=>$sequence_id));#当前批次题
		$answer_user_num 	= $this->teacher_b->answer_user_num(array('class_id' => $class_id,'sequence'=>$sequence_id,'count'=>2));//当前批次答题人数
		
		$data = array(
			'jInfo' 			=> $jInfo,
			'active' 			=> 'orderlist_count',
			'title' 			=> '答题统计',
			'question' 			=> $question,
			'answer_user_num' 	=> $answer_user_num,
			'sequence_num' 		=> $sequence_num,
			'total' 			=> $total,
			'sequence_id' 		=> $sequence_id,
		);
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/teacherOrderList/order_count.html');
	}
	
	//章节详细
	public function detail(){
		$round_id = $this->uri->segment(3,0);
		$param = array(
				'teacher_id' 	=> $this->teacher_id,
     			'round_id' 		=> isset($round_id) ? $round_id : '',
     			'order' 		=> 3,
     		);
		$zjList = $this->teacher_b->class_list($param);
		
		$data = array(
			'zjList' 		=> $zjList,
			'active' 		=> 'orderlist_detail',
			'title' 		=> '班次详情',
			'status_count' 	=> $this->teacher_b->count_zj_status($zjList),
		);
		
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/teacherOrderList/order_detail.html');
	}
	
	//题目管理
	public function question(){
		#1.课堂信息
		$class_id = $this->uri->segment(3,0);
		$param = array(
				'teacher_id' 	=> $this->teacher_id,
     			'id' 			=> isset($class_id) ? $class_id : '',
     		);
		$zjList 	= $this->teacher_b->class_list($param);
		$arr 		= array_pop($zjList);
		$jInfo 		= isset($arr['jArr'][0]) ? $arr['jArr'][0] : '';
		#2.评价信息
		$question 	= $this->teacher_b->class_question(array('class_id'=>$class_id));
		
		$data = array(
			'jInfo' 	=> $jInfo,
			'active' 	=> 'orderlist_question',
			'title' 	=> '课程练习题',
			'question' 	=> $question,
			'total' 	=> count($question),
		);
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/teacherOrderList/order_manage.html');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */