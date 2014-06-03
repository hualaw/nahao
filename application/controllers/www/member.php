<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/student/student_member');
        $this->load->model('model/student/model_member');
    }

    
    /**
     * 我的课程
     */
	public function my_course()
	{  
        header('content-type: text/html; charset=utf-8');
        $int_user_id = 1;                                                    #TODO用户id
        $str_avater = $this->model_member->get_user_avater($int_user_id);
        #我买的课程
        $this->student_member->get_my_course($int_user_id);
        $this->smarty->assign('str_avater', $str_avater);
        $this->smarty->display('www/studentMyCourse/myCourse.html');
	}
	
	/**
	 * 我的订单
	 */
	public function my_order()
	{
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