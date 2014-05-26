<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * user相关逻辑
 * Class Model_User
 * @author yanrui@tizi.com
 */
class Model_User extends NH_Model
{

    /**
     * 创建user
     * @param array $arr_param
     * @return int
     * @author yanrui@tizi.com
     */
    public function create_user($arr_param)
    {
        $arr_result = $this->db->insert(TABLE_USER, $arr_param);
        $int_insert_id = $this->db->insert_id();
        return $int_insert_id;
    }

    /**
     * 创建user_info
     * @param array $arr_param
     * @return int
     * @author yanrui@tizi.com
     */
    public function create_user_info($arr_param)
    {
        $arr_result = $this->db->insert(TABLE_USER_INFO, $arr_param);
        $int_insert_id = $this->db->affected_rows();
        return $int_insert_id;
    }

    /**
     * 修改user
     * @param array $arr_param
     * @param array $arr_where
     * @return bool
     * @author yanrui@tizi.com
     */
    public function update_user($arr_param,$arr_where){
        $this->db->update(TABLE_USER, $arr_param, $arr_where);
        $int_affected_rows = $this->db->affected_rows();
        return $int_affected_rows > 0 ? true :false;
    }

    /**
     * 修改user_info
     * @param array $arr_param
     * @param array $arr_where
     * @return bool
     * @author yanrui@tizi.com
     */
    public function update_user_info($arr_param,$arr_where){
        $this->db->update(TABLE_USER_INFO, $arr_param, $arr_where);
        $int_affected_rows = $this->db->affected_rows();
        return $int_affected_rows > 0 ? true :false;
    }

    /**
     * 删除user
     * @param array $arr_param
     * @return bool
     * @author yanrui@tizi.com
     */
    public function delete_user($arr_param){
        $this->db->delete(TABLE_USER,$arr_param);
        $int_affected_rows = $this->db->affected_rows();
        return $int_affected_rows > 0 ? true :false;
    }

    /**
     * 删除user_info
     * @param array $arr_param
     * @return bool
     * @author yanrui@tizi.com
     */
    public function delete_user_info($arr_param){
        $this->db->delete(TABLE_USER_INFO,$arr_param);
        $int_affected_rows = $this->db->affected_rows();
        return $int_affected_rows > 0 ? true :false;
    }

    /**
     * 根据参数获取user&user_info
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
    public function get_user_by_param($str_table_range, $str_result_type = 'list', $str_field = '*', $arr_where = array(), $arr_group_by = array(), $arr_order_by = array(), $arr_limit = array())
    {
        $mix_return = self::_get_user($str_table_range, $str_result_type, $str_field, $arr_where, $arr_group_by, $arr_order_by, $arr_limit);
        return $mix_return;
    }

    /**
     * 全功能user&user_info查询方法 可配置查询条件、字段、完整度 被本类中其他函数调用
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
    protected function _get_user($str_table_range = 'user', $str_result_type = 'list', $str_field = '*', $arr_where = array(), $arr_group_by = array(), $arr_order_by = array(), $arr_limit = array())
    {
        $mix_return = self::_get_from_db($str_table_range, $str_result_type, $str_field, $arr_where, $arr_group_by, $arr_order_by, $arr_limit);
        return $mix_return;
    }
}