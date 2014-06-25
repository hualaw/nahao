<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Courseware相关逻辑
 * Class Model_Courseware
 * @author yanrui@tizi.com
 */
class Model_Courseware extends NH_Model
{
    /**
     * 创建courseware
     * @param array $arr_param
     * @return int
     * @author yanrui@tizi.com
     */
    public function create_courseware($arr_param)
    {
        $arr_result = $this->db->insert(TABLE_COURSEWARE, $arr_param);
//        $int_affected_rows = $this->db->affected_rows();
//        $int_insert_id = $this->db->insert_id();
//        return $int_affected_rows;
    }
}