<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Business_Course_Type
 * @author yanrui@tizi.com
 */
class Business_Course_Type extends NH_Model {

    function __construct(){
        parent::__construct();
        $this->load->model('model/common/model_course_type');
    }

    /**
     * 获取全部课程类型
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_course_types(){
        $str_table_range = 'course_type';
        $str_result_type = 'list';
        $str_fields = 'id,name';
        $arr_return = $this->model_course_type->get_course_type_by_param($str_table_range, $str_result_type, $str_fields);
        return $arr_return;
    }

    /**
     * 拿到id为键name为值的course_types数组
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_course_types_like_kv(){
        $arr_course_types = self::get_course_types();
        $arr_return = self::converse_array_to_kv($arr_course_types);
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