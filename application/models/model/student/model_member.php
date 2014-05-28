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
     * 我购买的课程
     * @param  $int_user_id
     * @return $array_result
     */
    public function get_my_course_for_buy($int_user_id)
    {
        $array_result = array();
        $sql = "SELECT so.round_id,so.id as order_id,r.teach_status,r.img,r.title FROM student_order so 
                LEFT JOIN round r ON so.round_id = r.id
                WHERE so.student_id = ".$int_user_id." AND so.status >= 2 AND so.status <= 3
                ORDER BY so.id DESC";
        $array_result = $this->db->query($sql)->result_array();
        return $array_result;
    }
    
    /**
     * 学生这轮共M节
     * @param  $int_user_id
     * @param  $int_round_id
     * @return $array_result['num']
     */
    public function get_student_class_totle($int_user_id,$int_round_id)
    {
        $sql = "SELECT count(id) AS num FROM student_class WHERE student_id = ".$int_user_id." 
                AND round_id = ".$int_round_id;
        $array_result = $this->db->query($sql)->row_array();
        return $array_result['num'];
    }
    
    /**
     * 学生这轮已上N节
     * @param  $int_user_id
     * @param  $int_round_id
     * @return $array_result['num']
     */
    public function get_student_class_done($int_user_id,$int_round_id)
    {
        $sql = "SELECT count(id) AS num FROM student_class WHERE student_id = ".$int_user_id." 
                AND round_id = ".$int_round_id." AND status = 2";
        $array_result = $this->db->query($sql)->row_array();
        return $array_result['num'];
    }
    
    /**
     * 下节课上课时间
     * @param  $int_round_id
     * @return $array_result
     */
    public function get_next_class_time($int_round_id)
    {
        $array_result = array();
        $sql = "SELECT begin_time,end_time FROM class WHERE round_id =".$int_round_id." AND `status` =1 
                AND parent_id > 0 AND parent_id !=''";
        $array_result = $this->db->query($sql)->row_array();
        return $array_result;
    }
    
    /**
     * 我的订单列表
     * @param  $str_type
     * @param  $str_type
     * @return $array_result
     */
    public function get_order_list($int_user_id,$str_type)
    {
        $where = '';
        switch ($str_type)
        {
            case 'all': $where.='';break;
            case 'pay': $where.=' AND status = 2';break;
            case 'nopay': $where.=' AND status = 0';break;
            case 'cancel': $where.=' AND status = 4';break;
            case 'refund': $where.=' AND status = 9';break;
        }
        $array_result = array();
        $sql = "SELECT id,spend,create_time,status FROM student_order 
                WHERE student_id = ".$int_user_id.$where." ORDER BY id DESC";
        $array_result = $this->db->query($sql)->result_array();
        return $array_result;
    }
}