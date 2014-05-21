<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Studnet相关逻辑
 * Class Business_Student
 * @author yanrui@91waijiao.com
 */
class Student_Index extends NH_Model{
    
    function __construct(){
        parent::__construct();
        $this->load->model('model/student/model_index');
    }
    
    /**
     * 首页获取轮的列表信息
     */
    public function get_round_list()
    {
        #首页获取一门课程里面最新的一轮（在审核通过和销售中）
        $arr_round = $this->model_index->get_course_late_round();
        $arr_return = array();
        if ($arr_round)
        {
            #首页获取轮的信息列表
            $arr_return = $this->model_index->get_round_list($arr_round);
        }
        return $arr_return;
    }
}