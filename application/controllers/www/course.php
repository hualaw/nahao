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
	    header('content-type: text/html; charset=utf-8');
	    $int_round_id = intval($this->input->get('round_id'));
	    #给$int_round_id一个默认值
	    $int_round_id = empty($int_round_id) ? 1 : $int_round_id;
	    #检查这个$int_round_id是否有效
	    $bool_flag = $this->student_course->check_round_id($int_round_id);
	    if (!$bool_flag)
	    {
	        show_error("参数错误");
	    }
	    #根据$int_round_id获取该轮的部分信息
	    $array_data = $this->student_course->get_round_info($int_round_id);
	    #根据$int_round_id获取该轮的课程大纲
        $array_outline = $this->student_course->get_round_outline($int_round_id);
        #根据$int_round_id获取该轮的课程评价
        $array_evaluate = $this->student_course->get_round_evaluate($int_round_id);
        #根据$int_round_id获取该轮的课程团队
        $array_team = $this->student_course->get_round_team($int_round_id);
        #根据$int_round_id获取对应课程下的所有轮
        $array_round = $this->student_course->get_all_round_under_course($int_round_id);
        var_dump($array_data);die;
        
        $this->smarty->assign('array_data', $array_data);
        $this->smarty->assign('array_outline', $array_outline);
        $this->smarty->assign('array_evaluate', $array_evaluate);
        $this->smarty->assign('array_team', $array_team);
        $this->smarty->assign('array_round', $array_round);
        $this->smarty->display('student/xx');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */