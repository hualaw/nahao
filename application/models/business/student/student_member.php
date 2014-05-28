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
    public function get_my_course($int_user_id)
    {
        $array_return = array();
        #根据user_id获取轮id
        $array_round_ids = $this->model_member->get_round_id_by_user_id($int_user_id);
        #根据轮id获取轮的具体信息
        if ($array_round_ids)
        {
            foreach ($array_round_ids as $k=>$v)
            {
                $this->model_member->
            }
        }
        return $array_return;
    }
}