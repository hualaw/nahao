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
     * @return array
     * @author liubing@tizi.com
     */
    public function get_course_latest_round()
    {
        $arr_result = array();
        $sql = "SELECT course_id,MIN(start_time) AS start_time FROM round
                WHERE sale_status >= 2 AND sale_status <= 3 GROUP BY course_id";
        $arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
    }
    
    /**
     * 首页获取轮的信息列表
     * @param array $arr_round
     * @return array
     * @author liubing@tizi.com
     */
    public function get_round_list($arr_round)
    {
        $arr_result = array();
        foreach ($arr_round as $k=>$v)
        {
            $arr_result[] = $this->get_one_round_info($v['course_id'],$v['start_time']);
        }

        return $arr_result;
    }
    
    /**
     * 首页获取一个轮的详细信息
     * @param int $course_id
     * @param int $start_time
     * @return array
     * @author liubing@tizi.com
     */
    public function get_one_round_info($course_id,$start_time)
    {
        $arr_return = array();
        $sql = "SELECT id,course_id,title,subtitle,students,bought_count,start_time,end_time,img FROM round
                WHERE course_id = ".$course_id." AND start_time = ".$start_time;
        $arr_return = $this->db->query($sql)->row_array();
        #获取每一轮里面有几次课
        if ($arr_return['id'])
        {
            $msql = "SELECT count(id) AS num FROM class WHERE course_id = ".$arr_return['course_id']." 
                     AND round_id = ".$arr_return['id']." AND parent_id !=0";
            $arr_row = $this->db->query($msql)->row_array();
            $arr_return['class_nums'] = empty($arr_row['num']) ? 0 : $arr_row['num'];
        }
        return $arr_return;
    }
}