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
    			case 1:$where.= 'begin_time = '.$int_time;break;
    			case 2:$where.= 'end_time = '.$int_time;break;
    		}
    		$sql = "SELECT id,classroom_id,begin_time,round_id FROM ".TABLE_CLASS." WHERE ".$where;
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
    		$sql= "SELECT lt.id, FROM class lo JOIN class lt ON lo.round_id=lt.round_id WHERE lo.round_id=".$int_round_id." 
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
    		$sql= "SELECT create_time FROM class_action_log WHERE classroom_id = ".$int_classroom_id." ORDER BY create_time ASC LIMIT 1";
    		$array_result = $this->db->query($sql)->row_array();
    		return $array_result;
    	}
    	
    	/**
    	 * 根据课id找出上过这节课的学生
    	 * @param  $int_classroom_id
    	 */
    	public function get_enter_classroom_user_id($int_classroom_id)
    	{
    		$array_result = array();
    		$sql = "SELECT DISTINCT user_id FROM entering_classroom WHERE classroom_id = ".$int_classroom_id." 
    				AND user_type = ".NH_MEETING_TYPE_STUDENT;
    		$array_result = $this->db->query($sql)->result_array();
    		return $array_result;
    	}
    	
    	/**
    	 * 根据课id在student_class表里面找到买过这节课的学生
    	 * @param  $int_classroom_id
    	 */
    	public function get_buy_class_user($int_class_id)
    	{
    		$array_result = array();
    		$sql = "SELECT user_id FROM student_class WHERE class_id = ".$int_class_id." 
    				AND (status = STUDENT_CLASS_REFUND_FAIL OR status = STUDENT_CLASS_LOST)";
    		$array_result = $this->db->query($sql)->result_array();
    		return $array_result;
    	}
        /**
         *轮的销售状态改为开售
         * @author shangshikai@tizi.com
         */
        public function status_begin_round($round_id)
        {
            return $this->db->uodate(TABLE_ROUND,array(TABLE_ROUND.'.sale_status'=>3),array(TABLE_ROUND.'.id'=>$round_id));
        }
        /**
         *轮的销售状态改为停售
         * @author shangshikai@tizi.com
         */
        public function status_end_round($round_id)
        {
            return $this->db->uodate(TABLE_ROUND,array(TABLE_ROUND.'.sale_status'=>5),array(TABLE_ROUND.'.id'=>$round_id));
        }
        /**
         *轮的销授课状态改为授课中
         * @author shangshikai@tizi.com
         */
        public function status_start_round($round_id)
        {
            return $this->db->uodate(TABLE_ROUND,array(TABLE_ROUND.'.teach_status'=>2),array(TABLE_ROUND.'.id'=>$round_id));
        }
        /**
         *轮的授课状态改为结课
         * @author shangshikai@tizi.com
         */
        public function end_round($round_id)
        {
            return $this->db->uodate(TABLE_ROUND,array(TABLE_ROUND.'.teach_status'=>4),array(TABLE_ROUND.'.id'=>$round_id));
        }
        /**
         *轮的授课状态改为过期
         * @author shangshikai@tizi.com
         */
        public function expire_round($round_id)
        {
            return $this->db->uodate(TABLE_ROUND,array(TABLE_ROUND.'.teach_status'=>5),array(TABLE_ROUND.'.id'=>$round_id));
        }
        /**
         *所有审核通过的轮
         * @author shangshikai@tizi.com
         */
        public function all_round_status()
        {
            return $this->db->select(TABLE_ROUND.'.id,sell_begin_time,sell_end_time')->from(TABLE_ROUND)->where(TABLE_ROUND.'.sale_status',2)->get()->result_array();
        }
        /**
         *所有授课中的轮
         * @author shangshikai@tizi.com
         */
        public function all_round_teach()
        {
            return $this->db->select(TABLE_ROUND.'.id,start_time,end_time')->from(TABLE_ROUND)->where(TABLE_ROUND.'.teach_status',1)->get()->result_array();
        }
    }