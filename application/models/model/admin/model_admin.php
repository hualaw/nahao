<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * admin相关逻辑
 * Class Model_Admin
 * @author yanrui@tizi.com
 */
class Model_Admin extends NH_Model
{

    /**
     * 创建admin
     * @param $arr_param
     * @return mixed
     * @author yanrui@tizi.com
     */
    public function create_admin($arr_param)
    {
        $arr_result = $this->db->insert('admin', $arr_param);
        $int_insert_id = $this->db->insert_id();
        return $int_insert_id;
    }

    /**
     * 根据参数获取admin
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
    public function get_admin_by_param($str_table_range, $str_result_type = 'list', $str_field = '*', $arr_where = array(), $arr_group_by = array(), $arr_order_by = array(), $arr_limit = array())
    {
        $mix_return = self::_get_admin($str_table_range, $str_result_type, $str_field, $arr_where, $arr_group_by, $arr_order_by, $arr_limit);
        return $mix_return;
    }

    /**
     * 全功能管理员查询方法 可配置查询条件、字段、完整度 被本类中其他函数调用
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
    protected function _get_admin($str_table_range = 'admin', $str_result_type = 'list', $str_field = '*', $arr_where = array(), $arr_group_by = array(), $arr_order_by = array(), $arr_limit = array())
    {
        $mix_return = self::_get_from_db($str_table_range, $str_result_type, $str_field, $arr_where, $arr_group_by, $arr_order_by, $arr_limit);
//        var_dump($mix_return);exit;
        return $mix_return;
    }
}