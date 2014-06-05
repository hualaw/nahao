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
        #获取评价总数
        $str_evaluate_count = $this->student_course->get_evaluate_count($int_round_id);
        //var_dump($array_team);
        
        $this->smarty->assign('array_data', $array_data);
        $this->smarty->assign('array_outline', $array_outline);
        $this->smarty->assign('array_evaluate', $array_evaluate);
        $this->smarty->assign('array_team', $array_team);
        $this->smarty->assign('array_round', $array_round);
        $this->smarty->assign('str_evaluate_count', $str_evaluate_count);
        $this->smarty->display('www/studentMyCourse/buyBefore.html');
	}
	
	/**
	 * 购买后
	 */
	public function buy_after($int_round_id)
	{
	    header('content-type: text/html; charset=utf-8');
	    #判断是否登录
	    // 	    if(! $this->user){
	    // 	        redirect('/login');
	    // 	    }
	    #用户id
	    $int_user_id = 1;                #TODO用户id
	    #轮id
	    $int_round_id = intval($int_round_id);
	    #检查$round_id以及学生是否购买此轮
/*	    $bool_flag = $this->student_course->check_student_buy_round($int_user_id,$int_round_id);
	    if (!$bool_flag)
	    {
	        show_error("参数错误");
	    }*/
	    #课堂同学
	    $array_classmate = $this->student_course->get_classmate_data($int_round_id);
	    #课堂同学总数
	    $int_classmates = count($array_classmate);
	    #课程公告
	    $array_note = $this->student_course->get_class_note_data($int_round_id);
	    #课程大纲
	    $array_outline = $this->student_course->get_round_outline($int_round_id);
	    #即将上课的信息--购买后顶部
	    $array_data = $this->student_course->get_soon_class_data($int_user_id,$int_round_id);
	    //var_dump($array_classmate);

	    $this->smarty->assign('array_classmate', $array_classmate);
	    $this->smarty->assign('int_classmates', $int_classmates);
	    $this->smarty->assign('array_note', $array_note);
	    $this->smarty->assign('array_outline', $array_outline);
	    $this->smarty->assign('array_data', $array_data);
	    $this->smarty->display('www/studentMyCourse/buyAfter.html');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */