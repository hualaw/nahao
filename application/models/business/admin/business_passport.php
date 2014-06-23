<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 通用admin passport相关逻辑
 * Class Business_Passport
 * @author yanrui@tizi.com
 */
class Business_Passport extends NH_Model{

    /**
     * 从cookie中取出用户id和密码
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_token_from_cookie(){
        $arr_return = array();
        $str_cookie_key = '_token_'.ROLE_ADMIN;
        $str_cookie_value = get_cookie($str_cookie_key);
        if ($str_cookie_value) {
            $arr_cookie_value = json_decode(authcode($str_cookie_value, 'DECODE'),true);
            $arr_return['user_id'] = isset($arr_cookie_value['user_id']) ? $arr_cookie_value['user_id'] : 0;
            $arr_return['password'] = isset($arr_cookie_value['password']) ? $arr_cookie_value['password'] : '';
        }
        return $arr_return;
    }

    /**
     * 将用户id和密码存入cookie
     * @param array $arr_user_info
     * @author yanrui@tizi.com
     */
    public function set_token_to_cookie($arr_user_info){
        $str_cookie_key = '_token_'.ROLE_ADMIN;
        $arr_cookie_value = array(
            'user_id' => $arr_user_info['id'],
            'password' => $arr_user_info['password'],
        );
        $str_cookie_value = authcode(json_encode($arr_cookie_value), 'ENCODE');
        set_cookie($str_cookie_key,$str_cookie_value,0);
    }

    /**
     * login
     * @param $str_username
     * @param $str_password
     * @return bool
     * @author yanrui@tizi.com
     */
    public function login($str_username,$str_password){
        $bool_return = false;
        if($str_username AND $str_password){
            $arr_user_info = $this->admin->get_admin_by_username($str_username);// maybe from cache
            $str_salt = $arr_user_info['salt'];
            if(sha1($str_salt.sha1($str_password))===$arr_user_info['password']){
                if($arr_user_info['status']==1 OR $arr_user_info['id']==1){//超管和非冻结账户才能登录
//                    $this->passport->set_token_to_cookie($arr_user_info);
                    $this->session->set_userdata($arr_user_info);
                    $bool_return = true;
                }
            }
        }
        return $bool_return;
    }

    /**
     * logout
     * @author yanrui@tizi.com
     */
    public function logout(){
        $str_cookie_key = '_token_'.ROLE_ADMIN;
        delete_cookie($str_cookie_key);
    }

    /**
     * 从cache中取用户信息
     * @param $int_user_type
     * @param $int_user_id
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_user_from_cache($int_user_type,$int_user_id){
        $arr_return = array();
        $str_user_info = $this->cache->get("{$int_user_type}-{$int_user_id}");
        if ($str_user_info) {
            $arr_return = (array)json_decode($str_user_info, true);
        }
        return $arr_return;
    }

    /**
     * 从db中取用户信息
     * @param $int_user_type
     * @param $int_user_id
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_user_from_db($int_user_type,$int_user_id){
        $arr_return = array();
        $arr_role = array(ROLE_ADMIN,ROLE_STUDENT,ROLE_TEACHER);
        if(in_array($int_user_type,$arr_role) AND $int_user_id > 0){
            if($int_user_type==ROLE_ADMIN){
                $this->load->model('model/admin/Model_Admin', 'admin');
                $arr_return = $this->admin->get_admin_by_id($int_user_id);
//                $arr_return['permission'] = $this->admin->get_admin_permission($int_user_id, 'format');
            }
        }
        return $arr_return;
    }

}