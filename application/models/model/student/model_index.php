<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Model_Index extends NH_Model
{

    function __construct()
    {
        parent::__construct();
        $this->db->query("set names utf8");
    }

    /**
     * 首页获取一门课程里面最新的一轮（在销售中）
     * @return $array_result
     * @author liubing@tizi.com
     */
    public function get_course_latest_round()
    {
        $array_result = array();
        $sql = "SELECT id,MIN(start_time) AS start_time FROM " . TABLE_ROUND . " WHERE sale_status = " . ROUND_SALE_STATUS_SALE . " GROUP BY course_id ORDER BY start_time ASC";
        $array_result = $this->db->query($sql)->result_array();
        return $array_result;
    }

    public function get_course_new_round($offset = 0,$per_page = 10)
    {
        $array_result = array();
        $sql = "SELECT id,start_time FROM " . TABLE_ROUND . " WHERE sale_status = " . ROUND_SALE_STATUS_SALE . " AND is_test = 0 GROUP BY course_id ORDER BY start_time ASC limit $offset,$per_page";

        $array_result = $this->db->query($sql)->result_array();
        return $array_result;
    }

    /**
     * 首页获取销售最多的那轮（在销售中）
     * @return $array_result
     * @author zhangshuaiqi@tizi.com
     */
    public function get_course_hot_round($offset = 0,$per_page = 10)
    {
        $array_result = array();
        $sql = "SELECT  id,start_time,(bought_count+extra_bought_count) as number FROM ".TABLE_ROUND."
                WHERE sale_status =".ROUND_SALE_STATUS_SALE ." AND is_test = 0 ORDER BY number DESC limit $offset,$per_page";
        $array_result = $this->db->query($sql)->result_array();
//        print_r($array_result);
//        exit;
        return $array_result;
    }

    /**
     * 一轮里面有几次课
     * @param $int_round_id
     * @return int $int_result
     */
    public function round_has_class_nums($int_round_id)
    {
        $sql = "SELECT count(id) AS num FROM " . TABLE_CLASS . " WHERE round_id = " . $int_round_id . " AND parent_id >0";
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
        if (!$array_data['school'] && $array_data['schoolname']) {
            $param = array(
                'county_id' => $array_data['area'],
                'schoolname' => $array_data['schoolname'],
                'province_id' => $array_data['province'],
                'city_id' => $array_data['city'],
                'status' => 0,
                'sctype' => $array_data['school_type'],
            );
            $this->db->insert(TABLE_SCHOOLS_CREATE, $param);
            $array_data['school'] = $this->db->insert_id();
        }
        unset($array_data['schoolname']);
        unset($array_data['school_type']);
        $this->db->insert(TABLE_TEACHER_LECTURE, $array_data);
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
        $this->db->insert(TABLE_FEEDBACK, $array_data);
        $int_row = $this->db->affected_rows();
        return $int_row > 0 ? true : false;
    }
}
