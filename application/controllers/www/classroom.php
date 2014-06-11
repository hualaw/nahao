<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classroom extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/student/student_classroom');
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
        $this->smarty->display('www/classRoom/index.html');
	}
	
	/**
	 * 获取练习题数据 
	 */
	public function get_exercise()
	{
	    header('content-type: text/html; charset=utf-8');
	    #课id
	    $int_class_id = $this->input->post('class_id');
	    $int_class_id = 4;
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
	    $array_data = $this->student_classroom->get_exercise_data($int_class_id,$int_max_sequence);
	    //var_dump($array_data);die;
	    if ($array_data) {
	       self::json_output(array('status'=>'ok','msg'=>'获取练习题成功','data'=>$array_data));
	    } else {
	       self::json_output(array('status'=>'error','msg'=>'获取练习题失败'));
	    }
	}
	
	/**
	 * 保存学生提交的答题记录
	 */
	public function save()
	{
	    #课id
	    $int_class_id = $this->input->post('class_id');
	    $int_user_id = $this->session->userdata('user_id');  
	    $int_question_id = $this->input->post('question_id');
	    $str_selected = $this->input->post('selected');
	    $array_result = $this->model_classroom->get_question_infor($int_question_id);
	    if ($array_result && $array_result['answer'])
	    {
	        if(!strpos($array_result['answer'],$str_selected))
	        {
	            $int_is_correct = 0;
	        }else {
	            $int_is_correct = 1;
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
	    $array_data = array(
	            'class_id'=>3,
	            'student_id'=>1,
	            'sequence'=>1
	    );
	    #获取学生做题统计以及做题的记录
	    $array_data = $this->student_classroom->get_question_result_data($array_data);
	    var_dump($array_data);die;

	    if ($array_data)
	    {
	        self::json_output(array('status'=>'ok','msg'=>'获取做题结果成功','data'=>$array_data));
	    } else {
	        self::json_output(array('status'=>'error','msg'=>'获取做题结果失败'));
	    }
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */