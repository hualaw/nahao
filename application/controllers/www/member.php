<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/student/student_member');
        $this->load->model('model/student/model_member');
        $this->load->model('business/student/student_index');
    }

    
    /**
     * 我的课程
     */
	public function my_course()
	{  
        header('content-type: text/html; charset=utf-8');
        $int_user_id = 1;                                                    #TODO用户id
        #头像
        $str_avater = $this->model_member->get_user_avater($int_user_id);
        #我购买的课程
        $array_buy_course = $this->student_member->get_my_course_for_buy($int_user_id);
        #最新课程
        $array_new = $this->student_index->get_course_latest_round_list();
        $array_new = array_slice($array_new,0,3,true);
        $this->smarty->assign('str_avater', $str_avater);
        $this->smarty->assign('array_buy_course', $array_buy_course);
        $this->smarty->assign('array_new', $array_new);
        $this->smarty->display('www/studentMyCourse/myCourse.html');
	}
	
	/**
	 * 我的订单
	 */
	public function my_order($str_type = 'all')
	{
	    header('content-type: text/html; charset=utf-8');
	    $int_user_id = 1;                                                    #TODO用户id
	    #头像
	    $str_avater = $this->model_member->get_user_avater($int_user_id);
	    #订单列表
	    $array_type = array('all','pay','nopay','cancel','refund');
	    if (!in_array($str_type, $array_type))
	    {
	        $str_type = 'all';
	    }
	    $array_order_list = $this->model_member->get_order_list($int_user_id,$str_type);
	    var_dump($array_order_list);
	    $this->smarty->assign('str_avater', $str_avater);
	    $this->smarty->assign('str_type', $str_type);
	    $this->smarty->assign('array_order_list', $array_order_list);
	    $this->smarty->display('www/studentMyCourse/myOrder.html');
	}
	
	/**
	 * 我的基本资料
	 */
	public function my_infor()
	{
	    $this->smarty->display('www/studentMyCourse/myInfor.html');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */