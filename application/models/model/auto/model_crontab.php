<?php
    class Model_crontab extends NH_Model
    {
		
    	/**
    	 * 获取课id
     	 * type=1 where条件为begin_time
     	 * type=2 where条件为end_time
    	 * @param  $int_time
    	 * @param  $type
    	 * @return $array_result
    	 */
    	public function get_class_data($int_time,$type)
    	{
    		$array_result = array();
    		$where = '';
    		switch ($type)
    		{
    			case 1:$where.= ' AND C.begin_time = '.$int_time;break;
    			case 2:$where.= ' AND C.end_time = '.$int_time;break;
    		}
    		$sql = "SELECT C.id,C.classroom_id,C.begin_time,C.round_id FROM ".TABLE_CLASS." C
    				LEFT JOIN ".TABLE_ROUND." R ON C.round_id = R.id
    				WHERE  R.teach_status = ".ROUND_TEACH_STATUS_TEACH.$where;
    		$array_result = $this->db->query($sql)->result_array();
    		return $array_result;
    	}
    	
    	/**
    	 * 更新课的状态
    	 * @param  $array_update
    	 * @param  $array_where
    	 * @return boolean
    	 */
    	public function update_class_status($array_update,$array_where)
    	{
    		$this->db->update(TABLE_CLASS,$array_update,$array_where);
    		$int_row = $this->db->affected_rows();
    		return $int_row > 0 ? true : false;
    	}
    	
    	
    	public function get_section_data($int_round_id)
    	{
    		$array_result = array();
    		$sql= "SELECT lt.id FROM ".TABLE_CLASS." lo JOIN ".TABLE_CLASS." lt ON lo.round_id=lt.round_id 
    			   WHERE lo.round_id=".$int_round_id." 
    			   AND lt.parent_id=lo.id order by lo.sequence,lt.sequence";
    		$array_result = $this->db->query($sql)->result_array();
    		return $array_result;
    	} 
    	
    	/**
    	 * 寻找每一节课老师按开始上课按钮的时间
    	 * @param  $int_classroom_id
    	 */
    	public function get_time_by_teacher_press_submit($int_classroom_id)
    	{
    		$array_result = array();
    		$sql= "SELECT create_time FROM ".TABLE_CLASS_ACTION_LOG." WHERE classroom_id = ".$int_classroom_id." ORDER BY create_time ASC LIMIT 1";
    		$array_result = $this->db->query($sql)->row_array();
    		return $array_result;
    	}
    	
    	/**
    	 * 查找学生在entering_classroom里面是否有进入记录
    	 * @param  $int_user_id
    	 * @param  $int_classroom_id
    	 * @return boolean
    	 */
    	public function check_entering_classroom_data($int_user_id,$int_classroom_id)
    	{
    		$array_result = array();
    		$sql = "SELECT id FROM ".TABLE_ENTERING_CLASSROOM." WHERE classroom_id = ".$int_classroom_id." 
    				AND user_id = ".$int_user_id." AND user_type = ".NH_MEETING_TYPE_STUDENT;
    		$int_result = $this->db->query($sql)->num_rows();
    		return $int_result > 0 ? true :false;
    	}
    	
    	/**
    	 * 根据课id在student_class表里面找到买过这节课的学生
    	 * @param  $int_class_id
    	 * @return $array_result
    	 */
    	public function get_buy_class($int_class_id)
    	{
    		$array_result = array();
    		$sql = "SELECT student_id,class_id FROM ".TABLE_STUDENT_CLASS." WHERE class_id = ".$int_class_id." 
    				AND (status = ".STUDENT_CLASS_REFUND_FAIL." OR status = ".STUDENT_CLASS_INIT.")";
    		$array_result = $this->db->query($sql)->result_array();
    		return $array_result;
    	}


        /**
         *轮的销售状态改为开售
         * @author shangshikai@tizi.com
         */
        public function status_begin_round($round_id)
        {
            $this->db->update(TABLE_ROUND,array(TABLE_ROUND.'.sale_status'=>3),array(TABLE_ROUND.'.id'=>$round_id));
            return $this->db->affected_rows();
        }
        /**
         *轮的销售状态改为停售
         * @author shangshikai@tizi.com
         */
        public function status_end_round($round_id)
        {
            $this->db->update(TABLE_ROUND,array(TABLE_ROUND.'.sale_status'=>5),array(TABLE_ROUND.'.id'=>$round_id));
            return $this->db->affected_rows();
        }
        /**
         *轮的销授课状态改为授课中
         * @author shangshikai@tizi.com
         */
        public function status_start_round($round_id)
        {
            $this->db->update(TABLE_ROUND,array(TABLE_ROUND.'.teach_status'=>2),array(TABLE_ROUND.'.id'=>$round_id));
            return $this->db->affected_rows();
        }
        /**
         *轮的授课状态改为结课
         * @author shangshikai@tizi.com
         */
        public function end_round($round_id)
        {
            $this->db->update(TABLE_ROUND,array(TABLE_ROUND.'.teach_status'=>4),array(TABLE_ROUND.'.id'=>$round_id));
            return $this->db->affected_rows();
        }
        /**
         *轮的授课状态改为过期
         * @author shangshikai@tizi.com
         */
        public function expire_round($round_id)
        {
            $this->db->update(TABLE_ROUND,array(TABLE_ROUND.'.teach_status'=>5),array(TABLE_ROUND.'.id'=>$round_id));
            return $this->db->affected_rows();
        }
        /**
         * 所有需要改为过期的轮
         * @author shangshikai@tizi.com
         */
        public function all_expire_end($ex_time)
        {
            $this->db->select(TABLE_ROUND.'.id')->from(TABLE_ROUND);
            $this->db->where(TABLE_ROUND.'.teach_status',4);
            $this->db->where(TABLE_ROUND.'.end_time<=',$ex_time);
            return $this->db->get()->result_array();
        }
        /**
         * 所有需要改为过期的轮(补救)
         * @author shangshikai@tizi.com
         */
        public function remedy_all_expire_end($begin_ex_time,$end_ex_time)
        {
            $this->db->select(TABLE_ROUND.'.id')->from(TABLE_ROUND);
            $this->db->where(TABLE_ROUND.'.teach_status',4);
            $this->db->where(TABLE_ROUND.'.end_time>=',$begin_ex_time);
            $this->db->where(TABLE_ROUND.'.end_time<',$end_ex_time);
            return $this->db->get()->result_array();
        }
        /**
         *所有审核通过的轮
         * @author shangshikai@tizi.com
         */
        public function all_round_status($advance_time)
        {
//            echo $advance_time;
            $this->db->select(TABLE_ROUND.'.id')->from(TABLE_ROUND);
            $this->db->where(TABLE_ROUND.'.sale_status',2);
            $this->db->where(TABLE_ROUND.'.sell_begin_time',$advance_time);
            return $this->db->get()->result_array();
        }
        /**
         *所有审核通过的轮(补救)
         * @author shangshikai@tizi.com
         */
        public function remedy_all_round_status($begin_time,$end_time)
        {
            $this->db->select(TABLE_ROUND.'.id')->from(TABLE_ROUND);
            $this->db->where(TABLE_ROUND.'.sale_status',2);
            $this->db->where(TABLE_ROUND.'.sell_begin_time>=',$begin_time);
            $this->db->where(TABLE_ROUND.'.sell_begin_time<',$end_time);
            return $this->db->get()->result_array();
            //return $this->db->last_query();
        }
        /**
         *所有需要改为授课中的轮
         * @author shangshikai@tizi.com
         */
        public function all_round_teach($advance_time)
        {
            $sql="SELECT round.id FROM round WHERE round.teach_status=1 AND (round.sale_status=4 OR round.sale_status=5) AND round.start_time=$advance_time";
            return $this->db->query($sql)->result_array();
        }
        /**
         *所有需要改为授课中的轮(补救)
         * @author shangshikai@tizi.com
         */
        public function remedy_all_round_teach($begin_time,$end_time)
        {
            $this->db->select(TABLE_ROUND.'.id')->from(TABLE_ROUND);
            $this->db->where(TABLE_ROUND.'.teach_status',1);
            $this->db->where(TABLE_ROUND.'.sale_status',4);
            $this->db->or_where(TABLE_ROUND.'.sale_status',5);
            $this->db->where(TABLE_ROUND.'.start_time>=',$begin_time);
            $this->db->where(TABLE_ROUND.'.start_time<',$end_time);
            return $this->db->get()->result_array();
        }
        /**
         *所有需要结课的轮
         * @author shangshikai@tizi.com
         */
        public function all_round_teach_end($advance_time)
        {
            $this->db->select(TABLE_ROUND.'.id')->from(TABLE_ROUND);
            $this->db->where(TABLE_ROUND.'.teach_status',2);
            $this->db->where(TABLE_ROUND.'.end_time',$advance_time);
            return $this->db->get()->result_array();
        }
        /**
         *所有需要结课的轮（补救）
         * @author shangshikai@tizi.com
         */
        public function remedy_all_round_teach_end($begin_time,$end_time)
        {
            $this->db->select(TABLE_ROUND.'.id')->from(TABLE_ROUND);
            $this->db->where(TABLE_ROUND.'.teach_status',2);
            $this->db->where(TABLE_ROUND.'.end_time>=',$begin_time);
            $this->db->where(TABLE_ROUND.'.end_time<',$end_time);
            return $this->db->get()->result_array();
        }
        /**
         * 所有需要改为停售的轮
         * @author shangshikai@tizi.com
         */
        public function all_round_status_end($advance_time)
        {
//            echo $advance_time;die;
            $this->db->select(TABLE_ROUND.'.id')->from(TABLE_ROUND);
            $this->db->where(TABLE_ROUND.'.sale_status',3);
            $this->db->where(TABLE_ROUND.'.sell_end_time',$advance_time);
            return $this->db->get()->result_array();
        }
        /**
         * 所有需要改为停售的轮(补救)
         * @author shangshikai@tizi.com
         */
        public function remedy_all_round_status_end($begin_time,$end_time)
        {
            $this->db->select(TABLE_ROUND.'.id')->from(TABLE_ROUND);
            $this->db->where(TABLE_ROUND.'.sale_status',3);
            $this->db->where(TABLE_ROUND.'.sell_end_time>=',$begin_time);
            $this->db->where(TABLE_ROUND.'.sell_end_time<',$end_time);
            return $this->db->get()->result_array();
        }
    	
    	/**
    	 * 更新student_class表里面的状态
    	 * @param  $array_update
    	 * @param  $array_where
    	 * @return boolean
    	 */
    	public function update_student_class_status($array_update,$array_where)
    	{
    		$this->db->update(TABLE_STUDENT_CLASS,$array_update,$array_where);
    		$int_row = $this->db->affected_rows();
    		return $int_row > 0 ? true : false;
    	}
    	
    	/**
    	 * 获取每个课的出席人数
    	 * @param  $int_classroom_id
    	 * @return $array_result
    	 */
    	public function get_class_count_attendance($int_classroom_id)
    	{
    		$array_result = array();
    		$sql = "SELECT count(DISTINCT user_id) AS num FROM ".TABLE_ENTERING_CLASSROOM." WHERE classroom_id = ".$int_classroom_id;
    		$array_result = $this->db->query($sql)->row_array();
    		return $array_result;
    		
    	}
    	
    	/**
    	 * 更新每个课的出席人数
    	 * @param  $array_update
    	 * @param  $array_where
    	 * @return boolean
    	 */
    	public function update_class_attendance($array_update,$array_where)
    	{
    		$this->db->update(TABLE_CLASS,$array_update,$array_where);
    		$int_row = $this->db->affected_rows();
    		return $int_row > 0 ? true : false;
    	}
    	
    	/**
    	 * 获取一个课上做题的总数
    	 * @param  $int_class_id
    	 * @return $array_result
    	 */
    	public function get_student_question_totle($int_class_id)
    	{
    		$array_result = array();
    		$sql = "SELECT count(id) AS num FROM ".TABLE_STUDENT_QUESTION." WHERE class_id = ".$int_class_id;
    		$array_result = $this->db->query($sql)->row_array();
    		return $array_result;
    	}
    	
    	/**
    	 * 获取一个课上做题的总数
    	 * @param  $int_class_id
    	 * @return $array_result
    	 */
    	public function get_student_question_correct_totle($int_class_id)
    	{
    		$array_result = array();
    		$sql = "SELECT count(id) AS num FROM ".TABLE_STUDENT_QUESTION." WHERE class_id = ".$int_class_id." AND is_correct = 1";
    		$array_result = $this->db->query($sql)->row_array();
    		return $array_result;
    	}
    	
    	/**
    	 * 获取每个课上做题的正确率
    	 * @param  $array_update
    	 * @param  $array_where
    	 * @return boolean
    	 */
    	public function update_class_correct_rate($array_update,$array_where)
    	{
    		$this->db->update(TABLE_CLASS,$array_update,$array_where);
    		$int_row = $this->db->affected_rows();
    		return $int_row > 0 ? true : false;
    	}
    }
