<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_Index extends NH_Model{
    
    function __construct(){
        parent::__construct();
        $this->db->query("set names utf8");
    }

    /**
     * 首页获取一门课程里面最新的一轮（在审核通过和销售中）
     * @return $array_result
     * @author liubing@tizi.com
     */
    public function get_course_latest_round()
    {
        $array_result = array();
        $sql = "SELECT id,MIN(start_time) AS start_time FROM round
                WHERE sale_status >= 2 AND sale_status <= 3 GROUP BY course_id ORDER BY start_time ASC";
        $array_result = $this->db->query($sql)->result_array();
        return $array_result;
    }
    
    /**
     * 首页获取一个轮的详细信息
     * @param int $course_id
     * @param int $start_time
     * @return array $array_result
     * @author liubing@tizi.com
     */
    public function get_one_round_info($course_id,$start_time)
    {
        $array_result = array();
        $sql = "SELECT id,course_id,title,subtitle,students,bought_count,
                start_time,end_time,img,grade_to,grade_from FROM round
                WHERE course_id = ".$course_id." AND start_time = ".$start_time;
        $array_result = $this->db->query($sql)->row_array();
        return $array_result;
    }
    
    /**
     * 一轮里面有几次课
     * @param $int_round_id
     * @return int $int_result
     */
    public function round_has_class_nums($int_round_id)
    {
        $sql = "SELECT count(id) AS num FROM class WHERE 
                round_id = ".$int_round_id." AND parent_id !=0";
        //echo $sql;die;
        $arr_row = $this->db->query($sql)->row_array();
        return $int_result = empty($arr_row['num']) ? 0 : $arr_row['num'];
    }
    
    /**
     * 保存我要开课申请
     * @param  $array_data
     * @return bool
	 */
	public function save_apply_teach($array_data)
	{
		//没有学校id，先插学校返回学校id
		if(!$array_data['school'] && $array_data['schoolname']){
			$param = array(
				'county_id' => $array_data['area'],
				'schoolname' => $array_data['schoolname'],
				'province_id' => $array_data['province'],
				'city_id' => $array_data['city'],
				'status' => 0,
				'sctype' => $array_data['school_type'],
			);
			$this->db->insert('nahao_schools_create', $param);
			$array_data['school'] = $this->db->insert_id();
		}
		unset($array_data['schoolname']);
		unset($array_data['school_type']);
	    $this->db->insert('teacher_lecture', $array_data);
	    $int_row = $this->db->affected_rows();
	    return $int_row > 0 ? true : false;
	}
	
	/**
	 * 意见反馈
	 * @param  $array_data
	 * @return boolean
	 */
	public function save_feedback($array_data)
	{
	    $this->db->insert('feedback', $array_data);
	    $int_row = $this->db->affected_rows();
	    return $int_row > 0 ? true : false;
	}
}
