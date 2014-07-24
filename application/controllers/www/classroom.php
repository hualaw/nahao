<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header('content-type: text/html; charset=utf-8');
class Classroom extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/student/student_course');
        $this->load->model('business/student/student_classroom');
        $this->load->model('model/student/model_classroom');
        $this->load->model('model/student/model_course');
        $this->load->model('business/teacher/business_teacher','teacher_b');
        if(!$this->is_login)
        {
            redirect('/login');
        }
    }

    /**
     * 进入教室
     */
	public function index()
	{  
	    $classroom_id = $this->uri->segment(3,0);
	    //老师获得该课的所有题目
	    $param = array(
	    	'classroom_id' => $classroom_id,
	    	'status' => -1,//没出过
	    );
	    $question_list = $this->teacher_b->class_question($param);
	    $data = array(
	    	'class_questions' => $question_list,
	    	'classroom_id' => $classroom_id,
	    );

	    #根据classroom_id获取课id
	    $array_class_id = $this->model_classroom->get_class_id_by_classroom_id($classroom_id);
	    $this->smarty->assign('class_id',$array_class_id['id']);
	    $this->smarty->assign('data',$data);
        $this->smarty->display('www/classRoom/index.html');
	}
	
	/**
	 * 获取练习题数据 
	 */
	public function get_exercise()
	{
	    header('content-type: text/html; charset=utf-8');
	    $int_user_id = $this->session->userdata('user_id'); #TODO
	    #课id
	    $int_class_id = $this->input->post('class_id');
	    #获取最大批次
	    $array_sequence = $this->model_classroom->get_max_sequence($int_class_id);
	    #数组为空或者批次为0，则老师没有出题
	    if (empty($array_sequence))
	    {
	        self::json_output(array('status'=>'error','msg'=>'老师没有出题'));
	    }
	    $int_max_sequence = $array_sequence['sequence'];
	    if ($int_max_sequence == '0')
	    {
	        self::json_output(array('status'=>'error','msg'=>'老师没有出题'));
	    }

	    $array_data = $this->student_classroom->get_exercise_data($int_class_id,$int_max_sequence,$int_user_id);

	    if ($array_data['status'] == 'ok') {
	       self::json_output(array('status'=>'ok','msg'=>'获取练习题成功','data'=>$array_data['data']));
	    } else {
	       self::json_output(array('status'=>'error','msg'=>$array_data['msg']));
	    }
	}
	
	/**
	 * 保存学生提交的答题记录
	 */
	public function save()
	{
	    #课id
	    $int_class_id = $this->input->post('class_id');
	    $int_user_id = $this->session->userdata('user_id');  #TODO
	    $int_question_id = $this->input->post('question_id');
	    $str_selected = $this->input->post('selected');
	    $array_result = $this->model_classroom->get_question_infor($int_question_id);
	    if ($array_result && $array_result['answer'])
	    {
	        if($array_result['answer']== $str_selected)
	        {
	            $int_is_correct = 1;
	        }else {
	            $int_is_correct = 0;
	        }
	    }

	    $int_sequence = $this->input->post('sequence');
	    $array_data = array(
	        'class_id'=>$int_class_id,
	        'student_id'=>$int_user_id,
	        'question_id'=>$int_question_id,
	        'answer'=>$str_selected,
	        'is_correct'=>$int_is_correct,
	        'sequence'=>$int_sequence
	    );
	    $bool_flag = $this->model_classroom->save_sutdent_question($array_data);
	    if ($bool_flag)
	    {
	        self::json_output(array('status'=>'ok','msg'=>'题目提交成功',
	              'data'=>array('qid'=>$int_question_id,'answer'=>$array_result['answer'],'seleced'=>$str_selected)));
	    } else {
	        self::json_output(array('status'=>'error','msg'=>'题目提交失败',
	              'data'=>array('qid'=>$int_question_id,'answer'=>$array_result['answer'],'seleced'=>$str_selected)));
	    }
	}
	
	/**
	 * 查看做题结果
	 */
	public function get_question_result_data()
	{
	    header('content-type: text/html; charset=utf-8');
	    #课id
	    $int_class_id = $this->input->post('class_id');
	    $int_user_id = $this->session->userdata('user_id'); 
	    $int_sequence = $this->input->post('sequence');
	    $array_data = array(
            'class_id'=>$int_class_id,
            'student_id'=>$int_user_id,
	        'sequence'=>$int_sequence
	    );
	    #获取学生做题统计以及做题的记录
	    $array_data = $this->student_classroom->get_question_result_data($array_data);
	    //var_dump($array_data);
	    if ($array_data)
	    {
	        self::json_output(array('status'=>'ok','msg'=>'获取做题结果成功','data'=>$array_data));
	    } else {
	        self::json_output(array('status'=>'error','msg'=>'获取做题结果失败'));
	    }
	}

	/**↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓老师端势力范围↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓**/
	/**
	 * 老师获得该课的当前未出过所有题目
	 */
	public function teacher_get_exercise_page(){
		header("Content-type: text/html; charset=utf-8");
		$classroom_id= $this->uri->segment(3,0);
		
	    $param = array(
	    	'classroom_id' => $classroom_id,
	    	'status' => -1,//没出过
	    );
	    $question_list = $this->teacher_b->class_question($param);
	    if($question_list)
	    {
	    	self::json_output(array('status'=>'ok','msg'=>'获取未出过题目成功','data'=>$question_list));
	    }else{
	        self::json_output(array('status'=>'error','msg'=>'<h3>该课没有题目或题目已出完</h3>'));
	    }
	}
	
	/**
	 * 老师出题
	 */
	public function teacher_publish_questions(){
		header("Content-type: text/html; charset=utf-8");
		$classroom_id = $this->uri->segment(3,0);
		//查询旧批次最大值
		$sequence = $this->teacher_b->get_sequence(array('classroom_id'=>$classroom_id));
		$question_id = $this->input->post('question_id');
//		$question_id =4;
		//生成新批次
		$sequence = isset($sequence) && $sequence>0 ? ($sequence+1) : 1;
		$question_id = isset($question_id) ? rtrim($question_id,',') : '';
		if(!$question_id){exit('没有选中要发布的题目');}
		$param = array(
			'classroom_id' => $classroom_id,
			'question_id' => $question_id,
			'sequence' => $sequence,
		);
		$res = $this->teacher_b->teacher_publish_question($param);
		if($res)
	    {
	    	self::json_output(array('status'=>'ok','msg'=>'出题成功','sequence'=>$sequence));
	    }else{
	        self::json_output(array('status'=>'error','msg'=>'出题失败'));
	    }
	}
	/**
	 * 老师查看做完题的统计
	 */
	public function teacher_checkout_question_answer(){
		header("Content-type: text/html; charset=utf-8");
		$classroom_id = $this->uri->segment(3,0);
		//获取批次
		$sequence_num 		= $this->teacher_b->get_sequence(array('classroom_id' => $classroom_id));
		//总答题人数
		$answer_user_num 	= $this->teacher_b->answer_user_num(array('classroom_id'=>$classroom_id,'counter' =>2));
		
		if($sequence_num>0){
			$list = array();
			for ($i=1;$i<=$sequence_num;$i++){
				$question_list = array();
				
				$param = array(
			    	'classroom_id' => $classroom_id,
			    	'status' => 1,//出过
			    	'sequence' => $i,
			    );
			    $question_list = $this->teacher_b->class_question($param);
			    
			    $list[$i] = $question_list;
			}
			//默认显示第一批，隐藏其他
			$html = $this->teacher_b->build_question_count_html($list,1,$answer_user_num);
			self::json_output(array('status'=>'ok','msg'=>'获取答题统计成功','data'=>$html));
		}else{
			self::json_output(array('status'=>'error','msg'=>'没有出过一批题的记录,或者学生没有过做题记录'));
		}
	}
	/***↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑老师端势力范围↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑**/

    /**
     * 学生进入教室入口
     */
    public function enter()
    {
        $int_classroom_id = intval($this->uri->rsegment(3));
        if (empty($int_classroom_id))
        {
        	show_error('参数错误');
        }


        #根据classroom_id获取课id
        $array_class = $this->model_classroom->get_class_id_by_classroom_id($int_classroom_id);
        
        if(empty($array_class)){
            show_error('参数错误');
        }

        #用户是否有登陆
        #登陆的用户是否有买过这堂课
        $int_user_id = $this->session->userdata('user_id'); #TODO

        #判断用户是否买了这一堂课
        $bool_flag = $this->model_course->check_user_buy_class($int_user_id,$array_class['id']);
        if(empty($bool_flag))
        {
        	show_error('您没有购买这堂课');
        }
        #检查这节课用户在student_class里面的状态
        $array = $this->model_course->get_student_class_status($int_user_id,$array_class['id']);
        if(empty($array))
        {
        	show_error('您没有购买这堂课啊');
        }
        if($array['status'] ==STUDENT_CLASS_APPLY_REFUND || $array['status']==STUDENT_CLASS_REFUND_AGREE || $array['status']==STUDENT_CLASS_REFUND_FINISH)
        {
        	show_error('您在进行退款流程，现在不能进入教室');
        }

        
        #判断这节课是不是在"可进教室 或者 正在上课"的状态 
        if ($array_class['status'] != CLASS_STATUS_ENTER_ROOM && $array_class['status'] != CLASS_STATUS_CLASSING )
        {
       		show_error('您不能进入教室了，您的课的状态不是“正在上课或者可进教室”');
        }
        $arr_class_map = config_item('round_class_map');
        $int_classroom_id = isset($arr_class_map[$int_classroom_id]) ? $arr_class_map[$int_classroom_id] : $int_classroom_id ;
        $str_iframe = self::enter_classroom($int_classroom_id,NH_MEETING_TYPE_STUDENT,array('class_title'=>$array_class['title']));
        $arr_class_id_map = config_item('round_class_id_map');
        $array_class['id'] = isset($arr_class_id_map[$array_class['id']]) ? $arr_class_id_map[$array_class['id']] : $array_class['id'] ;
        $this->smarty->assign('classroom_id',$int_classroom_id);
        $this->smarty->assign('class_id',$array_class['id']);
        $this->smarty->assign('iframe',$str_iframe);
        $this->smarty->display('www/classRoom/index.html');

//        $str_classroom_url = 'http://www.nahaodev.com/nahao_classroom/main.html';
//        $str_iframe = '<iframe src="'.$str_classroom_url.'" width="100%" height="100%" frameborder="0" name="_blank" id="_blank" ></iframe>';
//        $str_iframe .= '<script>function student_get_exercise_page(id){console.log("asdfghj!");}//student_get_exercise_page();</script>';
//        echo $str_iframe;exit;


//        $str_classroom_url = '/classroom/main.html';
//        o($str_classroom_url,true);
//        $str_iframe = '<iframe src="'.$str_classroom_url.'" width="100%" height="100%" frameborder="0" name="_blank" id="_blank" ></iframe>';



    }
    
    /**
     * 老师进教室入口
     */
    public function teacher_enter()
    {
    	$int_classroom_id = intval($this->uri->rsegment(3));
    	if (empty($int_classroom_id))
    	{
    		show_error('参数错误');
    	}
    	
    	#根据classroom_id获取课id
    	$array_class = $this->model_classroom->get_class_id_by_classroom_id($int_classroom_id);
    	
    	if(empty($array_class)){
    		show_error('参数错误');
    	}
    	
    	#用户是否有登陆
    	#登陆的用户是否有买过这堂课
    	$int_user_id = $this->session->userdata('user_id'); #TODO
    	$array_user = $this->_user_detail;
    	$int_user_type = $array_user['teach_priv'];
    	#判断当前用户是学生还是老师。 0是学生，1是老师
    	if($int_user_type == NH_MEETING_TYPE_STUDENT)
    	{
    		show_error('您不是老师身份，不能进教室讲课');
    	}
    	if($int_user_type == NH_MEETING_TYPE_TEACHER)
    	{
    		#如果是老师判断是否是这节课的老师
    		$bool_flag = $this->student_course->check_is_teacher_in_class($int_user_id,$array_class['id']);
    		if(empty($bool_flag))
    		{
    			show_error('您不是这节课的老师');
    		}

			
			#判断这节课是不是在"可进教室 或者 正在上课"的状态 
			if ($array_class['status'] != CLASS_STATUS_ENTER_ROOM && $array_class['status'] != CLASS_STATUS_CLASSING )
			{
				   show_error('您不能进入教室了，您的课的状态不是“正在上课或者可进教室”');
			}
			
			$arr_class_map = config_item('round_class_map');
			$int_classroom_id = isset($arr_class_map[$int_classroom_id]) ? $arr_class_map[$int_classroom_id] : $int_classroom_id ;
			$str_iframe = self::enter_classroom($int_classroom_id,NH_MEETING_TYPE_TEACHER,array('class_title'=>$array_class['title']));
		}
		$arr_class_id_map = config_item('round_class_id_map');
		$array_class['id'] = isset($arr_class_id_map[$array_class['id']]) ? $arr_class_id_map[$array_class['id']] : $array_class['id'] ;
    	$this->smarty->assign('classroom_id',$int_classroom_id);
    	$this->smarty->assign('class_id',$array_class['id']);
    	$this->smarty->assign('iframe',$str_iframe);
    	$this->smarty->display('www/classRoom/index.html');
    }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
