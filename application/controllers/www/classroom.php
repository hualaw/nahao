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
	    $str_answer = $this->input->post('answer');
	    if ($str_answer == $str_selected)
	    {
	        $int_is_correct = 1;
	    } else {
	        $int_is_correct = 0;
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
	              'data'=>array('qid'=>$int_question_id,'answer'=>$str_answer,'seleced'=>$str_selected)));
	    } else {
	        self::json_output(array('status'=>'error','msg'=>'题目提交失败',
	              'data'=>array('qid'=>$int_question_id,'answer'=>$str_answer,'seleced'=>$str_selected)));
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
	


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */