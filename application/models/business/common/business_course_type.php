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
}