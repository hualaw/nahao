<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User相关逻辑
 * Class Business_User
 * @author yanrui@tizi.com
 */
class Business_User extends NH_Model
{
    function __construct(){
        parent::__construct();
        $this->load->model('model/common/model_user');
    }

    /**
     * 根据绑定的email取user
     * @param string $str_username
     * @return array
     * @author yanhengjia@tizi.com
     */
    public function get_user_by_email($email)
    {
        $arr_return = array();
        if($email){
            $str_table_range = 'user';
            $str_result_type = 'one';
            $str_fields = 'id,nickname,email';
            $arr_where = array(
                'email' => $email
            );
//            echo $str_table_range.'--'.$str_result_type.'--'.$str_fields."\n";echo "where : \n";var_dump($arr_where);;exit;
            $arr_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }

    /**
     * 通过手机重新设置密码
     * @param int       $user_id 用户Id
     * @param string    $password 新密码
     */
    public function phone_reset_password($user_id, $password)
    {
        $user_info = $this->model_user->get_user_by_param('user', 'one', '*', array('id' => $user_id));
        if($user_info) {
            $new_password = $user_info['salt'] . sha1($password);
            $res = $this->model_user->update_user(array('password' => $new_password), array('id' => $user_info['id']));
            return $res === false ? false : true;
        }
        
        return false;
    }
}