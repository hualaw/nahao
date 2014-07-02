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
        $sql = "SELECT r.sale_price,so.status,so.round_id,so.id as order_id,r.teach_status,r.img,r.title FROM ".TABLE_STUDENT_ORDER." so 
                LEFT JOIN ".TABLE_ROUND." r ON so.round_id = r.id
                WHERE so.student_id = ".$int_user_id." AND (so.status = ".ORDER_STATUS_SUCC." OR so.status = ".ORDER_STATUS_FINISH."
                OR so.status = ".ORDER_STATUS_APPLYREFUND." OR so.status = ".ORDER_STATUS_APPLYREFUND_FAIL." 
                OR so.status = ".ORDER_STATUS_APPLYREFUND_AGREE." )
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
        $sql = "SELECT count(id) AS num FROM ".TABLE_STUDENT_CLASS." WHERE student_id = ".$int_user_id." 
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
        $sql = "SELECT count(id) AS num FROM ".TABLE_STUDENT_CLASS." WHERE student_id = ".$int_user_id." 
                AND round_id = ".$int_round_id." AND (status = ".STUDENT_CLASS_ENTER." OR status =".STUDENT_CLASS_LOST.")";
        $array_result = $this->db->query($sql)->row_array();
        return $array_result['num'];
    }
    
    /**
     * 下节课上课时间(即将开始的课)
     * @param  $int_round_id
     * @return $array_result
     */
    public function get_next_class_time($int_round_id)
    {
        $array_result = array();
        $sql = "SELECT begin_time,end_time FROM ".TABLE_CLASS." WHERE round_id =".$int_round_id." 
        		AND `status` =".CLASS_STATUS_SOON_CLASS." AND parent_id > 0 ";
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
            case 'pay': $where.=' AND status = '.ORDER_STATUS_SUCC;break;
            case 'nopay': $where.=' AND status = '.ORDER_STATUS_INIT;break;
            case 'cancel': $where.=' AND (status = '.ORDER_STATUS_CANCEL.' OR status = '.ORDER_STATUS_CLOSE.')';break;
            case 'refund': $where.=' AND (status = '.ORDER_STATUS_APPLYREFUND.
            			' OR status = '.ORDER_STATUS_APPLYREFUND_FAIL.
            			' OR status = '.ORDER_STATUS_APPLYREFUND_AGREE.
            			' OR status = '.ORDER_STATUS_APPLYREFUND_SUCC.')';break;
        }
        $array_result = array();
        $sql = "SELECT id,spend,create_time,status,round_id,pay_type FROM ".TABLE_STUDENT_ORDER." 
                WHERE student_id = ".$int_user_id." AND is_delete = 0 ".$where." ORDER BY id DESC LIMIT ".$int_start.",".$int_limit;
//         echo $sql;die;
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
            case 'pay': $where.=' AND status = '.ORDER_STATUS_SUCC;break;
            case 'nopay': $where.=' AND status = '.ORDER_STATUS_INIT;break;
            case 'cancel': $where.=' AND (status = '.ORDER_STATUS_CANCEL.' OR status = '.ORDER_STATUS_CLOSE.')';break;
            case 'refund': $where.=' AND (status = '.ORDER_STATUS_APPLYREFUND.
            			' OR status = '.ORDER_STATUS_APPLYREFUND_FAIL.
            			' OR status = '.ORDER_STATUS_APPLYREFUND_AGREE.
            			' OR status = '.ORDER_STATUS_APPLYREFUND_SUCC.')';break;
        }
        $array_result = array();
        $sql = "SELECT COUNT(id) AS count FROM ".TABLE_STUDENT_ORDER." WHERE student_id = ".$int_user_id.$where." AND is_delete = 0";
        $array_result = $this->db->query($sql)->row_array();
        return $array_result['count'];
    }
    
    /**
     * 添加到退款记录表
     * @param  $array_data
     */
    public function add_student_refund($array_data)
    {
        $this->db->insert(TABLE_STUDENT_REFUND, $array_data);
        $int_row = $this->db->affected_rows();
        return $int_row > 0 ? true : false;
    }
    
    /**
     * 更改学生与课的关系表里面的状态
     * @param $array_update['status']
     * @param $array_where['round_id']
     * @param $array_where['student_id']
     * @return bool
     */
    public function update_student_class($array_update,$array_where)
    { 
        $this->db->update(TABLE_STUDENT_CLASS,$array_update,$array_where);
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
        $this->db->update(TABLE_USER_INFO,$array_bank_update,$array_bank_where);
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
                FROM ".TABLE_STUDENT_REFUND." WHERE student_id = ".$int_user_id." AND round_id = ".$int_round_id;
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
                ui.teacher_intro,ui.teacher_signature,ui.user_id,ui.teacher_age FROM ".TABLE_USER." u
                LEFT JOIN ".TABLE_USER_INFO." ui ON u.id = ui.user_id
                WHERE ui.user_id = ".$int_user_id." AND u.status = 1";
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
        $this->db->update(TABLE_USER, array('phone_mask'=>$str_phone,'phone_verified'=>1),array('id'=>$int_user_id));
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
        $this->db->update(TABLE_USER_INFO, array('realname'=>$str_real_name),array('user_id'=>$int_user_id));
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
    	$sql = "SELECT teach_priv,avatar FROM ".TABLE_USER." WHERE id = ".$int_user_id." AND status = 1";
    	$array_result = $this->db->query($sql)->row_array();
    	return $array_result;
    }
    
    /**
     * 找到该学生没有上课的课id
     * @param  $array_data['round_id']
     * @param  $array_data['student_id']
     */
    public function get_student_class_undone($array_data)
    {
    	$array_result = array();
    	$sql = "SELECT class_id FROM ".TABLE_STUDENT_CLASS." WHERE student_id = ".$array_data['student_id']." 
    			AND round_id = ".$array_data['round_id']." AND (status = ".STUDENT_CLASS_INIT." OR status = ".STUDENT_CLASS_REFUND_FAIL.") ";
    	$array_result = $this->db->query($sql)->result_array();
    	return $array_result;
    }
}