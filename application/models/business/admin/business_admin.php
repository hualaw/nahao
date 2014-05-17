<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Admin相关逻辑
 * Class Business_Admin
 * @author yanrui@91waijiao.com
 */
class Business_Admin extends NH_Model
{

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
        }
        return $int_return;
    }

    public function get_admin_by_id()
    {

    }

    /**
     * 全功能管理员查询方法 可配置查询条件、字段、完整度 被本类中其他函数调用
     * @param string $str_type :  完整程度 default='*'
     * @param string $str_field : 所有字段 default='*'
     * @param array $arr_condition : 参数数组 参数值如为数组 启用wherein 否则where
     * @param array $arr_limit
     * @return array
     * @author yanrui@91waijiao.com
     */
    protected function _get_admin($str_type='base',$str_field = '*', $arr_condition = array(), $arr_limit = array())
    {
        if (!is_array($arr_condition)) {
            return false;
        }
        if($str_type=='admin'){
            $this->db->from(TABLE_ADMIN);
        }elseif($str_type=='permission'){
            $this->db->from(TABLE_ADMIN_PERMISSION_RELATION)->join(TABLE_PERMISSION,TABLE_ADMIN_PERMISSION_RELATION.'.permission_id='.TABLE_PERMISSION.'.id');
        }elseif($str_type=='admin_and_permission'){
            $this->db->from(TABLE_ADMIN)->join(TABLE_ADMIN_PERMISSION_RELATION,TABLE_ADMIN.'.id='.TABLE_ADMIN_PERMISSION_RELATION.'.admin_id','left')->join(TABLE_ADMIN_GROUP,TABLE_ADMIN_PERMISSION_RELATION.'.group_id='.TABLE_ADMIN_GROUP.'.id','left')->group_by(TABLE_ADMIN.'.id');
        }
        $this->db->select($str_field);
        if (!empty($arr_condition)) {
            foreach ($arr_condition as $k => $v) {
                if (is_array($v)) {
                    $this->db->where_in($k, $v);
                } else {
                    $this->db->where($k, $v);
                }
            }
        }
        if (!empty($arr_limit)) {
            $this->db->limit($arr_limit['limit'], $arr_limit['start']);
        }
        $res = $this->db->get()->result_array();
        return $res;
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

    public function order_list()
    {
        $this->load->model("model/admin/model_admin","admin_order");
        return $this->admin_order->admin_order_list();
    }
}