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
    }