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
        echo $return ? 1 : 0;
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
            $bool_flag = true;//TODO
            if($bool_flag){
                $arr_param = array(
                    'student_id' => $int_student_id,
                    'classroom_id' => $int_classroom_id
                );
                $this->load->model('business/student/student_classroom','classroom');
                $arr_return = $this->classroom->get_class_note($arr_param);
            }
        }
        self::json_output($arr_return);
    }


    public function save_stu_action()
    {
        header("Content-type: text/html; charset=utf-8");
        $classroom_id = intval(trim($this->input->get("class_id")));
        $user_id = intval(trim($this->input->get("user_id")));
        $user_type = 0; //only student call this interface
        $action_type = intval(trim($this->input->get("type")));

        $info = array(
            'classroom_id' => $classroom_id,
            'user_id' => $user_id,
            'user_type'=> $user_type,
            'action_type' => $action_type,
        );
        if($classroom_id <= 0 OR $user_id <= 0 OR $action_type <= 0)
        {
            log_message('error_nahao', "save student class action failed,".print_r($info,1));
            die(ERROR);
        }

        $this->load->model('model/student/model_class_action_log', 'cal_obj');
        $this->cal_obj->save_action($classroom_id, $user_id, $user_type, $action_type);
        log_message('info_nahao', "save student class action", $info);
        die(SUCCESS);
    }

    public function get_action_stat()
    {
        header("Content-type: text/html; charset=utf-8");
        $classroom_id = intval(trim($this->input->get("class_id")));
        if($classroom_id <= 0)
        {
            log_message('error_nahao', "get class action stat failed", array('classroom_id'=>$classroom_id));
        }
        $this->load->model('model/student/model_class_action_log', 'cal_obj');
        $result = $this->cal_obj->get_action_stat($classroom_id);

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
     * 用户进出教室调用接口
     * @author shangshikai@tizi.com
     */
    public function classroom_status()
    {
        $classroom_status=$this->input->post(NULL,TRUE);
        $classroom_status['time']=time();
        log_message('info_nahao','The original request:'.print_r($classroom_status,1));
        $this->load->model('business/api/business_class_status');
        return $this->business_class_status->status_classroom($classroom_status);
    }
    /**
     * 用户上课下课调用接口
     * @author shangshikai@tizi.com
     */
    public function class_status()
    {
        $class_status=$this->input->post(NULL,TRUE);
        $class_status['time']=time();
        log_message('info_nahao','The original request:'.print_r($class_status,1));
        $this->load->model('business/api/business_class_status');
        return $this->business_class_status->status_class($class_status);
    }
    
    /**
     * http://api.nahao.com/index/verify_enter_flash/?sid=xxx&uid=xxxx&crid=xxx
     * 验证sesson_id中的uid以及验证uid和crid的对应
     */
    public function verify_enter_flash(){
    	$session_id = $this->input->get('sid');
    	$classroom_id = $this->input->get('crid');
    	$user_id = $this->input->get('uid');
    	$user_type = $this->input->get('utype');
    	$user_type = !empty($user_type) ? $user_type : 0;
    	#新增1：如果有映射，将crid还原到映射前
    	$map = config_item('round_class_map');
    	$branch_classroom_id = '';//分轮教室id
    	if(in_array($classroom_id,$map))
    	{
    		$branch_classroom_id = $classroom_id;
    		$classroom_id = array_search($classroom_id,$map); 
    	}
    	#新增2：如果是试讲轮的课，验证通过
    	$this->load->model('model/teacher/model_info');
    	$round_info = $this->model_info->get_round_info_by_classroom_id(array('classroom_id'=>$classroom_id));
    	$round_info['is_test'] = !empty($round_info['is_test']) ? $round_info['is_test'] : 0;
    	if($round_info['is_test'] == ROUND_USE_TYPE_APPLYTEACH)
    	{
    		$res = array('status' => 'ok','msg' => '试讲轮直接通过验证');
    	}else{
	    	if($user_type!=NH_MEETING_TYPE_ADMIN){
	    		#不是管理员
		    	$data = array(
					'session_id' => !empty($session_id) ? $session_id : '',
					'user_id' => !empty($user_id) ? $user_id : '',
					'classroom_id' => !empty($classroom_id) ? $classroom_id : '',
					);
		    	if(empty($session_id) || empty($user_id) || empty($classroom_id)){
		    		$res = array('status' => 'error','msg' => '请求参数不能有遗漏');
		    	}else{
			    	$arr_data  = $this->session->all_userdata();
			    	if(!empty($arr_data)){
			    		if($user_id != $arr_data['user_id']){
			    			$res = array('status' => 'error','msg' => 'sessioid与用户id不匹配');
			    		}else{
				    		$this->load->model('model/student/model_classroom');
				    		$this->load->model('model/student/model_course');
				    		#根据classroom_id获取课id
				    		$array_class = $this->model_classroom->get_class_id_by_classroom_id($classroom_id);
				    		#判断这节课是不是在"可进教室 或者 正在上课"的状态 
							if ($array_class['status'] != CLASS_STATUS_ENTER_ROOM && $array_class['status'] != CLASS_STATUS_CLASSING )
							{
								$res = array('status' => 'error','msg' => '您不能进入教室了，课的状态不是“正在上课或者可进教室”');
							}else{
					    		if($user_type == NH_MEETING_TYPE_STUDENT){#学生
					    			#如果是学生判断用户是否买了这一堂课
					    			if($branch_classroom_id){#分轮同时查询分轮教室和总轮教室2种
							    		$array_branch_class = $this->model_classroom->get_class_id_by_classroom_id($branch_classroom_id);
						    			$bool_flag_branch = $this->model_course->check_user_buy_class($user_id,$array_branch_class['id']);
						    			$bool_flag_base = $this->model_course->check_user_buy_class($user_id,$array_class['id']);
						    			if(!$bool_flag_branch && !$bool_flag_base)
							    		{
							    			$res = array('status' => 'error','msg' => '学生没买过这堂分轮课');
							    		}else{
							    			$res = array('status' => 'ok','msg' => '学生session以及用户与分论课信息验证通过');
							    		}
						    		}else{#不是分轮的教室，查询一种
						    			$bool_flag = $this->model_course->check_user_buy_class($user_id,$array_class['id']);
						    			if(!$bool_flag)
							    		{
							    			$res = array('status' => 'error','msg' => '学生没买过这堂课');
							    		}else{
							    			$res = array('status' => 'ok','msg' => '学生session以及用户与课信息验证通过');
							    		}
						    		}
					    		}elseif($user_type == NH_MEETING_TYPE_TEACHER){#老师
					    			#如果是老师判断是否是这节课的老师
					    			$this->load->model('business/student/student_course');
						    		$bool_flag = $this->student_course->check_is_teacher_in_class($user_id,$array_class['id']);
						    		if(!$bool_flag)
						    		{
						    			$res = array('status' => 'error','msg' => '您不是这节课的老师');
						    		}else{
						    			$res = array('status' => 'ok','msg' => '老师session以及用户与课信息验证通过');
						    		}
					    		}
							}
			    		}
			    	}else{
			    		$res = array('status' => 'error','msg' => '没有用户登陆的session记录');
			    	}
		    	}
	    	}else{
	    		#是管理员
	    		$data = array(
					'session_id' => $session_id,
					'user_id' => !empty($user_id) ? $user_id : '',
					);
				if(empty($user_id) || empty($session_id)){#参数不全
		    		$res = array('status' => 'error','msg' => '管理员id或管理员session不能为空');
		    	}else{#参数齐全
		    		$user_info = $this->session->sess_admin_read();
		    		if(!$user_info){
		    			$res = array('status' => 'error','msg' => '管理员登陆信息为空');
		    		}else{
		    			if(($user_info['user_type']==NH_MEETING_TYPE_ADMIN) && ($user_info['user_id']==$user_id) && ($user_info['session_id']==$session_id)){
		    				$res = array('status' => 'ok','msg' => '管理员身份验证通过');
		    			}else{
		    				$res = array('status' => 'error','msg' => '管理员身份验证失败');
		    			}
		    		}
		    	}
	    	}
    	}
    	echo json_encode($res);
		exit;
    }
}
