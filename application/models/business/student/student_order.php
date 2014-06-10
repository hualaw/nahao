<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
        if ($array_return)
        {
            #总金额
            $array_return['totle_money'] = $array_return['sale_price'];
            #节省了多少钱
            $array_return['save_money'] = $array_return['price']-$array_return['sale_price'];
        }
        return $array_return;
    }
    
    /**
     * 创建订单,向数据库里面写一条记录(订单表、订单与轮的关系表),同时添加订单日志
     * @param  $int_product_id
     * @param  $payment_method
     * @param  $int_user_id
     * @return $array_return
     */
    public function create_order($int_product_id,$payment_method,$int_user_id)
    {
        $array_data = $this->get_order_round_info($int_product_id);
        #原价
        $price = $array_data['price'];
        #促销价格
        $sale_price = $array_data['sale_price'];
        #创建订单时，支付方式是线下还是线上
        if ($payment_method == 'online')
        {
            $pay_type = ORDER_TYPE_ONLINE;
        } else {
            $pay_type = ORDER_TYPE_OFFLINE;
        }
        #插入到订单表数组
        $array_order = array(
                'round_id'=>$int_product_id,
                'student_id'=>$int_user_id,                                    #TODO用户id
                'create_time'=>time(),
                'price'=>$price,
                'status'=>ORDER_STATUS_INIT,
                'spend'=>$sale_price,
                'pay_type'=>$pay_type
        );
        #插入到订单表
        $int_insert_id = $this->model_order->insert_student_order($array_order);
        #写订单日志
        $order_msg = $int_insert_id  > 0 ? "创建订单成功" : "创建订单失败";
        $array_prams = array(
            'order_id'=>$int_insert_id,
            'user_id'=>$int_user_id,                                      #TODO用户id
            'user_type'=>ROLE_STUDENT,                         #用户类型 ：学生
            'action'=>1,                                       #创建订单
            'create_time'=>time(),
            'note'=>$order_msg
        );
        $this->model_order->add_order_log($array_prams);
        #返回值
        $array_return = $int_insert_id >0 ? array('status'=>true,'order_id'=>$int_insert_id,'price'=>$price)
        : array('status'=>false);
        #创建订单之后是否要发短信
        return $array_return;
    }
    
    /**
     * 根据$int_order_id获取订单信息
     * @param  $int_order_id
     * @return $array_return
     */
    public function get_order_by_id($int_order_id)
    {
        $array_return = array();
        $array_return = $this->model_order->get_order_by_id($int_order_id);
        return $array_return;
    }
    
    /**
     * 更新订单状态，写日志
     * @param  $array_data['order_id']    #订单号
     * @param  $array_data['status']      #订单状态
     * @param  $array_data['action']      #日志动作
     * @param  $array_data['note']        #日志记录
     * @return $bool_return
     */
    public function update_order_status($array_data)
    {
        $bool_return = $this->model_order->update_order_status($array_data);
        if ($bool_return)
        {
            $array_order_log = array(
                'order_id'=>$array_data['order_id'],
                'user_type'=> ROLE_STUDENT,              #用户类型 ：学生
                'user_id'=>$array_data['user_id'],       #TODO用户id
                'action'=>$array_data['action'],
                'create_time'=>time(),
                'note'=>$array_data['note']
            );
            #添加订单日志
            $this->model_order->add_order_log($array_order_log);
        }
        return $bool_return;
    }
    
    /**
     * 根据$int_order_id,查找轮以及轮里面的课，添加学生与课的关系
     * @param  $int_order_id
     * @param  $int_user_id
     * @return $bool_return
     */
    public function add_student_class_relation($int_order_id,$int_user_id)
    {
        #根据订单id，获取该订单下的轮
        $array_round_id = $this->model_order->get_order_by_id($int_order_id);
        $array_class = array();
        if (empty($array_round_id)){
            return $response['message'] = "订单里面没有对应的轮";
        }
        
        $array_class = $this->model_course->get_class_under_round_id($array_round_id['round_id']);
        
        if(empty($array_class))
        {
            return $response['message'] = "轮下面没有课";
        }
        
        foreach ($array_class as $kk=>$vv)
        {
            #添加学生与课的关系
            $array_data = array(
                'student_id'=>$int_user_id,                                        #TODO用户id
                'course_id'=>$vv['course_id'],
                'round_id'=>$vv['round_id'],
                'class_id'=>$vv['lesson_id'],
                'status'=>0
            );
            $bool_return = $this->model_order->add_student_class_relation($array_data);
        }
        return $bool_return;
    }
    
    /**
     * 检查商品id是否在订单表中及其状态
     * @param  $int_product_id
     * @param  $int_user_id
     * @return $array_return
     */
    public function check_product_in_order($int_product_id,$int_user_id)
    {
        $array_return = array();
        $array_return = $this->model_order->check_product_in_order($int_product_id,$int_user_id);
        return $array_return;
    }
    
    /**
     * 删除订单，并且写日志
     * @param  $array_data['user_id']
     * @param  $array_data['order_id']
     * @param  $array_data['action']
     * @param  $array_data['note']
     * @return $bool_return
     */
    public function delete_order($array_data)
    {
        $array_update = array(
            'is_delete'=>1    
        );
        $array_where = array(
           'id'=> $array_data['order_id'] 
        );
        #删除日志(标记删除)
        $bool_return = $this->model_order->delete_order($array_update,$array_where);
        if ($bool_return)
        {
            $array_order_log = array(
                'order_id'=>$array_data['order_id'],
                'user_type'=> ROLE_STUDENT,              #用户类型 ：学生
                'user_id'=>$array_data['user_id'],       #TODO用户id
                'action'=>$array_data['action'],
                'create_time'=>time(),
                'note'=>$array_data['note']
            );
            #添加订单日志
            $this->model_order->add_order_log($array_order_log);
        }
        return $bool_return;
    }

}