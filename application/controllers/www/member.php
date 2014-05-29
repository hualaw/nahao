<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/student/student_member');
        $this->load->model('business/student/student_order');
        $this->load->model('business/student/student_index');
        $this->load->model('business/student/student_course');
    }

    
    /**
     * 我的课程
     */
	public function my_course()
	{  
        header('content-type: text/html; charset=utf-8');
        $int_user_id = 1;                                                    #TODO用户id
        #头像
        $str_avater = DEFAULT_AVATER;
        #我购买的课程
        $array_buy_course = $this->student_member->get_my_course_for_buy($int_user_id);
        #最新课程
        $array_new = $this->student_index->get_course_latest_round_list();
        $array_new = array_slice($array_new,0,3,true);
        $this->smarty->assign('str_avater', $str_avater);
        $this->smarty->assign('array_buy_course', $array_buy_course);
        $this->smarty->assign('array_new', $array_new);
        $this->smarty->assign('page_type', 'myCourse');
        $this->smarty->display('www/studentMyCourse/index.html');
	}
	
	/**
	 * 我的订单
	 */
	public function my_order($str_type = 'all')
	{
	    header('content-type: text/html; charset=utf-8');
	    $int_user_id = 1;                                                    #TODO用户id
        #头像
        $str_avater = DEFAULT_AVATER;

	    $array_type = array('all','pay','nopay','cancel','refund');
	    if (!in_array($str_type, $array_type))
	    {
	        $str_type = 'all';
	    }
	    #分页
	    $this->load->library('pagination');
	    $int_count  = $this->student_member->get_order_count($int_user_id,$str_type);
	    $int_start = $this->uri->segment(4) ? $this->uri->segment(4) : 0;
	    $config['base_url'] = '/member/my_order/'.$str_type.'/';
	    $config['total_rows'] = $int_count;
	    $config['per_page'] = PER_PAGE_NO;
	    $config['uri_segment'] = '4';//设为页面的参数，如果不添加这个参数分页用不了
	    $this->pagination->initialize($config);
	    $show_page = $this->pagination->create_links();
	    #订单列表
	    $array_order_list = $this->student_member->get_order_list($int_user_id,$str_type,$int_start,PER_PAGE_NO);
	    #订单总数
        $all_count  = $this->student_member->get_order_count($int_user_id,'all');
        $pay_count  = $this->student_member->get_order_count($int_user_id,'pay');
        $nopay_count  = $this->student_member->get_order_count($int_user_id,'nopay');
        $cancel_count  = $this->student_member->get_order_count($int_user_id,'cancel');
        $refund_count  = $this->student_member->get_order_count($int_user_id,'refund');
	    $this->smarty->assign('str_avater', $str_avater);
	    $this->smarty->assign('str_type', $str_type);
	    $this->smarty->assign('array_order_list', $array_order_list);
	    $this->smarty->assign('all_count', $all_count);
	    $this->smarty->assign('pay_count', $pay_count);
	    $this->smarty->assign('nopay_count', $nopay_count);
	    $this->smarty->assign('cancel_count', $cancel_count);
	    $this->smarty->assign('refund_count', $refund_count);
	    $this->smarty->assign('show_page', $show_page);
	    $this->smarty->assign('page_type', 'myOrder');
	    $this->smarty->display('www/studentMyCourse/index.html');
	}
	
	/**
	 * 订单详情
	 */
	public function order_detail($int_order_id)
	{
	    header('content-type: text/html; charset=utf-8');
	    $int_user_id = 1;                                                    #TODO用户id
        #头像
        $str_avater = DEFAULT_AVATER;
	    
	    #获取参数
	    $int_order_id = intval($int_order_id);
	    if ($int_order_id == '0')
	    {
	        show_error("参数错误");
	    }
	    #获取订单信息
	    $array_order = $this->student_order->get_order_by_id($int_order_id);
	    if (empty($array_order) OR $array_order['student_id']!=1) #TODO用户id$this->user['id']
	    {
	        show_error("订单号不存在");
	    }
	    if ($array_order['pay_type'] == '0'|| $array_order['pay_type'] == '1' ||$array_order['pay_type'] == '2'||$array_order['pay_type'] == '3')
	    {
	        $array_order['payment_method'] = 'online';
	    } elseif ($array_order['pay_type'] == '4') {
	        $array_order['payment_method'] = 'remittance';
	    }
	   
	    #根据订单里面的round_id获取轮的信息
	    $array_round = $this->student_course->get_round_info($array_order['round_id']);
	    if (empty($array_round))
	    {
	        show_error("订单信息不完整");
	    }
	    $this->smarty->assign('array_round', $array_round);
	    $this->smarty->assign('array_order', $array_order);
	    $this->smarty->assign('str_avater', $str_avater);
	    $this->smarty->assign('page_type', 'order_detail');
	    $this->smarty->display('www/studentMyCourse/index.html');
	    
	}
	
	/**
	 * 订单列表里面的操作
	 */
	public function action($int_order_id,$str_type)
	{
	    header('content-type: text/html; charset=utf-8');
	    #判断是否登录
	    $int_user_id = 1;                                                    #TODO用户id
	    #头像
	    $str_avater = DEFAULT_AVATER;
	    $int_order_id = intval($int_order_id);
	    if ($int_order_id == '0')
	    {
	        show_error("参数错误");
	    }
	    #获取参数 取消1  删除2 申请退课3 退课详情4
	    if (!in_array($str_type, array(1,2,3,4)))
	    {
	        show_error("参数错误");
	    }
	    
	    #获取订单信息
	    $array_order = $this->student_order->get_order_by_id($int_order_id);
	    if (empty($array_order) OR $array_order['student_id']!=1) #TODO用户id$this->user['id']
	    {
	        show_error("订单号不存在");
	    }
	    
	    #取消与删除
	    
	    #申请退课
	    if ($str_type == '3')
	    {
	        #获取申请退课数据
	        $array_data = $this->student_member->get_apply_refund_data($int_user_id,$array_order['round_id']);
	        //var_dump($array_data);die;
	        $array_bank = config_item('bank');
	        $this->smarty->assign('array_bank', $array_bank);
	        $this->smarty->assign('array_data', $array_data);
	        $this->smarty->assign('page_type', 'apply_refund');
	        $this->smarty->assign('str_avater', $str_avater);
	        $this->smarty->assign('int_order_id', $int_order_id);
	        $this->smarty->display('www/studentMyCourse/index.html');
	    }
	    

	}
	
	/**
	 * 添加退课申请
	 */
	public function save_refund()
	{
	    #判读是否登录
	    $int_user_id = 1;                                                    #TODO用户id
	    $int_order_id = intval($this->input->post('id'));
	    $str_reason = trim($this->input->post('reason'));
	    $str_bank = trim($this->input->post('bank'));
	    $str_bankbench = trim($this->input->post('bankbench'));
	    $str_bankcard = trim($this->input->post('bankcard'));
	    $str_id_code = trim($this->input->post('id_code'));
	    $str_totle_money = trim($this->input->post('totle_money'));
	    $str_unclass = trim($this->input->post('unclass'));
	    if ($int_order_id == '0')
	    {
	        show_error("参数错误");
	    }
	    #获取订单信息
	    $array_order = $this->student_order->get_order_by_id($int_order_id);
	    if (empty($array_order) OR $array_order['student_id']!=1) #TODO用户id$this->user['id']
	    {
	        show_error("订单号不存在");
	    }
	    
	    $array_data = array(
	        'student_id'=>$int_user_id,                        #TODO用户id
	        'order_id'=>$int_order_id,
	        'round_id'=>$array_order['round_id'],
            'reason'=>$str_reason,
            'bankname'=>$str_bank,
	        'bankbench'=>$str_bankbench,
            'bankcard'=>$str_bankcard,
            'id_code'=>$str_id_code,
            'amount'=>$str_totle_money,
	        'times'=>$str_unclass,
	    );
	    $this->student_member->save_refund($array_data);
	}
	
	/**
	 * 我的基本资料
	 */
	public function my_infor()
	{
	    header('content-type: text/html; charset=utf-8');
	    $int_user_id = 1;                                                    #TODO用户id
	    #头像
	    $str_avater = DEFAULT_AVATER;
	    $this->smarty->assign('str_avater', $str_avater);
	    $this->smarty->assign('page_type', 'myInfor');
	    $this->smarty->display('www/studentMyCourse/index.html');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */