<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class相关逻辑
 * Class Model_Class
 * @author yanrui@tizi.com
 */
class Model_Class extends NH_Model
{

    /**
     * 创建class
     * @param array $arr_param
     * @return int
     * @author yanrui@tizi.com
     */
    public function create_class($arr_param)
    {
        $arr_result = $this->db->insert(TABLE_CLASS, $arr_param);
        $int_insert_id = $this->db->insert_id();
        return $int_insert_id;
    }

    /**
     * 批量创建classes
     * @param $arr_param
     * @return mixed
     * @author yanrui@tizi.com
     */
    public function create_class_batch($arr_param){
        $arr_result = $this->db->insert_batch(TABLE_CLASS, $arr_param);
        $int_insert_id = $this->db->insert_id();
        return $int_insert_id;
    }

    /**
     * 删除class
     * @param $arr_where
     * @return bool
     * @author yanrui@tizi.com
     */
    public function delete_class_by_param($arr_where){
        $this->db->delete(TABLE_CLASS, $arr_where);
    }

    /**
     * 修改class
     * @param array $arr_param
     * @param array $arr_where
     * @return bool
     * @author yanrui@tizi.com
     */
    public function update_class($arr_param,$arr_where){
        $this->db->update(TABLE_CLASS, $arr_param, $arr_where);
        $int_affected_rows = $this->db->affected_rows();
//        o($int_affected_rows);
        return $int_affected_rows > 0 ? true :false;
    }


    /**
     * 根据参数获取class
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
    public function get_class_by_param($str_table_range, $str_result_type = 'list', $str_field = '*', $arr_where = array(), $arr_group_by = array(), $arr_order_by = array(), $arr_limit = array())
    {
        $mix_return = self::_get_class($str_table_range, $str_result_type, $str_field, $arr_where, $arr_group_by, $arr_order_by, $arr_limit);
        return $mix_return;
    }

    /**
     * 全功能class查询方法 可配置查询条件、字段、完整度 被本类中其他函数调用
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
    protected function _get_class($str_table_range = 'class', $str_result_type = 'list', $str_field = '*', $arr_where = array(), $arr_group_by = array(), $arr_order_by = array(), $arr_limit = array())
    {
        $mix_return = self::_get_from_db($str_table_range, $str_result_type, $str_field, $arr_where, $arr_group_by, $arr_order_by, $arr_limit);
//        var_dump($mix_return);exit;
        return $mix_return;
    }
}