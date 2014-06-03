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
        $this->load->model('business/student/student_order');
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
                    $array_return[$k]['next_class_time'] = $this->student_course->handle_time($next_class_time['begin_time'],$next_class_time['end_time']);
                }
            }
        }
        return $array_return;
    }
    
    /**
     * 我的订单列表
     * @param  $int_user_id
     * @param  $str_type
     * @return $array_return
     */
    public function get_order_list($int_user_id,$str_type,$int_start,$int_limit)
    {
        $array_return = array();
        $array_return = $this->model_member->get_order_list($int_user_id,$str_type,$int_start,$int_limit);
        if ($array_return)
        {
            foreach ($array_return as $k=>$v)
            {
                $array_returns = $this->student_course->get_round_info($v['round_id']);
                $array_return[$k]['create_time'] = date('Y/m/d H:i:s',$v['create_time']);
                $array_return[$k]['class_img'] = $array_returns['class_img'];
                $array_return[$k]['title'] = $array_returns['title'];
                if ($v['pay_type'] == '0'|| $v['pay_type'] == '1' ||$v['pay_type'] == '2'||$v['pay_type'] == '3')
                {
                    $array_return[$k]['payment_method'] = 'online';
                } elseif ($v['pay_type'] == '4') {
                    $array_return[$k]['payment_method'] = 'remittance';
                }
            }
        }
        return $array_return;
    }
    
    /**
     * 获取订单的总数
     * @param  $int_user_id
     * @param  $str_type
     * @return $array_return
     */
    public function get_order_count($int_user_id,$str_type)
    {
        $array_return = array();
        $array_return = $this->model_member->get_order_count($int_user_id,$str_type);
        return $array_return;
    }
    
    /**
     * 获取申请退课数据
     * @param  $int_round_id
     * @param  $int_user_id
     * @return $array_return
     */
    public function get_apply_refund_data($int_user_id,$int_round_id)
    {
        $array_return = array();
        #获取轮的信息
        $array_round = $this->model_course->get_round_info($int_round_id);
        #这个人买的这轮上了N节
        $array_return['class'] = $this->model_member->get_student_class_done($int_user_id,$int_round_id);
        #这个人买的这轮总共M节
        $array_return['totle_class'] = $this->model_member->get_student_class_totle($int_user_id,$int_round_id);
        #获取轮的标题
        $array_return['title'] = $array_round['title'];
        #课程总金额
        $array_return['totle_money'] = $array_round['sale_price'];
        #课时费
        $array_return['reward'] = $array_round['reward'];
        #可退金额
        $array_return['return_money'] = $array_return['totle_money'] - $array_return['yi_shang'] * $array_return['reward']*2;
        #这个人买的这轮没上N节
        $array_return['unclass'] = $array_return['totle_class'] - $array_return['class'];
        #轮id
        $array_return['round_id'] = $array_round['id'];
        return $array_return;
    }
    
    /**
     * 添加退课申请
     * @param  $array_data
     */
    public function save_refund($array_data)
    {
        #添加到退款记录表
        $array_add_data = array(
            'round_id'=>$array_data['round_id'],
            'student_id'=>$array_data['student_id'],
            'times'=>$array_data['times'],
            'amount'=>$array_data['amount'],
            'create_time'=>time(),
            'order_id'=>$array_data['order_id'],
            'reason'=>$array_data['reason']
        );
        $this->model_member->add_student_refund($array_add_data);
        #更改订单表的状态
        $this->student_order->update_order_status();
    }
}