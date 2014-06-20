<?php

class Business_School extends NH_Model{
	
	/**
	 * 通过父节点获取所有孩子节点数据[area表]
	 * @param integer $parentid
	 * @param string $fields
	 * @return array
	 * @author liuhua
	 */
	public function id_children($parentid, $fields = "*"){
		$id_replace = array(2,25,27,32,33);
		$rel = array(
			2 => 52,
			25 => 321,
			27 => 343,
			32 => 394,
			33 => 395
		);
		if (in_array($parentid, $id_replace)){
			$parentid = $rel[$parentid];
		}
		$result = $this->db->query("select {$fields} from ".TABLE_NAHAO_AREAS." where
				parentid=?", array($parentid))->result_array();
		return $result;
	}


    /**
     * 根据县/区的ID获取学校列表
     * @param integer $county_id
     * @param string $fields
     * @return array
     * @author liuhua
     */
    public function county_schools($county_id, $sctype, $fields = "*"){
        //echo "select {$fields} from ".TABLE_NAHAO_SCHOOLS." where county_id=$county_id and sctype=$sctype";
        $r = $this->db->query("select {$fields} from ".TABLE_NAHAO_SCHOOLS." where county_id=? and sctype=?"
                                , array($county_id, $sctype))->result_array();
        return $r;
    }
    
    /**
     * 根据学校ID获取学校的信息
     * @param int      $school_id 学校Id
     * @param string   $fields    要查询的字段
     * @param bool     $custom_school 用户的学校是否是自定义的
     * return array
     * @author yanhj
     */
    public function school_info($school_id, $fields = '*', $custom_school = false)
    {
        $arr_return = array();
        if($school_id) {
            $table = $custom_school ? TABLE_SCHOOLS_CREATE : TABLE_NAHAO_SCHOOLS;
            $arr_return = $this->db->query("select {$fields} from " . $table . " where id=?", array($school_id))->row_array();
        }
        
        return $arr_return;
    }
    
    /**
     * 添加用户自填的学校信息
     * @param Array $insert_data 要插入的数据
     * @return $int_school_id 新增的学校Id
     */
    public function add_custom_school($insert_data)
    {
        $school_data = array();
        !empty($insert_data['province_id']) && $school_data['province_id'] = $insert_data['province_id'];
        !empty($insert_data['city_id']) && $school_data['city_id'] = $insert_data['city_id'];
        !empty($insert_data['county_id']) && $school_data['county_id'] = $insert_data['county_id'];
        !empty($insert_data['schoolname']) && $school_data['schoolname'] = $insert_data['schoolname'];
        !empty($insert_data['school_type']) && $school_data['sctype'] = $insert_data['school_type'];
        $this->db->insert(TABLE_SCHOOLS_CREATE, $school_data);

        return $this->db->insert_id();
    }
}