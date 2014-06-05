<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Business_Area
 * @author yanrui@tizi.com
 */
class Business_Area extends NH_Model {

    function __construct(){
        parent::__construct();
        $this->load->model('model/common/model_area');
    }

    /**
     * 获取全部省份
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_provinces(){
        $str_table_range = 'area';
        $str_result_type = 'list';
        $str_fields = 'id,name';
        $arr_where['level'] = 1;
        $arr_return = $this->model_area->get_area_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        return $arr_return;
    }

    /**
     * 根据ids获取areas
     * @param array $arr_area_ids
     * @return mixed
     * @author yanrui@tizi.com
     */
    public function get_areas_by_ids($arr_area_ids){
        $arr_return = array();
        if(is_array($arr_area_ids) AND $arr_area_ids){
            $str_table_range = 'area';
            $str_result_type = 'list';
            $str_fields = 'id,name';
            $arr_where = array('id' => $arr_area_ids);
            $arr_return = $this->model_area->get_area_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }

    /**
     * 根据ids拿到id为键name为值的areas数组
     * @param array $arr_area_ids
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_areas_by_ids_like_kv($arr_area_ids){
        $arr_return = array();
        if(is_array($arr_area_ids) AND $arr_area_ids){
            $arr_areas = self::get_areas_by_ids($arr_area_ids);
            $arr_return = self::converse_array_to_kv($arr_areas);
        }
        return $arr_return;
    }

    /**
     * 把普通数组转换为id为键name为值的数组
     * @param $arr_param
     * @return array
     * @author yanrui@tizi.com
     */
    public function converse_array_to_kv($arr_param){
        $arr_return = array();
        if(is_array($arr_param) AND $arr_param){
            foreach($arr_param as $k => $v){
                $arr_return[$v['id']] = $v['name'];
            }
        }
        return $arr_return;
    }
}