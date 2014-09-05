<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Group相关逻辑
 * Class Business_Group
 * @author yanrui@tizicom
 */
class Business_Group extends NH_Model
{
    function __construct(){
        parent::__construct();
        $this->load->model('model/admin/model_group');
    }

    /**
     * 创建Group
     * @param $arr_param
     * @return int
     * @author yanrui@tizi.com
     */
    public function create_group($arr_param)
    {
        $int_return = 0;
        if($arr_param){
            $arr_param['status'] = 1;//默认启用
            $int_return = $this->model_group->create_group($arr_param);
        }
        return $int_return;
    }

    /**
     * 修改group
     * @param array $arr_param
     * @param array $arr_where
     * @return bool
     * @author yanrui@tizi.com
     */
    public function update_group($arr_param,$arr_where){
        $bool_flag = false;
        if($arr_param AND $arr_where){
            $bool_flag = $this->model_group->update_group($arr_param,$arr_where);
        }
        return $bool_flag;
    }

    /**
     * 根据条件获取group      count
     * @param $arr_where
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_group_count($arr_where){
        $int_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'group';
            $str_result_type = 'count';
            $str_fields = 'count(1) as count';
            if(array_key_exists('name',$arr_where)){
                $arr_where['like']['name'] = $arr_where['name'];
                unset($arr_where['name']);
            }
            $int_return = $this->model_group->get_group_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $int_return;
    }

    /**
     * 根据条件获取group list
     * @param $arr_where
     * @param $int_start
     * @param $int_limit
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_group_list($arr_where,$int_start,$int_limit){
        $arr_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'group';
            $str_result_type = 'list';
            $str_fields = 'id,name,status';
            if(array_key_exists('name',$arr_where)){
                $arr_where['like']['name'] = $arr_where['name'];
                unset($arr_where['name']);
            }
            $arr_limit = array(
                'start'=>$int_start,
                'limit' => $int_limit
            );
            $arr_return = $this->model_group->get_group_by_param($str_table_range, $str_result_type, $str_fields, $arr_where, array(), array(),$arr_limit);
        }
        return $arr_return;
    }

    /**
     * 根据id取group
     * @param $int_group_id
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_group_by_id($int_group_id)
    {
        $arr_return = array();
        if($int_group_id){
            $str_table_range = 'group';
            $str_result_type = 'one';
            $str_fields = '*';
            $arr_where = array(
                'id' => $int_group_id
            );
            $arr_return = $this->model_group->get_group_by_param($str_table_range,$str_result_type,$str_fields, $arr_where);
        }
        return $arr_return;
    }

    /**
     * 根据name取group
     * @param string $str_name
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_group_by_username($str_name)
    {
        $arr_return = array();
        if($str_name){
            $str_table_range = 'group';
            $str_result_type = 'one';
            $str_fields = 'id,name,status';
            $arr_where = array(
                'name' => $str_name
            );
//            echo $str_table_range.'--'.$str_result_type.'--'.$str_fields."\n";echo "where : \n";var_dump($arr_where);;exit;
            $arr_return = $this->model_group->get_group_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }

}