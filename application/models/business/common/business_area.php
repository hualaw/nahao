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
}