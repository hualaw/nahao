<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classroom extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/student/student_classroom');
        $this->load->model('model/student/model_classroom');
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
	    header('content-type: text/html; charset=utf-8');
	    $classroom_id = $this->uri->segment(3,0);
	    $classroom_id = 3;
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
	    #获取课id
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
	    //$int_class_id = 4;
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
	    //var_dump($array_data['data']);
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
	
	/**
	 * 进入教室
	 */
	public function enter()
	{
	    $int_classroom_id = $this->uri->rsegment(3) ? $this->uri->rsegment(3) : 0;
	    $str_classroom_urlself::enter_classroom($int_classroom_id,NH_MEETING_TYPE_STUDENT);
	    $str_iframe = '<iframe src="'.$str_classroom_url.'" width="100%" height="100%" frameborder="0" name="_blank" id="_blank" ></iframe>';
        $this->smarty->assign('iframe',$str_iframe);
        $this->smarty->assign('view', 'classroom');
        $this->smarty->display('admin/layout.html');
	}
	
    public function save_stu_action()
    {
        $class_id = intval(trim($this->input->get("class_id")));
        $user_id = intval(trim($this->input->get("user_id")));
        $action_type = intval(trim($this->input->get("type")));

        $info = array(
            'class_id' => $class_id,
            'user_id' => $user_id,
            'action_type' => $action_type,
        );
        if($class_id <= 0 OR $user_id <= 0 OR $action_type <= 0)
        {
            log_message('error_nahao', "save student class action failed", $info);
            die(ERROR);
        }

        $this->load->model('model/student/model_student_class_log', 'stu_obj');
        $this->stu_obj->save_action($class_id, $user_id, $action_type);
        log_message('info_nahao', "save student class action", $info);
        die(SUCCESS);
    }

    public function get_action_stat()
    {
        $class_id = intval(trim($this->input->get("class_id")));
        if($class_id <= 0)
        {
            log_message('error_nahao', "get class action stat failed", array('class_id'=>$class_id));
        }
        $this->load->model('model/student/model_student_class_log', 'stu_obj');
        $result = $this->stu_obj->get_action_stat($class_id);

        $arr_return = array(
            'please_total_count' => 0,
            'slower_total_count' => 0,
            'faster_total_count' => 0,
        );
        if(!empty($result))
        {
            foreach($result as $val)
            {
                if($val['action'] == CLASS_PLEASE_ACTION) $arr_return['please_total_count'] = $val['count'];
                else if($val['action'] == CLASS_SLOWER_ACTION) $arr_return['slower_total_count'] = $val['count'];
                else if($val['action'] == CLASS_FASTER_ACTION) $arr_return['faster_total_count'] = $val['count'];
            }
        }

        $str_return = "{\"please_total_count\":{$arr_return['please_total_count']},";
        $str_return .=  "\"slower_total_count\":{$arr_return['slower_total_count']},";
        $str_return .=  "\"faster_total_count\":{$arr_return['faster_total_count']}}";

        die($str_return);
    }

    /**
     * 课堂笔记API 课堂调用
     * @author yanrui@91waijiao.com
     */
    public function class_note(){
        $log_path = PATH_SEPARATOR==':' ? '/tmp/' : 'c:/wamp/logs/';
        header("Content-type: text/html; charset=utf-8");
        error_reporting(E_ALL);
        ini_set('display_errors', true);
        $int_class_id = intval($this->input->post('cid'));
        $int_student_id = intval($this->input->post('uid'));
        $str_content = trim($this->input->post('notes'));
        if($int_class_id==0 AND $int_student_id==0 AND $str_content==''){
            die('param error');
        }
        /* $str_content = urldecode(gzinflate((string)base64_decode($str_content)));
//        $str_content_log = iconv('UTF-8','GBK',$str_content_db);*/
        $int_student_id = substr($int_student_id,0,strlen($int_student_id)-1);
        $str_content = urldecode(gzinflate((string)base64_decode($str_content)));

//        $int_class_id = intval($this->input->get('cid'));
//        $int_student_id = intval($this->input->get('uid'));
//        $str_content = trim($this->input->get('notes'));
//        if($int_class_id==0 AND $int_student_id==0 AND $str_content==''){
//            die('param error');
//        }

//        error_log('--'.$int_class_id.'--'.$int_student_id.'--'.$str_content."\n",3,'c:/wamp/logs/test.log');
        $this->load->model('business/student/student_classroom','classroom');
        $data = array(
            'class_id' => $int_class_id,
            'student_id' => $int_student_id,
//            'content' => $this->boolMagic ? $str_content_db : addslashes($str_content_db),
            'content' => mysql_real_escape_string($str_content),
            'create_time' => TIME_STAMP,
            'update_time' => TIME_STAMP
        );
//        var_dump($data);
        error_log(TIME_STAMP.'  '.date('Y-m-d H:i:s',TIME_STAMP).'  cid:'.$int_class_id.'    sid:'.$int_student_id.' content:'.$str_content."\n",3,$log_path.'class_note.log');
        $return = $this->classroom->save_class_note($data);
//        error_log($this->db->last_query()."\n",3,$log_path.'class_note.log');
//        echo $return ? 1 : 0;
    }
	/**↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓老师端势力范围↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓**/
	/**
	 * 老师获得该课的所有题目
	 */
	public function teacher_get_exercise_page(){
		
	}
	
	/**
	 * 老师出题
	 */
	public function teacher_publish_questions(){
		$int_course_id = $this->input->post("course_id");
	    $array_data = array(
	            'course_id'=>$int_course_id,
	    );
	}
	
	/**
	 * 老师查看做完题的统计
	 */
	public function teacher_checkout_question_answer(){
		
	}
	/***↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑老师端势力范围↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑**/
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
