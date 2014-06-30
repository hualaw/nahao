<?php
    class Business_crontab extends NH_Model
    {

        public function __construct()
        {
            parent::__construct();
            $this->load->model('model/auto/model_crontab');
        }

        /**
         * 获取课的id
     	 * type=1 where条件为begin_time
     	 * type=2 where条件为end_time
         * @param  $int_time
         * @param  $type
         * @return $array_return
         */
        public function get_class_data($int_time,$type)
        {
        	$array_return = array();
        	$array_return = $this->model_crontab->get_class_data($int_time,$type);
        	return $array_return;
        }
        
        /**
         * 更新课的状态
         * @param  $array_update
         * @param  $array_where
         * @return $bool_return
         */
        public function update_class_status($array_update,$array_where)
        {
        	$bool_return = $this->model_crontab->update_class_status($array_update,$array_where);
        	return $bool_return;
        }
        
        /**
         * 一节课上完了，寻找下节课
         * @param  $int_class_id
         * @param  $int_round_id
         * @return $next_class_id
         */
        public function get_next_class_data($int_class_id,$int_round_id)
        {
        	$array_result = array();
        	#获取该节课对应轮下的所有章数组
        	$array_charpter = $this->model_course->get_all_chapter($int_round_id);
        	#有章
        	if($array_charpter)
        	{
        		#获取下面的节
        		$array_section = $this->model_crontab->get_section_data($int_round_id);
        	} else {
        		#没有章
        		$array_section = $this->model_course->get_all_section($int_round_id);
        	}
			$array_class_id = array();
        	if ($array_section)
        	{
        		foreach ($array_section as $kk=>$vv)
        		{
        			$array_class_id[] = $vv['id'];
        		}
        		$array_key = array_search($int_class_id,$array_class_id);
        		if(isset($array_class_id[$array_key+1]))
        		{
        			$next_class_id = $array_class_id[$array_key+1];
        		} else {
        			$next_class_id = '';
        		}
        		return $next_class_id;
        	}
        }
        
        /**
         * 寻找每一节课老师按开始上课按钮的时间
         * @param  $int_classroom_id
         */
        public function get_time_by_teacher_press_submit($int_classroom_id)
        {
        	$array_return = array();
        	$array_return = $this->model_crontab->get_time_by_teacher_press_submit($int_classroom_id);
        	return $array_return;
        }

        /**
         * 查找学生在entering_classroom里面是否有进入记录
         * @param  $int_user_id
         * @param  $int_classroom_id
         * @return $bool_return
         */
        public function check_entering_classroom_data($int_user_id,$int_classroom_id)
        {
        	$bool_return = $this->model_crontab->check_entering_classroom_data($int_user_id,$int_classroom_id);
        	return $bool_return;
        }
        
        /**
         * 根据课id在student_class表里面找到买过这节课的学生
         * @param  $int_class_id
         */
        public function get_buy_class($int_class_id)
        {
        	$array_return = array();
        	$array_return = $this->model_crontab->get_buy_class($int_class_id);
        	return $array_return;
        }
        
        /**
         * 更新student_class表里面的状态
         * @param  $array_update
         * @param  $array_where
         * @return boolean
         */
        public function update_student_class_status($array_update,$array_where)
        {
        	$bool_return = $this->model_crontab->update_student_class_status($array_update,$array_where);
        	return $bool_return;
        }
        
        /**
         * 获取每个课的出席人数
         * @param  $int_classroom_id
         * @return $array_return
         */
        public function get_class_count_attendance($int_classroom_id)
        {
        	$array_return = array();
        	$array_return = $this->model_crontab->get_class_count_attendance($int_classroom_id);
        	return $array_return;
        }
        
        /**
         * 获取每个课上做题的正确率
         * @param  $int_class_id
         * @return $array_return
         */
        public function get_class_count_correctrate($int_class_id)
        {
        	$array_totle = $this->model_crontab->get_student_question_totle($int_class_id);
        	$totle = empty($array_totle) ? 0 : $array_totle['num'];
        	$array_correct_totle = $this->model_crontab->get_student_question_correct_totle($int_class_id);
        	$array_correct_totle = empty($array_correct_totle) ? 0 : $array_correct_totle['num'];
        	$correctrate = $totle == '0' ? 0 : round($array_correct_totle/$totle,2);
        	return $correctrate;
        }


    }