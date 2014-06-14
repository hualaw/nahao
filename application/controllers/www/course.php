<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/student/student_course');
        $this->load->model('business/student/student_order');
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
        //var_dump($array_team);die;
        
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
	    if(!$this->is_login)
	    {
	        redirect('/login');
	    }
	    #用户id
	    $int_user_id = $this->session->userdata('user_id');                #TODO用户id
	    #轮id
	    $int_round_id = intval($int_round_id);
	    #检查$round_id以及学生是否购买此轮
	    $bool_flag = $this->student_course->check_student_buy_round($int_user_id,$int_round_id);
	    if (!$bool_flag)
	    {
	        show_error("参数错误");
	    }

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
	    #每节课是否有评价
	    
	    //var_dump($array_outline);die;

	    $this->smarty->assign('array_classmate', $array_classmate);
	    $this->smarty->assign('int_classmates', $int_classmates);
	    $this->smarty->assign('array_note', $array_note);
	    $this->smarty->assign('array_outline', $array_outline);
	    $this->smarty->assign('array_data', $array_data);
	    $this->smarty->display('www/studentMyCourse/buyAfter.html');
	}
	
	/**
	 * 购买后 查看笔记
	 */
	public function get_user_cloud_notes()
	{
	    #判断是否登录
	    if(!$this->is_login)
	    {
	        redirect('/login');
	    }
	    #用户id
	    $int_user_id = $this->session->userdata('user_id');                #TODO用户id
	    #教室id
	    $int_classroom_id = intval($this->input->post('cid'));
	    if (empty($int_classroom_id))
	    {
	        self::json_output(array('status'=>'error','msg'=>'参数错误'));
	    }
	    #根据教室id查找云笔记
	    $array_result = $this->student_course->get_user_cloud_notes($int_classroom_id,$int_user_id);
	    if ($array_result)
	    {
	        self::json_output(array('status'=>'ok','data'=>array('content'=>$array_result['content'],
	              'class_title'=>$array_result['class_title'])));
	    } else {
	        self::json_output(array('status'=>'error','msg'=>'获取云笔记数据出错'));
	    }
	}
	
	/**
	 * 为课点评
	 */
	public function class_comment()
	{
	    #判断是否登录
	    if(!$this->is_login)
	    {
	        self::json_output(array('status'=>'no_login','msg'=>'您还未登陆，请先登录',));
	    }
	    
	    $int_user_id = $this->session->userdata('user_id');#TODOuser_id
	    $int_class_id = $this->input->post("class_id");
	    
	    if (empty($int_class_id))
	    {
	        self::json_output(array('status'=>'error','msg'=>'参数错误'));
	    }
	    #根据class_id获取课的信息
	    $array_result = $this->student_course->get_class_infor($int_class_id);
	    if (empty($array_result))
	    {
	        self::json_output(array('status'=>'error','msg'=>'根据课id获取课信息出错'));
	    }
	    
	    $int_score  = $this->input->post("score");
	    $str_content = $this->input->post("content");
	    $array_data = array(
	            'course_id'=>$array_result['course_id'],
	            'round_id'=>$array_result['round_id'],
	            'class_id'=>$int_class_id,
	            'student_id'=>$int_user_id,
	            'nickname'=>$this->session->userdata('nickname'),
	            'content'=>$str_content,
	            'score'=>  $int_score,
	            'create_time'=>time(),
	            'is_show'=>0
	            );
	    $bool_flag = $this->model_course->save_class_feedback($array_data);
	    if ($bool_flag)
	    {
	        self::json_output(array('status'=>'ok','msg'=>'提交意见反馈成功'));
	    } else {
	        self::json_output(array('status'=>'error','msg'=>'提交意见反馈失败'));
	    }
	 }
	
	/**
	 * 到核对订单信息页面前的AJAX判断，判断是否有买过该轮
	 */
	public function before_check_order()
	{
	    header('content-type: text/html; charset=utf-8');
	    #判断是否登录
	    if(!$this->is_login){
	        self::json_output(array('status'=>'no_login','msg'=>'您还未登陆，请先登录',));
	    }
	    $int_product_id = $this->input->post("product_id");
	    $int_product_id = max(intval($int_product_id),1);
	    #检查这个$int_product_id是否有效：在预售和销售中的轮
	    $bool_flag = $this->student_course->check_round_id($int_product_id);
	    if (!$bool_flag)
	    {
	        show_error("参数错误");
	    }
	    #如果购买的商品已经在订单表存在了，并且状态时已关闭和已取消，则该商品可以继续下单，否则提示它
	    $int_user_id = $this->session->userdata('user_id');                 #TODO
	    $array_result = $this->student_order->check_product_in_order($int_product_id,$int_user_id);

	    if(empty($array_result))
	    {
	        #根据$int_product_id获取订单里面该轮的部分信息
	        self::json_output(array('status'=>'ok','id'=>$int_product_id));
	    }

	    foreach ($array_result as $k=>$v)
	    {
    	    switch ($v['status'])
    	    {
    	        case 0:
    	        case 1:
    	            self::json_output(array('status'=>'error','msg'=>'您的订单已经存在，请去订单中心付款',));
    	            break;
    	        case 2:
    	        case 3:
    	        case 6:
    	        case 7:
    	        case 8:
    	        case 9:
    	            self::json_output(array('status'=>'error','msg'=>'您已经购买过这轮课程，请不要重复购买'));
    	            break;
    	        case 4:
    	        case 5:
    	            #根据$int_product_id获取订单里面该轮的部分信息
    	            self::json_output(array('status'=>'ok','id'=>$int_product_id));
    	            break;
	       }
        }

	 }
	 

	 

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */