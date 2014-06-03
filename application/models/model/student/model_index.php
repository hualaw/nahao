<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * studnet相关逻辑
 * Class Model_Student
 * @author liubing@tizi.com
 */
class Model_Index extends NH_Model{
    
    function __construct(){
        parent::__construct();
    }

    /**
     * 首页获取一门课程里面最新的一轮（在审核通过和销售中）
     * @return $array_result
     * @author liubing@tizi.com
     */
    public function get_course_latest_round()
    {
        $array_result = array();
        $sql = "SELECT course_id,MIN(start_time) AS start_time FROM round
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
     * 一轮里面有机次课
     * @param $int_round_id
     * @return int $int_result
     */
    public function round_has_class_nums($int_round_id)
    {
        $sql = "SELECT count(id) AS num FROM class WHERE 
                AND round_id = ".$int_round_id." AND parent_id !=0";
        $arr_row = $this->db->query($sql)->row_array();
        return $int_result = empty($arr_row['num']) ? 0 : $arr_row['num'];
    }
}