<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header('content-type: text/html; charset=utf-8');
class Classroom extends NH_Admin_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/student/student_course');
        $this->load->model('business/student/student_classroom');
        $this->load->model('model/student/model_classroom');
        $this->load->model('model/student/model_course');
        $this->load->model('business/teacher/business_teacher','teacher_b');
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

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
