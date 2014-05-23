<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Studnet相关逻辑
 * Class Business_Student
 * @author yanrui@91waijiao.com
 */
class Student_Order extends NH_Model{
    
    function __construct(){
        parent::__construct();
        $this->load->model('model/student/model_order');
        $this->load->model('model/student/model_course');
    }
    
    /**
     * 根据$int_product_id获取订单里面该轮的部分信息
     * @param  $int_product_id "就是轮id"
     * @return $array_return
     */
    public function get_order_round_info($int_product_id)
    {
        $array_return = array();
        $array_return = $this->model_course->get_round_info($int_product_id);
        return $array_return;
    }
    
    /**
     * 创建订单,向数据库里面写一条记录(订单表、订单与轮的关系表),同时添加订单日志
     * @param  $int_product_id
     * @return $array_return
     */
    public function create_order($int_product_id,$payment_method)
    {
        $array_data = $this->get_order_round_info($int_product_id);
        #原价
        $price = $array_data['price'];
        #促销价格
        $sale_price = $array_data['sale_price'];
        #创建订单时，支付方式是线下还是线上
        if ($payment_method == 'online')
        {
            $pay_type = 0;
        } else {
            $pay_type = 4;
        }
        #插入到订单表数组
        $array_order = array(
                'student_id'=>1,                                    #TODO用户id
                'create_time'=>time(),
                'price'=>$price,
                'status'=>1,
                'spend'=>$sale_price,
                'pay_type'=>$pay_type
        );
        #插入到订单表
        $int_insert_id = $this->model_order->insert_student_order($array_order);
        if ($int_insert_id)
        {
            #插入到订单与轮的关系表数组
            $array_mdata = array(
                    'round_id'=>$int_product_id,
                    'order_id'=>$int_insert_id
            );
            #插入到订单与轮的关系表
            $bool_result = $this->model_order->insert_order_round_relation($array_mdata);
        } else {
           $bool_result = false;
        }
        
        #写订单日志
        $order_msg = $bool_result == true ? "创建订单成功" : "创建订单失败";
        $array_prams = array(
            'order_id'=>$int_insert_id,
            'user_id'=>1,                                    #TODO用户id
            'user_type'=>2,                                  #用户类型 ：学生
            'action'=>1,
            'create_time'=>time(),
            'note'=>$order_msg
        );
        $this->model_order->add_order_log($array_prams);
        #返回值
        $array_return = $bool_result == true ? array('status'=>true,'order_id'=>$int_insert_id,'price'=>$price)
        : array('status'=>false);
        #创建订单之后是否要发短信
        return $array_return;
    }
    
    /**
     * 根据order_id获取订单信息
     * @param  $order_id
     */
    public function get_order_by_id($order_id)
    {
        $array_return = array();
        $array_return = $this->model_order->get_order_by_id($order_id);
        return $array_return;
    }

}