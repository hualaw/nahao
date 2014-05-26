<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/student/student_course');
    }

    /**
     * 从首页链接到课程购买前页面  获取一个课程下的所有轮
     */
	public function buy_before($int_round_id = 1)
	{
	    header('content-type: text/html; charset=utf-8');
	    $int_round_id = max(intval($int_round_id),1);
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
        //var_dump($array_outline);die;
        
        $this->smarty->assign('array_data', $array_data);
        $this->smarty->assign('array_outline', $array_outline);
        $this->smarty->assign('array_evaluate', $array_evaluate);
        $this->smarty->assign('array_team', $array_team);
        $this->smarty->assign('array_round', $array_round);
        $this->smarty->display('www/myCourse/buyBefore.html');
	}
	
	/**
	 * 购买后
	 */
	public function buy_after()
	{
	    $this->smarty->display('www/myCourse/buyAfter.html');
	}
	
	/**
	 * 我的课程--我买的轮
	 */
	public function index()
	{
	    $this->smarty->display('www/myCourse/index.html');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */