<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends NH_User_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('business/teacher/business_teacher','teacher_b');
        $this->load->model('model/teacher/model_teacher','teacher_m');
    }
    
	/**
	 * 【老师端】老师获得没出过的练习题
	 */
	public function teacher_get_exercise_page()
	{
		$classroom_id= $this->input->get('classroom_id');
	    $param = array(
	    	'classroom_id' => $classroom_id,
	    	'status' => -1,//没出过
	    );
	    $question_list = $this->teacher_b->class_question($param);
	    echo json_encode($question_list);
	    die;
	}
	
	/**
	 * 【老师端】查看答题统计
	 */
	public function teacher_get_exercise_stat()
	{
		$classroom_id = $this->input->get('classroom_id');
		$param = array(
				'classroom_id' => $classroom_id
				);
		$sequence_num 		= $this->teacher_b->get_sequence($param);//获取批次
		
		if($sequence_num>0){
			$list = array();
			for ($i=1;$i<=$sequence_num;$i++){
				$question_list = array();
				$sequence_id = $this->teacher_b->get_sequence($param);
				$param = array(
			    	'class_id' => $class_id,
			    	'status' => 1,//出过
			    	'sequence_id' => $i,
			    );
			    $question_list = $this->teacher_b->class_question($param);
			    $list[$i] = $question_list;
			}
			self::json_output(array('status'=>'ok','msg'=>'获取答题统计成功','data'=>$list));
		}else{
			self::json_output(array('status'=>'error','msg'=>'没有出过一批题的记录'));
		}
	}
	
	/**
	 * 【老师端】显示被赞的数量/显示被点击讲快点数量/显示被点击讲慢点数量
	 * 　array(
	 *　		'please_total_count' => 11,
	 *　		'slower_total_count' => 12,
	 *　		'faster_total_count' => 13,
	 *　);
	 */
	public function count_classroom_student_action(){
		$classroom_id= $this->input->get('classroom_id');
		$class_id= $this->input->get('class_id');
		$param = array(
			'class_id' => $class_id,
			'classroom_id' => $classroom_id,
		);
		$result = $this->teacher_b->class_student_action($param);
		echo json_encode($result);
		exit;
	}
}