<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends NH_User_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('business/teacher/business_teacher','teacher_b');
        $this->load->model('model/teacher/model_teacher','teacher_m');
    }
    
	/**
	 * 【老师端】老师获得练习题
	 */
	public function teacher_get_exercise_page()
	{
		
	}
	
	/**
	 * 【老师端】查看答题统计
	 */
	public function teacher_get_exercise_stat()
	{
		
	}
	
	/**
	 * 【老师端】显示被赞的数量/显示被点击讲快点数量/显示被点击讲慢点数量
	 * 　array(
	 *　		'please_total_count' => 11,
	 *　		'slower_total_count' => 12,
	 *　		'faster_total_count' => 13,
	 *　);
	 */
	public function count_classroom_student_action(){
		
	}
	
}