<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * studnet相关逻辑
 * Class Model_Student
 * @author liubing@tizi.com
 */
class Model_Member extends NH_Model{
    
    function __construct(){
        parent::__construct();
    }

    /**
     * 获取用户的头像地址
     * @param  $int_user_id
     */
    public function get_user_avater($int_user_id)
    {
        $sql = "SELECT avater FROM user WHERE id = ".$int_user_id;
        $array_avater = $this->db->query($sql)->row_array();
        return empty($array_avater['avater']) ? DEFAULT_AVATER : $array_avater['avater'];
    }
    
    
    /**
     * 通过user_id获取我买的轮
     * @param  $int_user_id
     * @return $array_result
     */
    public function get_round_id_by_user_id($int_user_id)
    {
        $array_result = array();
        $sql = "SELECT round_id FROM student_class WHERE student_id = ".$int_user_id;
        $array_result = $this->db->query($sql)->result_array();
        return $array_result;
    }
}