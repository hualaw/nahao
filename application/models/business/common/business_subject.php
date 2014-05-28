<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Business_Subject
 * @author yanrui@tizi.com
 */
class Business_Subject extends NH_Model {

    function __construct(){
        parent::__construct();
        $this->load->model('model/common/model_subject');
    }

    /**
     * 获取全部学科
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_subjects(){
        $str_table_range = 'subject';
        $str_result_type = 'list';
        $str_fields = 'id,name';
        $arr_return = $this->model_subject->get_subject_by_param($str_table_range, $str_result_type, $str_fields);
        return $arr_return;
    }
}