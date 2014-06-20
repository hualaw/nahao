<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends NH_User_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('business/teacher/business_teacher','teacher_b');
        $this->load->model('model/teacher/model_teacher','teacher_m');
        header("Content-type: text/html; charset=utf-8");
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
			    	'classroom_id' => $classroom_id,
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


    /**
     * 课堂笔记API 课堂调用
     * @author yanrui@91waijiao.com
     */
    public function set_class_note(){
        $log_path = PATH_SEPARATOR==':' ? '/tmp/' : 'c:/wamp/logs/';
        header("Content-type: text/html; charset=utf-8");
        error_reporting(E_ALL);
        ini_set('display_errors', true);

        $int_classroom_id = intval($this->input->post('cid'));
        $int_student_id = intval($this->input->post('uid'));
        $str_content = trim($this->input->post('notes'));

//        $int_classroom_id = intval($this->input->get('cid'));
//        $int_student_id = intval($this->input->get('uid'));
//        $str_content = trim($this->input->get('notes'));

        if($int_classroom_id==0 AND $int_student_id==0 AND $str_content==''){
            die('param error');
        }
        $this->load->model('business/student/student_classroom','classroom');
        $this->load->model('model/student/model_course');
        $array_class_id = $this->model_classroom->get_class_id_by_classroom_id($int_classroom_id);
        if($array_class_id){
            $bool_flag = $this->model_course->check_user_buy_class($int_student_id,$array_class_id['id']);
            if($bool_flag){
//                $int_student_id = substr($int_student_id,0,strlen($int_student_id)-1);
                $str_content = urldecode(gzinflate((string)base64_decode($str_content)));
                $data = array(
                    'classroom_id' => $int_classroom_id,
                    'student_id' => $int_student_id,
//            'content' => $this->boolMagic ? $str_content_db : addslashes($str_content_db),
//                    'content' => mysql_real_escape_string($str_content),
                    'content' => $str_content,
                    'create_time' => TIME_STAMP,
                    'update_time' => TIME_STAMP
                );
                error_log(TIME_STAMP.'  '.date('Y-m-d H:i:s',TIME_STAMP).'  cid:'.$int_classroom_id.'    sid:'.$int_student_id.' content:'.$str_content."\n",3,$log_path.'class_note.log');
                $return = $this->classroom->save_class_note($data);
            }
        }
        /* $str_content = urldecode(gzinflate((string)base64_decode($str_content)));
//        $str_content_log = iconv('UTF-8','GBK',$str_content_db);*/



//        error_log('--'.$int_class_id.'--'.$int_student_id.'--'.$str_content."\n",3,'c:/wamp/logs/test.log');
//        var_dump($data);
//        error_log($this->db->last_query()."\n",3,$log_path.'class_note.log');
//        echo $return ? 1 : 0;
    }

    public function get_class_note(){
        header("Content-type: text/html; charset=utf-8");
        error_reporting(E_ALL);
        ini_set('display_errors', true);

        $int_classroom_id = intval($this->input->get('cid'));
        $int_student_id = intval($this->input->get('uid'));

//        $int_classroom_id = intval($this->input->get('cid'));
//        $int_student_id = intval($this->input->get('uid'));

        if($int_classroom_id==0 AND $int_student_id==0){
            die('param error');
        }
        $arr_return = array();
        $this->load->model('business/student/student_classroom','classroom');
        $this->load->model('model/student/model_course');
        $array_class_id = $this->model_classroom->get_class_id_by_classroom_id($int_classroom_id);
//        o($array_class_id);
        if($array_class_id){
            $bool_flag = $this->model_course->check_user_buy_class($int_student_id,$array_class_id['id']);
//            o($bool_flag);
            if($bool_flag){
                $arr_param = array(
                    'student_id' => $int_student_id,
                    'classroom_id' => $int_classroom_id
                );
                o($arr_param);
                $this->load->model('business/student/student_classroom','classroom');
                $arr_return = $this->classroom->get_class_note($arr_param);
            }
        }
        o($arr_return,true);
    }
}