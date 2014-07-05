<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_Order extends NH_Model{
    
    function __construct(){
        parent::__construct();
    }
    
    /**
     * 创建订单,向数据库里面写一条记录(订单表)
     * @param  $array_order
     * @return $result
     */
    public function insert_student_order($array_order)
    {
        $this->db->insert(TABLE_STUDENT_ORDER,$array_order);
        $int_inser_id = $this->db->insert_id();
        $result = $int_inser_id > 0 ? $int_inser_id : false;
        return $result;
    }
    
    /**
     *  创建订单,向数据库里面写一条记录(订单与轮的关系表)
     *  @param  $array_mdata
     *  @return $bool_result
     */
    public function insert_order_round_relation($array_mdata)
    {
        $this->db->insert(TABLE_ORDER_ROUND_RELATION,$array_mdata);
        $int_row = $this->db->affected_rows();
        $bool_result = $int_row > 0 ? true : false;
        return $bool_result;
    }
    
    /**
     * 添加订单日志
     * @param  $array_data['order_id']
     * @param  $array_data['user_type']
     * @param  $array_data['user_id']
     * @param  $array_data['action']
     * @param  $array_data['create_time']
     * @param  $array_data['note']
     */
    public function add_order_log($array_data)
    {
        $this->db->insert(TABLE_ORDER_ACTION_LOG,$array_data);
        $int_row = $this->db->affected_rows();
        return $int_row > 0 ? true : false;
    }
    
    /**
     * 根据order_id获取订单信息
     * @param  $int_order_id
     * @return $array_result
     */
    public function get_order_by_id($int_order_id)
    {
        $array_result = array();
        $sql = "SELECT id,student_id,status,price,spend,create_time,round_id,pay_type FROM ".TABLE_STUDENT_ORDER." WHERE id = ".$int_order_id." AND is_delete = 0";
        $array_result = $this->db->query($sql)->row_array();
        return $array_result;
    }

    /**
     * 更新订单状态
     * @param  $array_data 
     * @return $bool_result
     */
    public function update_order_status($array_update,$array_where)
    {
        $this->db->update(TABLE_STUDENT_ORDER, $array_update,$array_where);
        $int_row = $this->db->affected_rows();
        return $bool_result = $int_row > 0  ? true : false;
    }

    
    /**
     * 添加学生与课的关系
     * @param  $array_data
     * @return $bool_result
     */
    public function add_student_class_relation($array_data)
    {
        $this->db->insert(TABLE_STUDENT_CLASS,$array_data);
        $int_row = $this->db->affected_rows();
        return $bool_result = $int_row > 0 ? true : false;
    }
    
    /**
     * 检查商品id是否在订单表中及其状态
     * @param  $int_product_id
     * @param  $int_user_id
     * @return $array_result
     */
    public function check_product_in_order($int_product_id,$int_user_id)
    {
        $array_result = array();
        $sql = "SELECT id,status FROM ".TABLE_STUDENT_ORDER." WHERE student_id = ".$int_user_id." AND round_id = ".$int_product_id." AND is_delete = 0 ORDER BY id DESC";
        $array_result = $this->db->query($sql)->result_array();
        return $array_result;
    }
    
    /**
     * 删除订单(标记删除)
     * @param  $array_update
     * @param  $array_where
     * @return $bool_result
     */
    public function delete_order($array_update,$array_where)
    {
        $this->db->update(TABLE_STUDENT_ORDER, $array_update,$array_where);
        $int_row = $this->db->affected_rows();
        return $bool_result = $int_row > 0  ? true : false;
    }
    
    /**
     * 检查该用户是否已经下单且未付款
     * @param  $int_product_id
     * @param  $int_user_id
     * @return $array_result
     */
    public function check_have_order($int_product_id,$int_user_id)
    {
        $array_result = array();
        $sql = "SELECT id,pay_type FROM ".TABLE_STUDENT_ORDER." WHERE student_id = ".$int_user_id." AND round_id = ".$int_product_id." AND status = 0 AND is_delete = 0";
        $array_result = $this->db->query($sql)->result_array();
        return $array_result;
    }
    
    /**
     * 更新轮的购买人数
     * @param  $int_round_id
     * @return boolean
     */
    public function update_round_buy_count($int_round_id)
    {
    	$sql = "UPDATE ".TABLE_ROUND." SET bought_count = bought_count+1 WHERE id = ".$int_round_id;
    	$this->db->query($sql);
    	$int_row = $this->db->affected_rows();
    	return $bool_result = $int_row > 0  ? true : false;
    }
    
    /**
     * 更新轮的销售状态
     * @param  $int_round_id
     * @param  $status
     * @return boolean
     */
    public function update_round_sale_status($int_round_id,$status)
    {
        $this->db->update(TABLE_ROUND, array('sale_status'=>$status),array('id'=>$int_round_id));
        $int_row = $this->db->affected_rows();
        return $bool_result = $int_row > 0  ? true : false;
    }
}