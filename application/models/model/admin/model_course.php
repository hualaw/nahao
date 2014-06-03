<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Course相关逻辑
 * Class Model_Course
 * @author yanrui@tizi.com
 */
class Model_Course extends NH_Model
{

    /**
     * 创建course
     * @param array $arr_param
     * @return int
     * @author yanrui@tizi.com
     */
    public function create_course($arr_param)
    {
        $arr_result = $this->db->insert(TABLE_COURSE, $arr_param);
        $int_insert_id = $this->db->insert_id();
        return $int_insert_id;
    }

    /**
     * 修改course
     * @param array $arr_param
     * @param array $arr_where
     * @return bool
     * @author yanrui@tizi.com
     */
    public function update_course($arr_param,$arr_where){
        $this->db->update(TABLE_COURSE, $arr_param, $arr_where);
        $int_affected_rows = $this->db->affected_rows();
//        o($int_affected_rows);
        return $int_affected_rows > 0 ? true :false;
    }

    /**
     * 根据参数获取course
     * @param string $str_table_range
     * @param string $str_result_type
     * @param string $str_field
     * @param array $arr_where
     * @param array $arr_group_by
     * @param array $arr_order_by
     * @param array $arr_limit
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_course_by_param($str_table_range, $str_result_type = 'list', $str_field = '*', $arr_where = array(), $arr_group_by = array(), $arr_order_by = array(), $arr_limit = array())
    {
        $mix_return = self::_get_course($str_table_range, $str_result_type, $str_field, $arr_where, $arr_group_by, $arr_order_by, $arr_limit);
        return $mix_return;
    }

    /**
     * 全功能组查询方法 可配置查询条件、字段、完整度 被本类中其他函数调用
     * @param string $str_table_range
     * @param string $str_result_type
     * @param string $str_field
     * @param array $arr_where
     * @param array $arr_group_by
     * @param array $arr_order_by
     * @param array $arr_limit
     * @return mix $mix_return
     * @author yanrui@tizi.com
     */
    protected function _get_course($str_table_range = 'course', $str_result_type = 'list', $str_field = '*', $arr_where = array(), $arr_group_by = array(), $arr_order_by = array(), $arr_limit = array())
    {
        $mix_return = self::_get_from_db($str_table_range, $str_result_type, $str_field, $arr_where, $arr_group_by, $arr_order_by, $arr_limit);
//        var_dump($mix_return);exit;
        return $mix_return;
    }
}