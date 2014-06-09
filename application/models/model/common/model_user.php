<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * user相关逻辑
 * Class Model_User
 * @author yanrui@tizi.com
 */
class Model_User extends NH_Model
{

    /**
     * 创建user
     * @param array $arr_param
     * @return int
     * @author yanrui@tizi.com
     */
    public function create_user($arr_param)
    {
        $arr_result = $this->db->insert(TABLE_USER, $arr_param);
        $int_insert_id = $this->db->insert_id();
        return $int_insert_id;
    }

    /**
     * 创建user_info
     * @param array $arr_param
     * @return int
     * @author yanrui@tizi.com
     */
    public function create_user_info($arr_param)
    {
        $arr_result = $this->db->insert(TABLE_USER_INFO, $arr_param);
        $int_insert_id = $this->db->affected_rows();
        return $int_insert_id;
    }

    /**
     * 修改user
     * @param array $arr_param
     * @param array $arr_where
     * @return bool
     * @author yanrui@tizi.com
     */
    public function update_user($arr_param,$arr_where){
        $this->db->update(TABLE_USER, $arr_param, $arr_where);
        $int_affected_rows = $this->db->affected_rows();
        return $int_affected_rows > 0 ? true :false;
    }

    /**
     * 修改user_info
     * @param array $arr_param
     * @param array $arr_where
     * @return bool
     * @author yanrui@tizi.com
     */
    public function update_user_info($arr_param,$arr_where){
        $this->db->update(TABLE_USER_INFO, $arr_param, $arr_where);
        $int_affected_rows = $this->db->affected_rows();
        return $int_affected_rows > 0 ? true :false;
    }
    /**
     * teacher账户禁用
     * @author shangshikai@tizi.com
     */
    public function account_close($arr)
    {
        foreach($arr as $v)
        {
           $this->db->update(TABLE_USER,array(TABLE_USER.'.status'=>0),array(TABLE_USER.'.id'=>$v));
           $this->db->update(TABLE_USER_INFO,array(TABLE_USER_INFO.'.status'=>0),array(TABLE_USER_INFO.'.user_id'=>$v));
        }
        return TRUE;
    }
    /**
     * teacher账户禁用
     * @author shangshikai@tizi.com
     */
    public function account_open($arr)
    {
        foreach($arr as $v)
        {
           $this->db->update(TABLE_USER,array(TABLE_USER.'.status'=>1),array(TABLE_USER.'.id'=>$v));
           $this->db->update(TABLE_USER_INFO,array(TABLE_USER_INFO.'.status'=>1),array(TABLE_USER_INFO.'.user_id'=>$v));
        }
        return TRUE;
    }
    /**
     * 根据省ID获取市
     * @author shangshikai@tizi.com
     */
    public function city2($province)
    {
         $this->db->select('nahao_areas.id,nahao_areas.name')->from('nahao_areas');
         $this->db->where("nahao_areas.parentid=$province");
         return $this->db->get()->result_array();
         // return $this->db->last_query();
    }
    /**
     * 根据市ID获取区
     * @author shangshikai@tizi.com
     */
    public function area2($city)
    {
         $this->db->select('nahao_areas.id,nahao_areas.name')->from('nahao_areas');
         $this->db->where("nahao_areas.parentid=$city");
         return  $this->db->get()->result_array();
         //return $this->db->last_query();
    }
    /**
     * 昵称是否存在
     * @author shangshikai@tizi.com
     */
    public function check_nick($nickname)
    {
         return $this->db->select('user.id')->from('user')->where("user.nickname='$nickname'")->get()->row_array();
        // return $this->db->last_query();
    }
    /**
     * 电话是否合法
     * @author shangshikai@tizi.com
     */
    public function check_tel($phone)
    {
        if(!is_mobile($phone))
        {
            return 1;
        }
        else
        {
            if(get_uid_phone_server($phone))
            {
                return 2;
            }
        }
    }
    /**
     * 邮箱是否合法
     * @author shangshikai@tizi.com
     */
    public function check_tec_email($email)
    {
        if(!is_email($email))
        {
            return "no";
        }
        else
        {
            $c=$this->db->select('user.id')->from('user')->where("user.email='$email'")->get()->row_array();
            return $c['id'];
            //return $this->db->last_query();
        }
    }
    /**
     * 删除user
     * @param array $arr_param
     * @return bool
     * @author yanrui@tizi.com
     */
    public function delete_user($arr_param){
        $this->db->delete(TABLE_USER,$arr_param);
        $int_affected_rows = $this->db->affected_rows();
        return $int_affected_rows > 0 ? true :false;
    }

    /**
     * 删除user_info
     * @param array $arr_param
     * @return bool
     * @author yanrui@tizi.com
     */
    public function delete_user_info($arr_param){
        $this->db->delete(TABLE_USER_INFO,$arr_param);
        $int_affected_rows = $this->db->affected_rows();
        return $int_affected_rows > 0 ? true :false;
    }

    /**
     * 根据参数获取user&user_info
     * @param string $str_table_range
     * @param string $str_result_type
     * @param string $str_field
     * @param array $arr_where
     * @param array $arr_group_by
     * @param array $arr_order_by
     * @param array $arr_limit
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_user_by_param($str_table_range, $str_result_type = 'list', $str_field = '*', $arr_where = array(), $arr_group_by = array(), $arr_order_by = array(), $arr_limit = array())
    {
        $mix_return = self::_get_user($str_table_range, $str_result_type, $str_field, $arr_where, $arr_group_by, $arr_order_by, $arr_limit);
        return $mix_return;
    }

    /**
     * 全功能user&user_info查询方法 可配置查询条件、字段、完整度 被本类中其他函数调用
     * @param string $str_table_range
     * @param string $str_result_type
     * @param string $str_field
     * @param array $arr_where
     * @param array $arr_group_by
     * @param array $arr_order_by
     * @param array $arr_limit
     * @return int|array $mix_return
     * @author yanrui@tizi.com
     */
    protected function _get_user($str_table_range = 'user', $str_result_type = 'list', $str_field = '*', $arr_where = array(), $arr_group_by = array(), $arr_order_by = array(), $arr_limit = array())
    {
        $mix_return = self::_get_from_db($str_table_range, $str_result_type, $str_field, $arr_where, $arr_group_by, $arr_order_by, $arr_limit);
        return $mix_return;
    }
}