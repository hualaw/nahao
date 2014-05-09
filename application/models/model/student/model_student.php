<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * studnet相关逻辑
 * Class Model_Student
 * @author yanrui@91waijiao.com
 */
class Model_Student extends NH_Model{

    public function get_student(){
        $arr_result = array();
        $arr_result = $this->db->query('select * from admin')->result();
        return $arr_result;
    }
}