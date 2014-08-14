<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header('content-type: text/html; charset=utf-8');
class Member extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/student/student_member');
        $this->load->model('business/student/student_order');
        $this->load->model('business/student/student_index');
        $this->load->model('business/student/student_course');
        if(!$this->is_login)
        {
            redirect('/login');
        }
        $grades = $this->config->item('grade');
        $this->smarty->assign('grades', $grades);
    }

    
    /**
     * 我的课程
     */
	public function my_course($status = '')
	{  
        header('content-type: text/html; charset=utf-8');
        $int_user_id = $this->session->userdata('user_id');#TODO用户id
        #我购买的课程
        $array_buy_course = $this->student_member->get_my_course_for_buy($int_user_id,$status);
        //var_dump($array_buy_course);
        #最新课程
        $array_new = $this->student_index->get_course_latest_round_list();
		if($array_new)
		{
			#没有加nh_dbug参数 过滤掉测试轮
			$array_new =$this->student_index->filter_test_round($array_new);
		}
        #热报课程
        $course_hot = $this->student_index->get_course_hot();
        if($course_hot)
        {
            #没有加nh_dbug参数 过滤掉测试轮
            $array_hot =$this->student_index->filter_test_round($course_hot);
        }

        $array_new = array_slice($array_new,0,3,true);
        $course_url = config_item('course_url');
        $this->smarty->assign('course_url', $course_url);
        $this->smarty->assign('array_buy_course', $array_buy_course);
        $this->smarty->assign('array_new', $array_new);
        $this->smarty->assign('array_hot', $array_hot);
        $this->smarty->assign('page_type', 'myCourse');
        $this->smarty->display('www/studentMyCourse/index.html');
	}
	
	/**
	 * 我的订单
	 */
	public function my_order($str_type = 'all')
	{
	    header('content-type: text/html; charset=utf-8');
	    $int_user_id = $this->session->userdata('user_id');   #TODO用户id

	    $array_type = array('all','pay','nopay','cancel','refund');
	    if (!in_array($str_type, $array_type))
	    {
	        $str_type = 'all';
	    }
	    #分页
	    $this->load->library('pagination');
	    $int_count  = $this->student_member->get_order_count($int_user_id,$str_type);
	    $int_start = $this->uri->segment(4) ? $this->uri->segment(4) : 0;
	    $config = config_item('page_student');
	    $config['base_url'] = '/member/my_order/'.$str_type.'/';
	    $config['total_rows'] = $int_count;
	    $config['per_page'] = PER_PAGE_NO;
	    $config['use_page_numbers'] = false;
	    //$config['uri_segment'] = '4';//设为页面的参数，如果不添加这个参数分页用不了
	    
	    $this->pagination->initialize($config);
	    $show_page = $this->pagination->create_links();
	    #订单列表
	    $array_order_list = $this->student_member->get_order_list($int_user_id,$str_type,$int_start,PER_PAGE_NO);
	    //var_dump($array_order_list);die;
	    #订单总数
        $all_count  = $this->student_member->get_order_count($int_user_id,'all');
        $pay_count  = $this->student_member->get_order_count($int_user_id,'pay');
        $nopay_count  = $this->student_member->get_order_count($int_user_id,'nopay');
        $cancel_count  = $this->student_member->get_order_count($int_user_id,'cancel');
        $refund_count  = $this->student_member->get_order_count($int_user_id,'refund');

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
	    $int_user_id = $this->session->userdata('user_id');                                                    #TODO用户id
	    
	    #获取参数
	    $int_order_id = intval($int_order_id);
	    if ($int_order_id == '0')
	    {
	        show_error("参数错误");
	    }
	    #获取订单信息
	    $array_order = $this->student_order->get_order_by_id($int_order_id);
	    //var_dump($array_order);die;
	    if (empty($array_order)) #TODO用户id$this->user['id']
	    {
	        show_error("订单不存在");
	    }
	    if ($array_order['student_id']!=$int_user_id)
	    {
	    	show_error("不是本人的订单");
	    }
	    if ($array_order['pay_type'] == ORDER_TYPE_ONLINE || $array_order['pay_type'] == ORDER_TYPE_BANK || $array_order['pay_type'] == ORDER_TYPE_CREDITPAY || $array_order['pay_type'] == ORDER_TYPE_ALIPAY)
	    {
	        $array_order['payment_method'] = 'online';
	    } elseif ($array_order['pay_type'] == ORDER_TYPE_OFFLINE ) {
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
	    $int_user_id = $this->session->userdata('user_id');  #TODO用户id
	    $int_order_id = intval($int_order_id);
	    if ($int_order_id == '0')
	    {
	        show_error("参数错误");
	    }
	    #获取参数 1取消  2删除 3申请退课 4退课详情
	    if (!in_array($str_type, array(1,2,3,4)))
	    {
	        show_error("参数错误");
	    }
	    
	    #获取订单信息
	    $array_order = $this->student_order->get_order_by_id($int_order_id);
	    if (empty($array_order) OR $array_order['student_id']!=$int_user_id) #TODO用户id$this->user['id']
	    {
	        show_error("订单号不存在");
	    }
	    
	    #取消,并写日志
	    if ($str_type == '1')
	    {
	        $array_mdata = array(
                'user_id'=>$int_user_id,
                'order_id'=>$int_order_id,
                'status'=>ORDER_STATUS_CANCEL,
                'action'=>ORDER_ACTION_CANCEL,
                'note'=>'取消订单',
	        	'user_type'=>NH_MEETING_TYPE_STUDENT,
	        	'pay_type'=>$array_order['pay_type']
	        );
	        $bool_flag = $this->student_order->update_order_status($array_mdata);
	        if ($bool_flag)
	        {
	            self::json_output(array('status'=>'ok','msg'=>'取消操作成功'));
	        } else {
	            self::json_output(array('status'=>'error','msg'=>'取消操作失败'));
	        }
	    }
	    
	    #删除,并写日志(在已关闭和已取消下才能删除)
	    if ($str_type == '2')
	    {
	        if ($array_order['status'] == ORDER_STATUS_CLOSE || $array_order['status'] == ORDER_STATUS_CANCEL)
	        {
	            $array_ndata = array(
	                    'user_id'=>$int_user_id,
	                    'order_id'=>$int_order_id,
	                    'action'=>ORDER_ACTION_DELETE_ORDER,
	                    'note'=>'删除订单'
	            );
	            $bool_flag = $this->student_order->delete_order($array_ndata);
	            if ($bool_flag)
	            {
	                self::json_output(array('status'=>'ok','msg'=>'删除操作成功'));
	            } else {
	                self::json_output(array('status'=>'error','msg'=>'删除操作失败'));
	            }
	        } else {
	            self::json_output(array('status'=>'error','msg'=>'不能执行删除操作,只在已关闭下才能删除'));
	        }

	    }
	    
	    #申请退课
	    if ($str_type == '3')
	    {
	        #获取申请退课数据
	        $array_data = $this->student_member->get_apply_refund_data($int_user_id,$array_order);	        
	        //var_dump($array_data);die;
	        $array_user = $this->_user_detail;
	        //var_export($array_user);die;
	        $array_bank = config_item('bank');
	        $this->smarty->assign('array_bank', $array_bank);
	        $this->smarty->assign('array_user', $array_user);
	        $this->smarty->assign('array_data', $array_data);
	        $this->smarty->assign('int_order_id', $int_order_id);
	        $this->smarty->assign('page_type', 'apply_refund');
	        $this->smarty->display('www/studentMyCourse/index.html');
	    }
	    
	    #退课详情
	    if ($str_type == '4')
	    {
	        $array_data = $this->student_member->get_student_refund_data($int_user_id,$array_order);
	        $this->smarty->assign('array_data', $array_data);
	        $this->smarty->assign('page_type', 'refund_detail');
	        $this->smarty->display('www/studentMyCourse/index.html');
	    }
	}
	
	/**
	 * 添加退课申请
	 */
	public function save_refund()
	{
	    header('content-type: text/html; charset=utf-8');
	    #判读是否登录
	    $int_user_id = $this->session->userdata('user_id');                                               #TODO用户id
	    $int_order_id = intval($this->input->post('id'));
	    $str_reason = trim($this->input->post('reason'));
	    $str_bank = trim($this->input->post('bank'));
	    $str_bankbench = trim($this->input->post('bankbench'));
	    $str_bankcard = trim($this->input->post('bankcard'));
	    $str_id_code = trim($this->input->post('id_code'));
	    $str_totle_money = trim($this->input->post('totle_money'));
	    $str_return_money = trim($this->input->post('return_money'));
	    $str_unclass = trim($this->input->post('unclass'));
	    $str_class = trim($this->input->post('class'));
	    if ($int_order_id == '0')
	    {
	        show_error("参数错误");
	    }
	    #获取订单信息
	    $array_order = $this->student_order->get_order_by_id($int_order_id);
		if (empty($array_order)) #TODO用户id$this->user['id']
	    {
	        show_error("订单不存在");
	    }
	    if ($array_order['student_id']!=$int_user_id)
	    {
	    	show_error("不是本人的订单");
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
            'round_price'=>$str_totle_money,
	        'refund_price'=>$str_return_money,
	        'refund_count'=>$str_unclass,
	        'study_count'=>$str_class,
	    	'pay_type'=>$array_order['pay_type']
	    );
	    $sflag = $this->student_member->save_refund($array_data);
	    if ($sflag)
	    {
	        self::json_output(array('status'=>'ok','msg'=>'申请提交成功！我们会在48小时内处理您的申请，请耐心等待！'));
	    } else {
	        self::json_output(array('status'=>'error','msg'=>'申请提交失败'));
	    }
	}
	
	/**
	 * 我的基本资料
	 */
	public function my_infor()
	{
        $this->load->model('business/common/business_school');
        $this->load->model('business/common/business_subject','subject');
        $this->load->model('business/admin/business_lecture');
        $this->load->model('business/admin/business_teacher');
        $this->load->model('business/common/business_area');
	    $user_id = $this->session->userdata('user_id');
        $reg_type = $this->session->userdata('reg_type');
        if($this->is_post()) {
            $this->load->model('business/common/business_user');
            $post_data = array();
            $phone = trim($this->input->post('phone'));
            if($reg_type == REG_LOGIN_TYPE_EMAIL && $phone != $this->_user_detail['phone']) {
                $phone_mask = empty($phone) ? '' : phone_blur($phone);
                $phone_data['phone_mask'] = $phone;//邮箱注册的用户,phone_mask在邮箱是明文在redis中是加了掩码的
                $this->business_user->modify_user($phone_data, $user_id);
                $this->session->set_userdata('phone', $phone);
                $this->session->set_userdata('phone_mask', $phone_mask);
            }
            
            $post_data['nickname'] = trim($this->input->post('nickname'));
            if($post_data['nickname'] && $post_data['nickname'] != $this->_user_detail['nickname']) {
                #修改数据库和redis中的昵称
                $this->business_user->modify_user($post_data, $this->session->userdata('user_id'));
                $this->session->set_userdata('nickname', $post_data['nickname']);
            }
            $post_data['email'] = trim($this->input->post('email'));
            if($post_data['email'] && empty($this->_user_detail['email'])) {
                #如果之前没有设置过邮箱才可以修改
                $this->business_user->modify_user(array('email' => $post_data['email']), $this->session->userdata('user_id'));
                $this->session->set_userdata('email', $post_data['email']);
            }
            $post_data['realname'] = trim($this->input->post('realname'));
            $post_data['grade'] = intval($this->input->post('grade'));
            $post_data['gender'] = intval($this->input->post('gender'));
            $post_data['province'] = intval($this->input->post('province'));
            $post_data['city'] = intval($this->input->post('city'));
            $post_data['area'] = intval($this->input->post('area'));
            $post_data['student_subject'] = $this->input->post('selected_subjects');
            $post_data['school_id'] = intval($this->input->post('school_id'));
            $post_data['schoolname'] = trim($this->input->post('schoolname'));
            if($post_data['schoolname'] && empty($post_data['school_id'])) {
                #post过来的数据有学校名称但没学校ID, 这是用户自己输入的学校,需要把学校所属的地区也接收过来
                $post_data['province_id'] = intval($this->input->post('province_id'));
                $post_data['city_id'] = intval($this->input->post('city_id'));
                $post_data['county_id'] = intval($this->input->post('area_county_id'));
                $post_data['school_type'] = intval($this->input->post('school_type'));
                $post_data['custom_school'] = 1;
                $new_school_id = $this->business_school->add_custom_school($post_data);
                $post_data['school_id'] = intval($new_school_id);
            } else if($post_data['school_id'] && empty($post_data['schoolname'])) {
                #相反post过来的数据有学校id没学校名称,这是用户选择系统里的学校,把custom_school设成0,防止用户之前自定义过学校
                $post_data['custom_school'] = 0;
            }
            $result = $this->business_user->modify_user_info($post_data, $user_id);
            if($result) {
                $arr_return = array('status' => 'ok', 'msg' => '更新资料成功');
            } else {
                $arr_return = array('status' => 'error', 'msg' => '更新资料失败请稍后重试');
            }
            self::json_output($arr_return);
        }
        #性别
        $gender = $this->config->item('gender');
        #学科
        $subjects = $this->subject->get_subjects();
        #学校
        $my_school = $this->business_school->school_info($this->_user_detail['school'], 'schoolname,province_id,city_id,county_id', $this->_user_detail['custom_school']);
        $school_name = isset($my_school['schoolname']) ? $my_school['schoolname'] : '';
        array_shift($my_school);
        $school_area = $this->business_area->get_areas_by_ids($my_school);  
        #我已选择的学科组成的字符串
        $subject_str = implode('-', $this->_user_detail['student_subject']);
        #地区数据
        $province=$this->business_lecture->all_province();
        $city = $area = array();
        if($this->_user_detail['province']) {
            $city = $this->business_teacher->city1($this->_user_detail['province']);
        }        
        if($this->_user_detail['city']) {
            $area = $this->business_teacher->area1($this->_user_detail['city']);
        }
//         print_r($this->_userdata);
//         exit();
        $this->smarty->assign('gender', $gender);
        $this->smarty->assign('subjects', $subjects);
        $this->smarty->assign('subject_str', $subject_str);
        $this->smarty->assign('school_name', $school_name);
        $this->smarty->assign('school_area', $school_area);
	    $this->smarty->assign('page_type', 'myInfor');
        $this->smarty->assign('province', $province);
        $this->smarty->assign('area', $area);
        $this->smarty->assign('city', $city);
        $this->smarty->assign('special_city', array(2, 25, 27, 32));
        $this->smarty->assign('reg_type', 3);
	    $this->smarty->display('www/studentMyCourse/index.html');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */