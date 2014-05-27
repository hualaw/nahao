<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Studnet相关逻辑
 * Class Business_Student
 * @author yanrui@91waijiao.com
 */
class Student_Member extends NH_Model{
    
    function __construct(){
        parent::__construct();
        $this->load->model('model/student/model_member');
    }
    
    /**
     * 我买的课程
     * @param  $int_user_id
     * @return $array_return
     */
    public function get_my_course_for_buy($int_user_id)
    {
        $array_return = array();
        $array_return = $this->model_member->get_round_id_by_user_id($int_user_id);
        return $array_return;
    }
}