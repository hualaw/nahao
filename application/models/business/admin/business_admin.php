<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Admin相关逻辑
 * Class Business_Admin
 * @author yanrui@91waijiao.com
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
     * @author yanrui@91waijiao.com
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
     * @param $arr_param
     * @param $arr_data
     * @return bool
     * @author yanrui@91waijiao.com
     */
    public function update_admin($arr_param,$arr_data){
        $bool_flag = false;
        if($arr_param AND $arr_data){
            $bool_flag = $this->model_admin->update_admin($arr_param,$arr_data);
        }
        return $bool_flag;
    }

    /**
     * 根据id取admin
     * @param $int_admin_id
     * @return array
     * @author yanrui@91waijiao.com
     */
    public function get_admin_by_id($int_admin_id)
    {
        $arr_return = array();
        if($int_admin_id){
            $str_fields = '*';
            $arr_where = array(
                'id' => $int_admin_id
            );
            $arr_return = $this->model_admin->get_admin_by_param($str_fields, $arr_where);
        }
        return $arr_return;
    }

    /**
     * 根据username取admin
     * @param $str_username
     * @return array
     * @author yanrui@91waijiao.com
     */
    public function get_admin_by_username($str_username)
    {
        $arr_return = array();
        if($str_username){
            $str_fields = '*';
            $arr_where = array(
                'username' => $str_username
            );
            $arr_return = $this->model_admin->get_admin_by_param($str_fields, $arr_where);
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

    public function order($post)
    {
        //echo "this is business";die;
        $this->load->model("model/admin/model_admin","admin_order");
        return $this->admin_order->order($post);
    }
    /**
     * 查询总记录数
     * @param
     * @return boolean
     * @author shangshikai@nahao.com
     */
    public function order_list()
    {

        $this->load->model("model/admin/model_admin");
        return $this->model_admin->admin_order_list();
    }
    /**
     * 查询订单
     * @param
     * @return boolean
     * @author shangshikai@nahao.com
     */
    public function order_data()
    {
        $this->load->model("model/admin/model_admin");
        return $this->model_admin->admin_order_data();
    }
    /**
     * 搜索订单
     * @param
     * @return boolean
     * @author shangshikai@nahao.com
     */
    public function sea_order($post)
    {
        $this->load->model("model/admin/model_admin");
        return $this->model_admin->sea_order_list($post);
    }
    /**
     * 复合搜索条件订单的总数
     * @param
     * @return boolean
     * @author shangshikai@nahao.com
     */
    public function search_order_count($post)
    {

        $this->load->model("model/admin/model_admin");
        return $this->model_admin->sea_order_count($post);
    }
}