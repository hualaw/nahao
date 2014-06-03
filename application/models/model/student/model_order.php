<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * studnet相关逻辑
 * Class Model_Student
 * @author liubing@tizi.com
 */
class Model_Order extends NH_Model{
    
    function __construct(){
        parent::__construct();
    }

    /**
     * 首页获取一门课程里面最新的一轮（在审核通过和销售中）
     * @return $array_result
     * @author liubing@tizi.com
     */
    public function get_course_latest_round()
    {
        $array_result = array();
        $sql = "SELECT course_id,MIN(start_time) AS start_time FROM round
                WHERE sale_status >= 2 AND sale_status <= 3 GROUP BY course_id";
        $array_result = $this->db->query($sql)->result_array();
        return $array_result;
    }
    
    /**
     * 创建订单,向数据库里面写一条记录(订单表)
     * @param  $array_order
     * @return $result
     */
    public function insert_student_order($array_order)
    {
        $this->db->insert('student_order',$array_order);
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
        $this->db->insert('order_round_relation',$array_mdata);
        $int_row = $this->db->affected_rows();
        $bool_result = $int_row > 0 ? true : false;
        return $bool_result;
    }
    
    /**
     * 添加订单日志
     * @param  $array_prams
     * @return $bool_result
     */
    public function add_order_log($array_prams)
    {
        $this->db->insert('order_action_log',$array_prams);
        $int_row = $this->db->affected_rows();
        $bool_result = $int_row > 0 ? true : false;
        return $bool_result;
    }
    
    /**
     * 根据order_id获取订单信息
     * @param  $int_order_id
     * @return $array_result
     */
    public function get_order_by_id($int_order_id)
    {
        $array_result = array();
        $sql = "SELECT id,student_id,status,price,spend,create_time,round_id,pay_type FROM student_order 
                WHERE id = ".$int_order_id." AND is_delete = 0";
        $array_result = $this->db->query($sql)->row_array();
        return $array_result;
    }

    /**
     * 更新订单状态
     * @param  $array_data 
     * @return $bool_result
     */
    public function update_order_status($array_data)
    {
        $data = array(
                'status' => $array_data['status'],
                'id' => $array_data['order_id'],
                'confirm_time' => time()
        );
        
        $this->db->where('id', $array_data['order_id']);
        $this->db->update('student_order', $data);
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
        $this->db->insert('student_class',$array_data);
        $int_row = $this->db->affected_rows();
        return $bool_result = $int_row > 0 ? true : false;
    }
    
    /**
     * 检查商品id是否在订单表中及其状态
     */
    public function check_product_in_order($int_product_id,$int_user_id)
    {
        $array_result = array();
        $sql = "SELECT status FROM student_order WHERE student_id = ".$int_user_id." 
                AND round_id = ".$int_product_id." AND is_delete = 0";
        $array_result = $this->db->query($sql)->result_array();
    }
}