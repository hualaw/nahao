<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OrderList extends NH_User_Controller {
   
   public $teacher_id;
   public function __construct(){
		/**
		 * 使用：
		 * 1. 读config：   config_item("subject");
		 * 2. 调redis：	  加载model/redis_model.php
		 */
        parent::__construct();
        header("Content-type: text/html; charset=utf-8");
        $this->smarty->assign('site_url','http://'.__HOST__);
        $this->load->model('business/teacher/business_teacher','teacher_b');
        $this->load->model('model/teacher/model_teacher','teacher_m');
        $this->load->model('business/admin/business_class','class');
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
    
	public function index(){
		$teach_status = config_item("round_teach_status");
		$page = $this->uri->segment(3,1);
		//分页
     	$this->load->library('pagination');
        $config = config_item('page_teacher');
        $config['suffix'] = '/?'.$this->input->server('QUERY_STRING');
        $config['base_url'] = '/orderList/index';
        $config['use_page_numbers'] = TRUE;
        $param = array(
     			'teacher_id' 	=> $this->teacher_id,
     			'teach_status' 	=> isset($_GET['teach_status']) ? $_GET['teach_status'] : "0,1,2,3,4",
     			'sale_status'	=> '2,3,4,5,6',//排除过审核的
     			'course_type' 	=> isset($_GET['course_type']) ? $_GET['course_type'] : "",
     			'id' 			=> isset($_GET['id']) ? $_GET['id'] : '',
     			'title' 		=> isset($_GET['title']) ? $_GET['title'] : '',
     			'start_time' 	=> isset($_GET['start_time']) ? strtotime($_GET['start_time']) : '',
     			'end_time' 		=> isset($_GET['end_time']) ? strtotime($_GET['end_time']) : '',
     			'counter' 		=> 1,
     		);
     	$int_count = $this->teacher_b->round_list($param);
        $config['total_rows'] = $int_count;
        $config['per_page'] = 10;
        $this->pagination->initialize($config);
        $pageBar = $this->pagination->create_links();
        //内容
		$param = array(
     			'teacher_id' 	=> $this->teacher_id,
     			'teach_status' 	=> isset($_GET['teach_status']) ? $_GET['teach_status'] : "0,1,2,3,4",
     			'sale_status'	=> '2,3,4,5,6',//排除过审核的
     			'course_type' 	=> isset($_GET['course_type']) ? $_GET['course_type'] : "",
     			'id' 			=> isset($_GET['id']) ? $_GET['id'] : '',
     			'title' 		=> isset($_GET['title']) ? $_GET['title'] : '',
     			'start_time' 	=> isset($_GET['start_time']) ? strtotime($_GET['start_time']) : '',
     			'end_time' 		=> isset($_GET['end_time']) ? strtotime($_GET['end_time']) : '',
     			'limit' 		=> !empty($page) ? (($page-1)*$config['per_page']).','.$config['per_page'] : '0,'.$config['per_page'],
     		);
		#1.列表
		$listArr = $this->teacher_b->round_list($param);
		#2.初始化参数
		$query_string = $this->input->server('QUERY_STRING');
		if(!empty($query_string)){
			parse_str($query_string,$query_param);
		}else{
			$query_param = array('id'=>'','title'=>'','teach_status'=>'','course_type'=>'','start_time'=>'','end_time'=>'');
		}
		#3.页面数据
		$data = array(
			'ids'					=> $this->teacher_m->teacher_round_ids(array('teacher_id' => $this->teacher_id)),
			'listArr' 				=> $listArr,
			'active' 				=> 'orderlist_index',
			'title' 				=> '课程列表',
			'course_type_list' 		=> $this->teacher_b->get_course_type(),
			'subject_list' 			=> $this->teacher_b->get_subject(),
			'teach_status_list' 	=> $teach_status,
			'teach_status_count' 	=> $this->teacher_b->round_status_count(array('teacher_id'=>$this->teacher_id)),
			'pageBar'				=> $pageBar,
			'query_param'			=> $query_param,
			'enter_classroom'		=> array(CLASS_STATUS_ENTER_ROOM,CLASS_STATUS_CLASSING),
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
		$round_info = $this->teacher_b->round_list(array('id'=>$round_id,'teacher_id'=>$this->teacher_id));
		if(!isset($round_info[0])){exit('<script>alert("该班次信息不全");history.go(-1);</script>');}
		$param = array(
				'teacher_id' 	=> $this->teacher_id,
     			'round_id' 		=> isset($round_id) ? $round_id : '',
     			'order' 		=> 5,
     		);
		$zjList = $this->teacher_b->class_list($param);
		
		$data = array(
			'zjList' 			=> $zjList,
			'round_info'		=> $round_info[0],
			'active' 			=> 'orderlist_detail',
			'title' 			=> '班次详情',
			'status_count' 		=> $this->teacher_b->count_zj_status($zjList),
			'enter_classroom'	=> array(CLASS_STATUS_ENTER_ROOM,CLASS_STATUS_CLASSING),
		);
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/teacherOrderList/order_detail.html');
	}
	
	/**
	 * 请求会议系统,获取token
	 */
	public function getToken(){
		$arr_token = array(
            'token' => get_meeting_token(0,NH_MEETING_TYPE_SUPER_ADMIN)
        );
        self::json_output($arr_token);
	}
	
	/**
	 * 上传讲义
	 */
	public function add_courseware(){
		$int_class_id = $this->input->post('class_id') ? intval($this->input->post('class_id')) : 0;
        $int_courseware_id = $this->input->post('courseware_id') ? intval($this->input->post('courseware_id')) : 0;
        $int_create_time = $this->input->post('create_time') ? strtotime($this->input->post('create_time')) : 0;
        $str_filename = $this->input->post('filename') ? trim($this->input->post('filename')) : '';
        $int_filesize = $this->input->post('filesize') ? intval($this->input->post('filesize')) : 0;
        $int_filetype = $this->input->post('filetype') ? intval($this->input->post('filetype')) : 0;
		$arr_response = array(
    		'status' 	=> 'error',
    		'msg'		=> '添加失败',
    	);
        if($int_class_id > 0 AND $int_courseware_id > 0 AND $int_create_time > 0 AND $str_filename){
            $arr_courseware = array(
                'id' => $int_courseware_id,
                'create_time' => $int_create_time,
                'name' => $str_filename,
                'filesize' => $int_filesize,
                'filetype' => $int_filetype,
            );
            $bool_return = $this->class->add_courseware($int_class_id,$arr_courseware);
            if($bool_return==true){
            	$arr_response = array(
            		'status' 	=> 'ok',
            		'msg'		=> '添加成功',
            	);
            }
        }
        self::json_output($arr_response);
	}
	
	/**
     * 刷新教材
     */
    public function reload(){
        $int_classroom_id = $this->input->post('classroom_id') ? intval($this->input->post('classroom_id')) : 0;
        $arr_response = array(
            'status' => 'error',
            'msg' => '刷新失败'
        );
        if($int_classroom_id > 0){
            $arr_old_ids = get_coursewares_by_classroom_id($int_classroom_id);
            if($arr_old_ids){
                foreach($arr_old_ids as $id){
                    delete_courseware_by_classroom_id($int_classroom_id,$id);
                }
            }
            $arr_class = $this->class->get_class_by_classroom_id($int_classroom_id);
            if($arr_class AND $arr_class['courseware_id']){
                set_courseware_to_classroom($int_classroom_id,$arr_class['courseware_id']);
            }
            reload_courseware($int_classroom_id);
            $arr_response = array(
                'status' => 'ok',
                'msg' => '刷新成功'
            );
        }
        self::json_output($arr_response);
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
		#2.题目列表
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
	
	/**
	 * 课件预览
	 */
	public function courseware_preview(){
		$courseware_id = $this->uri->segment(3,0);
        if($courseware_id){
            $arr_coruseware = get_courseware_info($courseware_id);
            $this->smarty->assign('coruseware_id', $arr_coruseware['id']);
            $this->smarty->assign('pagenum', $arr_coruseware['pagenum']);
            $this->smarty->assign('swfpath', $arr_coruseware['swfpath']);
            $this->smarty->assign('view', 'preview');
            $this->smarty->display('teacher/teacherOrderList/courseware_preview.html');
        }else{
        	exit('<script>alert("缺少课件参数");history.go(-1);</script>');
        }
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */