<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Oauth相关逻辑
 * Class Business_Oauth
 * @author changlinjie@tizi.com
 */
class Business_Oauth extends NH_Model
{
    function __construct(){
        parent::__construct();
        $this->load->model('model/common/model_user');
    }

    /**
     * 根据从第三方获取的openid获取user
     * @param string $openid
     * @return array
     * @author changlinjie@tizi.com
     */
    public function get_user_by_openid($openid)
    {
        $arr_return = array();
        if($openid){
            $str_table_range = 'user';
            $str_result_type = 'one';
            $str_fields = '*';
            $arr_where = array(
                'openid' => $openid,
                'status' => 1,
            );
//            echo $str_table_range.'--'.$str_result_type.'--'.$str_fields."\n";echo "where : \n";var_dump($arr_where);;exit;
            $arr_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }

    public function create_user($user){
        $user_table_data = array(
            'register_time' => time(),
            'register_ip' => ip2long($this->input->ip_address()),
            'source' => 0,
            'avatar' => '', //default avatar URI, TBD
            'phone_verified' => 0,
            'email_verified' => 0,
            'reg_type' => 3
            );
        $user_table_data = array_merge($user_table_data,$user);
        $user_id = $this->model_user->create_user($user_table_data);
        //if insert table failed, the $uer_id is int zero
        if($user_id === 0)
        {
            $user_table_data['error'] = 'user_insert_failed';
            return $this->_log_reg_info(ERROR, 'reg_db_error', $user_table_data);
        }

        //insert a record into user_info table
        $insert_affected_rows = $this->model_user->create_user_info(array('user_id'=>$user_id));
        if($insert_affected_rows < 1)
        {
            //标记user表里的记录为无效状态
            $this->model_user->update_user(array('status'=>0), array('id'=>$user_id));
            $user_table_data['error'] = 'user_info_insert_failed';
            return $this->_log_reg_info(ERROR, 'reg_db_error', $user_table_data);
        }

        return $user_id;
    }

    public function do_login($user_info){
        $this->set_session_data($user_info['id'], $user_info['nickname'], $user_info['avatar'],$user_info['phone'],
             $user_info['phone_mask'], $user_info['email'], $user_info['login_type'], $user_info['teach_priv'], 1);
        //remember me
    }
}