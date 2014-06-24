<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_Member extends NH_Model{
    
    function __construct(){
        parent::__construct();
    }
    
    /**
     * 我购买的课程
     * @param  $int_user_id
     * @return $array_result
     */
    public function get_my_course_for_buy($int_user_id)
    {
        $array_result = array();
        $sql = "SELECT r.sale_price,so.status,so.round_id,so.id as order_id,r.teach_status,r.img,r.title FROM student_order so 
                LEFT JOIN round r ON so.round_id = r.id
                WHERE so.student_id = ".$int_user_id." AND (so.status = 2 OR so.status = 3
                OR so.status = 6 OR so.status = 7 OR so.status = 8 OR so.status = 9)
                ORDER BY so.id DESC";
        $array_result = $this->db->query($sql)->result_array();
        return $array_result;
    }
    
    /**
     * 学生买这轮共M节
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
                AND parent_id > 0 ";
        $array_result = $this->db->query($sql)->row_array();
        return $array_result;
    }
    
    /**
     * 我的订单列表
     * @param  $str_type
     * @param  $str_type
     * @return $array_result
     */
    public function get_order_list($int_user_id,$str_type,$int_start,$int_limit)
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
        $sql = "SELECT id,spend,create_time,status,round_id,pay_type FROM student_order 
                WHERE student_id = ".$int_user_id." AND is_delete = 0 ".$where." ORDER BY id DESC LIMIT ".$int_start.",".$int_limit;
        $array_result = $this->db->query($sql)->result_array();
        return $array_result;
    }
    
    /**
     * 订单总数
     * @param  $int_user_id
     * @param  $str_type
     */
    public function get_order_count($int_user_id,$str_type)
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
        $sql = "SELECT COUNT(id) AS count FROM student_order WHERE student_id = ".$int_user_id.$where;
        $array_result = $this->db->query($sql)->row_array();
        return $array_result['count'];
    }
    
    /**
     * 添加到退款记录表
     * @param  $array_data
     */
    public function add_student_refund($array_data)
    {
        $this->db->insert('student_refund', $array_data);
        $int_row = $this->db->affected_rows();
        return $int_row > 0 ? true : false;
    }
    
    /**
     * 更改学生与课的关系表，将该学生没有上过的课里面的状态更为申请退款
     * @param $array_update['status']
     * @param $array_where['round_id']
     * @param $array_where['student_id']
     * @return bool
     */
    public function update_student_class($array_update,$array_where)
    { 
        $this->db->update('student_class',$array_update,$array_where);
        $int_row = $this->db->affected_rows();
        return $int_row > 0 ? true : false;
    }
    
    /**
     * 更新用户的银行卡信息
     * @param  $array_bank_update['bankname']
     * @param  $array_bank_update['bankbench']
     * @param  $array_bank_update['bankcard']
     * @param  $array_bank_update['id_code']
     * @param  $array_bank_where['student_id']
     * @return bool
     */
    public function update_user_bank_infor($array_bank_update,$array_bank_where)
    {
        $this->db->update('user_info',$array_bank_update,$array_bank_where);
        $int_row = $this->db->affected_rows();
        return $int_row > 0 ? true : false;
    }
    
    /**
     * #获取学生退款记录
     * @param  $int_user_id
     * @param  $int_round_id
     * @return $array_result
     */
    public function get_student_refund_data($int_user_id,$int_round_id)
    {
        $array_result = array();
        $sql = "SELECT study_count,refund_count,round_price,refund_price,reason,status,create_time
                FROM student_refund WHERE student_id = ".$int_user_id." AND round_id = ".$int_round_id;
        $array_result = $this->db->query($sql)->row_array();
        return $array_result;
    }
    
    /**
     * 获取用户信息
     * @param  $int_user_id
     * @return $array_result
     */
    public function get_user_infor($int_user_id)
    {
        $array_result = array();
        $sql = "SELECT u.nickname,u.avatar,ui.realname,ui.teacher_age,ui.work_auth,ui.teacher_auth,ui.titile_auth,
                ui.teacher_intro,ui.teacher_signature,ui.user_id,ui.teacher_age FROM user u
                LEFT JOIN user_info ui ON u.id = ui.user_id
                WHERE ui.user_id = ".$int_user_id." AND u.status = 1";
        //echo $sql.'---';
        $array_result = $this->db->query($sql)->row_array();
        return  $array_result;
    }
    
    /**
     * 确认订单跟新用户信息
     * @param  $str_phone
     * @param  $int_user_id
     * @return $bool_result
     */
    public function update_user($str_phone,$int_user_id)
    {
        $this->db->update('user', array('phone_mask'=>$str_phone,'phone_verified'=>1),array('id'=>$int_user_id));
        $int_row = $this->db->affected_rows();
        return $bool_result = $int_row > 0  ? true : false;
    }
    
    /**
     * 确认订单跟新用户扩展信息
     * @param  $str_real_name
     * @param  $int_user_id
     * @return $bool_result
     */
    public function update_user_info($str_real_name,$int_user_id)
    {
        $this->db->update('user_info', array('realname'=>$str_real_name),array('user_id'=>$int_user_id));
        $int_row = $this->db->affected_rows();
        return $bool_result = $int_row > 0  ? true : false;
    }
    
    /**
     * 获取用户头像
     * @param  $int_user_id
     * @return $array_result
     */
    public function get_user_avater($int_user_id)
    {
    	$sql = "SELECT teach_priv,avatar FROM user WHERE id = ".$int_user_id." AND status = 1";
    	$array_result = $this->db->query($sql)->row_array();
    	return $array_result;
    }
}