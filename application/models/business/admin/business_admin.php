<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Admin相关逻辑
 * Class Business_Admin
 * @author yanrui@tizi.com
 */
class Business_Admin extends NH_Model
{
    function __construct(){
        parent::__construct();
        $this->load->model('model/admin/model_admin');
    }

    /**
     * 创建admin
     * @param $arr_param
     * @return int
     * @author yanrui@tizi.com
     */
    public function create_admin($arr_param)
    {
        $int_return = 0;
        if($arr_param){
            $this->load->helper('string');
            $str_salt = random_string('alnum', 6);
            $arr_param['register_time'] = TIME_STAMP;
            $arr_param['status'] = 1;//默认启用
            $arr_param['salt'] = $str_salt;
            $arr_param['password'] = create_password($str_salt);
            $int_return = $this->model_admin->create_admin($arr_param);
        }
        return $int_return;
    }

    /**
     * 修改admin
     * @param array $arr_param
     * @param array $arr_where
     * @return bool
     * @author yanrui@tizi.com
     */
    public function update_admin($arr_param,$arr_where){
        $bool_flag = false;
        if($arr_param AND $arr_where){
            $bool_flag = $this->model_admin->update_admin($arr_param,$arr_where);
        }
        return $bool_flag;
    }

    /**
     * 根据条件获取admin count
     * @param $arr_where
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_admin_count($arr_where){
        $int_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'admin';
            $str_result_type = 'count';
            $str_fields = 'count(1) as count';
            if(array_key_exists('group_id',$arr_where)){
                $arr_where[TABLE_ADMIN_PERMISSION_RELATION.'.group_id'] = $arr_where['group_id'];
            }
            if(array_key_exists('admin_id',$arr_where)){
                $arr_where[TABLE_ADMIN_PERMISSION_RELATION.'.admin_id'] = $arr_where['admin_id'];
            }
            if(array_key_exists('username',$arr_where)){
                $arr_where['like'][TABLE_ADMIN.'.username'] = $arr_where['username'];
                unset($arr_where['username']);
            }
            $int_return = $this->model_admin->get_admin_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $int_return;
    }

    /**
     * 根据条件获取admin count
     * @param $arr_where
     * @param $int_start
     * @param $int_limit
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_admin_list($arr_where,$int_start,$int_limit){
        $arr_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'admin_group_permission';
            $str_result_type = 'list';
            $str_fields = TABLE_ADMIN.'.id,username,phone,email,realname,'.TABLE_ADMIN.'.status,'.TABLE_ADMIN_GROUP.'.name as group_name,'.TABLE_ADMIN_PERMISSION_RELATION.'.group_id';
            if(array_key_exists('group_id',$arr_where)){
                $arr_where[TABLE_ADMIN_PERMISSION_RELATION.'.group_id'] = $arr_where['group_id'];
            }
            if(array_key_exists('admin_id',$arr_where)){
                $arr_where[TABLE_ADMIN_PERMISSION_RELATION.'.admin_id'] = $arr_where['admin_id'];
            }
            if(array_key_exists('username',$arr_where)){
                $arr_where['like'][TABLE_ADMIN.'.username'] = $arr_where['username'];
                unset($arr_where['username']);
            }
            $arr_limit = array(
                'start'=>$int_start,
                'limit' => $int_limit
            );
            $arr_return = $this->model_admin->get_admin_by_param($str_table_range, $str_result_type, $str_fields, $arr_where, array(), array(),$arr_limit);
        }
        return $arr_return;
    }

    /**
     * 根据id取admin
     * @param $int_admin_id
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_admin_by_id($int_admin_id)
    {
        $arr_return = array();
        if($int_admin_id){
            $str_table_range = 'admin';
            $str_result_type = 'one';
            $str_fields = '*';
            $arr_where = array(
                'id' => $int_admin_id
            );
            $arr_return = $this->model_admin->get_admin_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }

    /**
     * 根据username取admin
     * @param string $str_username
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_admin_by_username($str_username)
    {
        $arr_return = array();
        if($str_username){
            $str_table_range = 'admin';
            $str_result_type = 'one';
            $str_fields = 'id,username,phone,email,salt,password,realname,status';
            $arr_where = array(
                'username' => $str_username
            );
//            echo $str_table_range.'--'.$str_result_type.'--'.$str_fields."\n";echo "where : \n";var_dump($arr_where);;exit;
            $arr_return = $this->model_admin->get_admin_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }


    public function get_admin_list_by_group(){

    }

    public function create_group(){

    }

    public function update_group(){

    }

    public function get_group_by_id(){

    }

    public function get_group_by_name(){

    }

    public function create_permission()
    {

    }


}