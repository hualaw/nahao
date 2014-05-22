<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course extends NH_Student_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/student/student_course');
    }

    /**
     * 从首页链接到课程购买前页面  获取一个课程下的所有轮
     */
	public function index()
	{
	    //header('content-type: text/html; charset=utf-8');
	    $course_id = intval($this->input->post('course_id'));
	    if (!$course_id)
	    {
	        show_error("参数错误");
	    }
        $data = $this->student_course->get_all_round_by_course_id();
        var_dump($data);die;
        //$this->load->view('www/signin');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */