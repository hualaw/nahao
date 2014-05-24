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
     * 创建user
     * @param $arr_param
     * @return int
     * @author yanrui@tizi.com
     */
    public function create_user($arr_param)
    {
        $int_return = 0;
        if($arr_param){
            $this->load->helper('string');
            $str_salt = random_string('alnum', 6);
            $arr_param['register_time'] = TIME_STAMP;
            $arr_param['status'] = 1;//默认启用
            $arr_param['salt'] = $str_salt;
            $arr_param['password'] = create_password($str_salt);
            $int_return = $this->model_user->create_user($arr_param);
        }
        return $int_return;
    }

    /**
     * 修改user
     * @param array $arr_param
     * @param array $arr_where
     * @return bool
     * @author yanrui@tizi.com
     */
    public function update_user($arr_param,$arr_where){
        $bool_flag = false;
        if($arr_param AND $arr_where){
            $bool_flag = $this->model_user->update_user($arr_param,$arr_where);
        }
        return $bool_flag;
    }

    /**
     * 根据条件获取user count
     * @param $arr_where
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_user_count($arr_where){
        $int_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'user';
            $str_result_type = 'count';
            $str_fields = 'count(1) as count';
            if(array_key_exists('user_id',$arr_where)){
                $arr_where[TABLE_USER.'.id'] = $arr_where['user_id'];
            }
            if(array_key_exists('nickname',$arr_where)){
                $arr_where['like'][TABLE_USER.'.nickname '] = $arr_where['nickname'];
                unset($arr_where['nickname']);
            }
            $int_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $int_return;
    }

    /**
     * 根据条件获取user list
     * @param $arr_where
     * @param $int_start
     * @param $int_limit
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_user_list($arr_where,$int_start,$int_limit){
        $arr_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'user';
            $str_result_type = 'list';
            $str_fields = 'id,nickname,phone_mask,email,realname,status,source';
            if(array_key_exists('user_id',$arr_where)){
                $arr_where[TABLE_USER.'.id'] = $arr_where['user_id'];
            }
            if(array_key_exists('nickname',$arr_where)){
                $arr_where['like'][TABLE_USER.'.nickname '] = $arr_where['nickname'];
                unset($arr_where['nickname']);
            }
            $arr_limit = array(
                'start'=>$int_start,
                'limit' => $int_limit
            );
            $arr_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where, array(), array(),$arr_limit);
        }
        return $arr_return;
    }

    /**
     * 根据id取user
     * @param $int_user_id
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_user_by_id($int_user_id)
    {
        $arr_return = array();
        if($int_user_id){
            $str_table_range = 'user';
            $str_result_type = 'one';
            $str_fields = '*';
            $arr_where = array(
                'id' => $int_user_id
            );
            $arr_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }

    /**
     * 根据username取user
     * @param string $str_username
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_user_by_username($str_username)
    {
        $arr_return = array();
        if($str_username){
            $str_table_range = 'user';
            $str_result_type = 'one';
            $str_fields = 'id,username,phone,email,salt,password,realname,status';
            $arr_where = array(
                'username' => $str_username
            );
//            echo $str_table_range.'--'.$str_result_type.'--'.$str_fields."\n";echo "where : \n";var_dump($arr_where);;exit;
            $arr_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }


}