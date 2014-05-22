<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Studnet相关逻辑
 * Class Business_Student
 * @author yanrui@91waijiao.com
 */
class Student_Course extends NH_Model{
    
    function __construct(){
        parent::__construct();
        $this->load->model('model/student/model_course');
    }
    
    /**
     * 从首页链接到课程购买前页面  获取一个课程下的所有轮（在审核通过和销售中）
     */
    public function get_all_round_by_course_id()
    {
        
    }
}