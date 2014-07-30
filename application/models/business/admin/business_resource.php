<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Resource相关逻辑
 * Class Business_Resource
 * @author yanrui@tizi.com
 */
class Business_Resource extends NH_Model
{
    function __construct(){
        parent::__construct();
        $this->load->model('model/admin/model_resource');
    }

    /**
     * 创建resource
     * @param $arr_param
     * @return int
     * @author yanrui@tizi.com
     */
    public function create_resource($arr_param)
    {
        $int_return = 0;
        if($arr_param){
            $int_return = $this->model_resource->create_resource($arr_param);
        }
        return $int_return;
    }

    /**
     * 修改resource
     * @param array $arr_param
     * @param array $arr_where
     * @return bool
     * @author yanrui@tizi.com
     */
    public function update_resource($arr_param,$arr_where){
        $bool_flag = false;
        if($arr_param AND $arr_where){
            $bool_flag = $this->model_resource->update_resource($arr_param,$arr_where);
        }
        return $bool_flag;
    }

    /**
     * 根据条件获取resource count
     * @param $arr_where
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_resource_count($arr_where){
        $int_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'resource';
            $str_result_type = 'count';
            $str_fields = 'count(1) as count';
//            if(array_key_exists('username',$arr_where)){
//                $arr_where['like'][TABLE_ADMIN.'.username'] = $arr_where['username'];
//                unset($arr_where['username']);
//            }
            $int_return = $this->model_resource->get_resource_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $int_return;
    }

    /**
     * 根据条件获取resource count
     * @param $arr_where
     * @param $int_start
     * @param $int_limit
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_resource_list($arr_where,$int_start,$int_limit){
        $arr_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'resource';
            $str_result_type = 'list';
            $str_fields = 'id,admin_id,username,uri,create_time,status,type';
            $arr_limit = array(
                'start'=>$int_start,
                'limit' => $int_limit
            );
            $arr_order_by = array(
                'id' => 'desc'
            );
            $arr_return = $this->model_resource->get_resource_by_param($str_table_range, $str_result_type, $str_fields, $arr_where, array(),$arr_order_by, $arr_limit);
        }
        return $arr_return;
    }

    /**
     * 根据id取resource
     * @param $int_resource_id
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_resource_by_id($int_resource_id)
    {
        $arr_return = array();
        if($int_resource_id){
            $str_table_range = 'resource';
            $str_result_type = 'one';
            $str_fields = '*';
            $arr_where = array(
                'id' => $int_resource_id
            );
            $arr_return = $this->model_resource->get_resource_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }

}