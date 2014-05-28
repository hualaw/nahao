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
        $this->load->model('business/student/student_course');
    }
    
    /**
     * 我购买的课程
     * @param  $int_user_id
     * @return $array_return
     */
    public function get_my_course_for_buy($int_user_id)
    {
        $array_return = array();
        $array_return = $this->model_member->get_my_course_for_buy($int_user_id);
        if ($array_return)
        {
            foreach ($array_return as $k=>$v)
            {
                #图片地址
                $array_return[$k]['class_img'] = empty( $array_return['class_img']) ? HOME_IMG_DEFAULT : $array_return['class_img'];
                #这轮以上N节/共M节
                $array_return[$k]['totle'] = $this->model_member->get_student_class_totle($int_user_id,$v['round_id']);
                $array_return[$k]['yi_shang'] = $this->model_member->get_student_class_done($int_user_id,$v['round_id']);
                if ($v['teach_status'] >=0 && $v['teach_status'] <=1)
                {
                    $next_class_time = $this->model_member->get_next_class_time($v['round_id']);
                    //var_dump($next_class_time);die;
                    $array_return[$k]['next_class_time'] = $this->student_course->handle_time($next_class_time['begin_time'],$next_class_time['end_time']);
                }
            }
        }
        return $array_return;
    }
    
    /**
     * 我的订单列表
     * @param  $str_type
     * @param  $str_type
     * @return $array_return
     */
    public function get_order_list($int_user_id,$str_type)
    {
        $array_return = array();
        $array_return = $this->model_member->get_order_list($int_user_id,$str_type);
        return $array_return;
    }
}