<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Studnet相关逻辑
 * Class Business_Student
 * @author yanrui@91waijiao.com
 */
class Business_Student extends NH_Model{

    public function get_student(){
        $arr_result = array();
        $this->load->model('model/student/model_student','model_student');
        $arr_result = $this->model_student->get_student();
        return $arr_result;
    }
}