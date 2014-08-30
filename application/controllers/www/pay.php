<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header('content-type: text/html; charset=utf-8');
class Pay extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/student/student_order');
        $this->load->model('business/student/student_course');
        $this->load->model('model/student/model_order');
        $this->load->model('model/student/model_member');
        $this->load->model('business/common/business_register');
        $this->load->model('business/common/business_user');
    }
	
	/**
	 * 点击立即购买到核对订单信息页面
	 * @param $int_product_id '就是轮id'
	 */
	public function product($int_product_id = 1)
	{
		#判断是否登录
		if(!$this->is_login){
			redirect('/login');
		}
	    $int_product_id = max(intval($int_product_id),1);
	    #检查这个$int_round_id是否存在
	    $bool_flag = $this->student_course->check_round_id_is_exist($int_product_id);
	    if (!$bool_flag){
	    	show_error("参数错误");
	    }
	    #检查这个$int_product_id是否有效：销售中的轮
	    $bool_nflag = $this->student_course->check_round_id($int_product_id);
	    if (!$bool_nflag){
	        show_error("该轮不在销售中了！");
	    }
	    #购买前加入没有名额的判断
	    $array_round = $this->model_course->get_round_info($int_product_id);
	    #购买前加入是否售罄、已停售、已下架
	    if ($array_round['bought_count'] == $array_round['caps']){
	    	show_error("这轮已售罄了");
	    }
	    if ($array_round['sale_status'] == ROUND_SALE_STATUS_FINISH ){
	    	show_error("这轮已停售了");
	    }
	    if ($array_round['sale_status'] == ROUND_SALE_STATUS_OFF ){
	    	show_error("这轮已下架了");
	    }
	    #如果购买的商品已经在订单表存在了，并且状态时已关闭和已取消，则该商品可以继续下单，否则提示它
	    $int_user_id = $this->session->userdata('user_id');
	    $array_result = $this->student_order->check_product_in_order($int_product_id,$int_user_id);
	    if(empty($array_result)){
	    	#根据$int_product_id获取订单里面该轮的部分信息
	    	$array_data = $this->student_order->get_order_round_info($int_product_id);
	    }
	    
		foreach ($array_result as $k=>$v)
	    {
	        switch ($v['status'])
	        {
	            case ORDER_STATUS_INIT:
	            case ORDER_STATUS_FAIL:
	                show_error('您的订单已经存在，请去订单中心付款');
	                break;
	            case ORDER_STATUS_SUCC:
                case ORDER_STATUS_FINISH:
                case ORDER_STATUS_APPLYREFUND:
                case ORDER_STATUS_APPLYREFUND_FAIL:
                case ORDER_STATUS_APPLYREFUND_AGREE:
                case ORDER_STATUS_APPLYREFUND_SUCC:
                     show_error('您已经购买过这轮课程，请不要重复购买');
                     break;
                case ORDER_STATUS_CANCEL:
                case ORDER_STATUS_CLOSE:
                     #根据$int_product_id获取订单里面该轮的部分信息
                     $array_data = $this->student_order->get_order_round_info($int_product_id);
                     break;                                    
	        }
	    }
	    
	    $array_infor = $this->_user_detail;
	    $this->smarty->assign('realname', $array_infor['realname']);
	    $this->smarty->assign('phone_verified', $array_infor['phone_verified']);
	    $this->smarty->assign('array_data', $array_data);
	    $this->smarty->display('www/studentCart/infoCheck.html');
	}
	
	/**
	 * 确认订单页面里面的添加联系方式
	 */
	public function add_contact()
	{
    	#ajax判断是否登录
    	if(!$this->is_login){
    	    self::json_output(array('status'=>'no_login'));
    	}
    	$int_user_id   = $this->session->userdata('user_id');
    	$str_real_name = trim($this->input->post("real_name"));
    	$str_phone     = $this->input->post("phone");
    	$str_verify_code = $this->input->post("verify_code");
    	$int_code_type   = 2;
    	$int_product_id  = $this->input->post("pid");
		$str_class_ids   = $this->input->post("cids");
    	#如果用户注册的时候填了手机号，就不用验证码了
    	if (!empty($str_phone) && $str_phone != $this->session->userdata('phone')){
        	$exists = $this->business_register->_check_captcha($str_phone, $str_verify_code, $int_code_type);
        	#若验证码不匹配
        	if (!$exists){
        	    self::json_output(array('status'=>'error','msg'=>'验证码不正确'));
        	}
    	}
    	#检查这个$int_round_id是否存在
    	$bool_flag = $this->student_course->check_round_id_is_exist($int_product_id);
    	if (!$bool_flag){
    		self::json_output(array('status'=>'error','msg'=>'参数错误'));
    	}
    	#检查这个$int_product_id是否有效：（在销售中）
    	$bool_flag = $this->student_course->check_round_id($int_product_id);
    	if (!$bool_flag){
    		self::json_output(array('status'=>'error','msg'=>'该轮不在销售中了！'));
    	}
    	#获取用户手机号
    	$phone = get_pnum_phone_server($int_user_id);
    	#获取用户真实姓名
    	$array_user = $this->_user_detail;
    	 
    	#检查用户是否已经买了该轮
    	$array_result = $this->model_order->check_product_in_order($int_product_id,$int_user_id);
    	$arrat_status = array(
    			ORDER_STATUS_SUCC,
    			ORDER_STATUS_FINISH,
    			ORDER_STATUS_APPLYREFUND,
    			ORDER_STATUS_APPLYREFUND_FAIL,
    			ORDER_STATUS_APPLYREFUND_AGREE,
    			ORDER_STATUS_APPLYREFUND_SUCC
    	);
    	if ($array_result && (in_array($array_result[0]['status'],$arrat_status))){
    		self::json_output(array('status'=>'been_buy','msg'=>'您已经买过该轮了,请不要重复下单'));
    	}
    	if ($array_user['realname'])
    	{
			if (empty($phone))
			{
				$pflag = add_user_phone_server($int_user_id,$str_phone);
				$uflag = $this->model_member->update_user(phone_blur($str_phone),$int_user_id);
				if (empty($pflag)){
					self::json_output(array('status'=>'error','msg'=>'联系方式保存出错，无法提交订单','code'=>1));
				}
				if (empty($uflag)){
					self::json_output(array('status'=>'error','msg'=>'联系方式保存出错，无法提交订单','code'=>2));
				}

				#跟新session缓存
				$userdata = array(
					'user_id' => $int_user_id,
					'phone' => $str_phone,
					'phone_mask' => phone_blur($str_phone)
				);
				$this->session->set_userdata($userdata);
				$this->check_buy_class_infor($int_product_id,$str_class_ids);
				self::json_output(array('status'=>'ok','data'=>array('product_id'=>$int_product_id)));

			}
            	         
			if($phone!= $str_phone)
			{
				$pflag = change_pnum_phone_server($int_user_id,$str_phone);
				$uflag = $this->model_member->update_user(phone_blur($str_phone),$int_user_id);
				if (empty($pflag)){
					self::json_output(array('status'=>'error','msg'=>'联系方式保存出错，无法提交订单','code'=>3));
				}
				if (empty($uflag)){
					self::json_output(array('status'=>'error','msg'=>'联系方式保存出错，无法提交订单','code'=>4));
				}

				#跟新session缓存
				$userdata = array(
					'user_id' => $int_user_id,
					'phone' => $str_phone,
					'phone_mask' => phone_blur($str_phone)
				);
				$this->session->set_userdata($userdata);
				$this->check_buy_class_infor($int_product_id,$str_class_ids);
				self::json_output(array('status'=>'ok','data'=>array('product_id'=>$int_product_id)));

			}
					 
			if($phone== $str_phone)
			{
				$this->check_buy_class_infor($int_product_id,$str_class_ids);
				self::json_output(array('status'=>'ok','data'=>array('product_id'=>$int_product_id)));
			}
	
        } else { 
			if (empty($phone))
			{
				$pflag = add_user_phone_server($int_user_id,$str_phone);
				$uflag = $this->model_member->update_user(phone_blur($str_phone),$int_user_id);
				$uiflag = $this->model_member->update_user_info($str_real_name,$int_user_id);
				
				if (empty($pflag)){
					self::json_output(array('status'=>'error','msg'=>'联系方式保存出错，无法提交订单','code'=>5));
				}
				if (empty($uflag)){
					self::json_output(array('status'=>'error','msg'=>'联系方式保存出错，无法提交订单','code'=>6));
				}
				if (empty($uiflag)){
					self::json_output(array('status'=>'error','msg'=>'联系方式保存出错，无法提交订单','code'=>7));
				}

				#跟新session缓存
				$userdata = array(
					'user_id' => $int_user_id,
					'phone' => $str_phone,
					'phone_mask' => phone_blur($str_phone)
				);
				$this->session->set_userdata($userdata);
				$this->check_buy_class_infor($int_product_id,$str_class_ids);
				self::json_output(array('status'=>'ok','data'=>array('product_id'=>$int_product_id)));

			}
	
			if ($phone != $str_phone)
			{
				$pflag = change_pnum_phone_server($int_user_id,$str_phone);
				$uflag = $this->model_member->update_user(phone_blur($str_phone),$int_user_id);
				$uiflag = $this->model_member->update_user_info($str_real_name,$int_user_id);
				if (empty($pflag)){
					self::json_output(array('status'=>'error','msg'=>'联系方式保存出错，无法提交订单','code'=>8));
				}
				if (empty($uflag)){
					self::json_output(array('status'=>'error','msg'=>'联系方式保存出错，无法提交订单','code'=>9));
				}
				if (empty($uiflag)){
					self::json_output(array('status'=>'error','msg'=>'联系方式保存出错，无法提交订单','code'=>10));
				}

				#跟新session缓存
				$userdata = array(
					'user_id' => $int_user_id,
					'phone' => $str_phone,
					'phone_mask' => phone_blur($str_phone)
				);
				$this->session->set_userdata($userdata);
				$this->check_buy_class_infor($int_product_id,$str_class_ids);
				self::json_output(array('status'=>'ok','data'=>array('product_id'=>$int_product_id)));

			}
	 
			if($phone == $str_phone)
			{
				$uiflag = $this->model_member->update_user_info($str_real_name,$int_user_id);
				if (empty($uiflag)){
					self::json_output(array('status'=>'error','msg'=>'联系方式保存出错，无法提交订单','code'=>11));
				}
				$this->check_buy_class_infor($int_product_id,$str_class_ids);
				self::json_output(array('status'=>'ok','data'=>array('product_id'=>$int_product_id)));
			}
    	}
	}
	
	/*
	*	检查购买的课节是否发生变化
	*/
	public function check_buy_class_infor($int_product_id,$str_class_ids)
	{
		#如果长时间在订单确认页面 没有提交，则需要检查可买的课
		$array_class_ids = explode(',', $str_class_ids);				
		$array_list  = array();
		$array_allow_buy_class = $this->model_order->get_allow_buy_class($int_product_id);
		$error_msg = "课程里面可购买节有变化，点击“确定” 本页面会被刷新，请确定订单详情，再次提交订单！谢谢！";
		if (empty($array_allow_buy_class)){
			self::json_output(array('status'=>'nerror','error_code'=>'1','msg'=>$error_msg));
		}
		foreach ($array_allow_buy_class as $kkk=>$vvv){
			$array_list[] = $vvv['id'];
		}
		$class_ids_count = count($array_class_ids);
		$allow_buy_class_count = count($array_list);
		if ($class_ids_count != $allow_buy_class_count){
			self::json_output(array('status'=>'nerror','error_code'=>'2','msg'=>$error_msg));
		}
		$array_diff = array_diff($array_class_ids,$array_list);
		if(!empty($array_diff)){
			self::json_output(array('status'=>'nerror','error_code'=>'3','msg'=>$error_msg));
		}
	}
	
	
	/**
	 * 创建新订单
	 * @param  $product_id
	 */
	public function neworder($product_id = 1)
	{
	    #判断是否登录
	    if(!$this->is_login){
	     	redirect('/login');
	    }
	    $int_product_id = max(intval($product_id),1);
		#检查这个$int_round_id是否存在
    	$bool_flag = $this->student_course->check_round_id_is_exist($int_product_id);
    	if (!$bool_flag){
    		show_error('参数错误');
    	}
    	#检查这个$int_product_id是否有效：（在销售中）
    	$bool_flag = $this->student_course->check_round_id($int_product_id);
    	if (!$bool_flag){
    		show_error('该轮不在销售中了！');
    	}
    	$array_list  = array();
    	$array_allow_buy_class_infor = $this->model_order->get_allow_buy_class($int_product_id);
    	$error_msg = "课程里面没有可购买的课节，点击“确定” 本页面会被刷新，请确定订单详情，再次提交订单！谢谢！";
    	if (empty($array_allow_buy_class_infor)){
    		show_error($error_msg);
    	}
	    $int_user_id = $this->session->userdata('user_id');
	    #检查该用户是否已经下单且未付款
	    $array_return = $this->model_order->check_have_order($int_product_id,$int_user_id);
	    if ($array_return)
	    {
	        $int_order_id = $array_return[0]['id'];
	        if ($array_return[0]['pay_type'] == ORDER_TYPE_OFFLINE){
	            $payment_method = 'remittance';
	        } else {
	            $payment_method = 'online';
	        }
	       
	    } else {
	        #线上或者线下支付
	        #$payment_method = $this->input->post('payment_method');
	        $payment_method = 'online';
	        $payment_method = $payment_method == 'online' ? 'online':'remittance';
	        #创建订单,向数据库里面写一条记录，写一条订单日志记录
	        $array_result = $this->student_order->create_order($int_product_id,$payment_method,$int_user_id);
	        if (!$array_result['status']){
	            show_error("创建订单失败");
	        }
	        #向数据库生成订单成功之后，将订单信息写到redis里面
	        $int_order_id = $array_result['order_id'];
	        $bool_mflag = $this->student_order->write_order_to_redis($int_order_id,$array_result['array_data']);
			#创建订单之后，将要买的课写到redis里面
			$bool_nflag = $this->student_order->write_order_class_to_redis($int_order_id,$array_allow_buy_class_infor);
	        if(!$bool_mflag || !$bool_nflag){
	        	show_error("创建订单失败啦");
	        }    
	    }
		redirect("/pay/order/{$int_order_id}/$payment_method", 'location', 302);
	}
	
	
	/**
	 * 成功提交订单页面
	 * @param  $int_order_id
	 * @param  $payment_method
	 */
	public function order($int_order_id=1, $payment_method='online')
	{
	    #判断是否登录
	    if(!$this->is_login){
	    	redirect('/login');
	    }
	    $int_order_id = max(intval($int_order_id), ORDER_START_VALUE);
	    $payment_method = $payment_method == 'online' ? 'online':'remittance';
	    #根据order_id获取订单信息(从redis读取订单信息),如果从数据库中读取
	    $array_order = $this->student_order->read_order_to_redis($int_order_id);
	    if(empty($array_order)){
	    	$array_order = $this->student_order->get_order_by_id($int_order_id);
	    }
	    $int_user_id = $this->session->userdata('user_id'); 
	    //log_message('debug_nahao', "int_user_id:$int_user_id, order_id: $int_order_id, array_order:".print_r($array_order, 1));
	    if (empty($array_order)){
	        #order不存在 或者不是本人订单
	        show_error('订单不存在'); 
	    }
	    if($array_order['student_id']!= $int_user_id){
	    	show_error('不是本人的订单');
	    }
	    #检查用户是否已经买了该轮
		$array_status = array(
			ORDER_STATUS_SUCC,
			ORDER_STATUS_FINISH,
			ORDER_STATUS_APPLYREFUND,
			ORDER_STATUS_APPLYREFUND_FAIL,
			ORDER_STATUS_APPLYREFUND_AGREE,
			ORDER_STATUS_APPLYREFUND_SUCC
		);
	    if (in_array($array_order['status'],$array_status)){
	    	show_error('您已经买过该轮了，请不要重复购买');
	    }
	    #查找该轮的信息(主要是轮的价格)
	    $array_round = $this->student_course->get_round_info($array_order['round_id']);
	    //$array_order['id'] = $int_order_id;
	    #如果是0元免费课程，执行下面的操作(或者不走支付的开关打开)
	    if ($array_round['current_price'] == '0' || NOT_GO_PAY_SWITCH == '0'){
	    	#更新订单状态,写日志
	    	$array_data = array(
				'round_id'=>$array_order['round_id'],
				'user_id'=>$int_user_id,
				'order_id' =>$array_order['id'],
				'status'=>ORDER_STATUS_SUCC,
				'pay_type' =>ORDER_TYPE_ONLINE,                      #支付方式
				'action'=>ORDER_ACTION_SUCC,            	         #日志动作
				'note'=>'0元免费课程支付成功',                             #日志记录
				'user_type'=>NH_MEETING_TYPE_STUDENT
	    	);
	    	$this->zero_order_action($array_data);
	    }
	    
	    $bank_code = config_item('bank_code');
	    $this->smarty->assign('bank_code', $bank_code);
	    $this->smarty->assign('array_order', $array_order);
	    $this->smarty->assign('payment_method', $payment_method);
	    $this->smarty->assign('current_price', $array_round['current_price']);
	    $this->smarty->display('www/studentCart/toPay.html');   
	}
	
	/**
	 * 如果是0元免费课程,执行下面的操作
	 */
	public function zero_order_action($array_data)
	{
		$this->student_order->update_order_status($array_data);
		#根据order_id,查找轮以及轮里面的课，添加学生与课的关系
		$this->student_order->add_student_class_relation($array_data['order_id'],$array_data['user_id']);
		#添加购买人数,如果是最后一个，将状态改为已售罄
		$this->student_order->update_round_data($array_data['round_id']);
		#是否付过费
		$array_user = $this->_user_detail;
		if($array_user['has_bought'] == '0'){
			$this->business_user->modify_user_info(array('has_bought'=>1),$array_data['user_id']);
		}
	}
	
	/**
	 * 请求支付
	 * @param  $int_order_id
	 */
	public function request($int_order_id=ORDER_START_VALUE)
	{
	    #判断是否登录
	    if(!$this->is_login){
	     	redirect('/login');
	    }
	    $int_order_id = max(intval($int_order_id), ORDER_START_VALUE);
	    $str_nickname = $this->session->userdata('nickname');
	    $int_user_id = $this->session->userdata('user_id');
	    #根据order_id获取订单信息(从redis读取订单信息),如果从数据库中读取
	    $array_order = $this->student_order->read_order_to_redis($int_order_id);
	    if(empty($array_order)){
	    	$array_order = $this->student_order->get_order_by_id($int_order_id);
	    }
	    if (!$array_order){
	        #order不存在
	        show_error('订单不存在'); 
	    }
	    if($array_order['student_id']!= $int_user_id){
	    	show_error('不是本人的订单');
	    }
	    $array_result = $this->model_order->check_product_in_order($array_order['round_id'],$int_user_id);
		$arrat_status = array(
			ORDER_STATUS_SUCC,
			ORDER_STATUS_FINISH,
			ORDER_STATUS_APPLYREFUND,
			ORDER_STATUS_APPLYREFUND_FAIL,
			ORDER_STATUS_APPLYREFUND_AGREE,
			ORDER_STATUS_APPLYREFUND_SUCC
		);
		#检查用户是否买过该订单里的这轮，防止重复购买
	    if ($array_result && (in_array($array_result[0]['status'],$arrat_status))){
	    	show_error('您已经买过该轮了，请不要重复购买');
	    }
		#如果下单超过30分钟就不能再支付了
	    if (time() > ($array_order['create_time'] + 1800)){
	    	show_error('生成订单30分钟之后就不能再支付了！');
	    }
	    $array_round = $this->model_course->get_round_info($array_order['round_id']);
		#轮销售结束之后就不能支付了
	    if($array_round['sale_status'] != ROUND_SALE_STATUS_SALE){
	    	show_error('此轮销售已结束，已不可支付！');
	    }
	    $method = $this->input->post('method');
	    if($method == 'netpay'){
	        $payChannel = 'netpay';
	        $bankCode = '';
	        $payMethod = '';
	    } else {
	        $method = explode('|', $method);
	        $pay_method = isset($method[0]) && $method[0] ? $method[0] : '';
	        $bank_code = isset($method[1]) && $method[1] ? $method[1] : '';
	        //目前只有支付宝一种支付接口网关
	        $payChannel = 'alipay';
	        $bank_code_config = config_item('bank_code');
	        $bankCode = isset($bank_code_config[$pay_method][$bank_code]['code']) ? $bank_code_config[$pay_method][$bank_code]['code']:'';
	        $payMethod = isset($bank_code_config[$pay_method][$bank_code]['type']) ? $bank_code_config[$pay_method][$bank_code]['type']:'';
	    }
	    $order_id = $array_order['id'];
	    $name = mb_substr($array_round['title'],0,124,'utf-8');
	    $desc = '';
	    $amount = $array_order['spend'];
	    $create_time = $array_order['create_time'];
	    
	    switch (strtoupper($payChannel)) 
	    {
	        case 'ALIPAY':
	            $this->load->model('business/pay/model_alipay','pay');
	            break;
	        case 'NETPAY':
	            $this->load->model('business/pay/model_netpay','pay');
	            break;
	    }
	    $array = array(
			'order_sn' => $order_id,
			'amount' => $amount,
			'name' => $name,
			'desc' => $desc,
			'paymethod' => $payMethod,
			'bank' => $bankCode,
			'payerName' => $str_nickname,
			'TransDate' => date('Ymd',$create_time)
	    );
	    if($payChannel == 'netpay'){
	        $array['transDate'] = $create_time;
	    }
	    $html = $this->pay->request($array);
	    
	    if (!$html || $html == '') {
	        //$this->load->view('blank', array("title"=>'提交失败', 'message'=>' 支付接口错误'), $this->tplpath);
	        return ;
	    }
	    header("Content-type:text/html;charset=utf-8");
	    echo "<!DOCTYPE html><html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>
	    <title>请等待,连接银行中...</title></head><body>请等待,连接银行中...{$html}</body></html>";
	}
	
	/**
	 * 支付回调
	 */
	public function payback()
	{
	    //测试页面跳转回调连接
/* 	   http://www.nahaodev.com/pay/payback?body=%E7%94%B5%E5%AD%90%E6%8A%80%E6%9C%AF%E5%9F%BA%E7%A1%80%EF%BC%88%E4%B8%89%EF%BC%89&buyer_email=wsbnd9%40gmail.com&buyer_id=2088212220120365&exterface=create_direct_pay_by_user&is_success=T&notify_id=RqPnCoPT3K9%252Fvwbh3InR8tGjRXYpexpaRGyeCWBjSZV1%252BqqtUD1W5T58ANYJw2sMq9G4&notify_time=2014-06-18+14%3A53%3A18&notify_type=trade_status_sync&out_trade_no=1&payment_type=1&seller_email=nahao%40tizi.com&seller_id=2088411963723035&subject=%E7%8E%8B%E8%80%81%E5%B8%88+2014%E5%B9%B4%E4%BA%94%E5%B9%B4%E7%BA%A7%E5%A5%A5%E6%95%B0%E6%9A%91%E5%81%87%E8%AE%AD%E7%BB%83%E8%90%A51&total_fee=0.01&trade_no=2014061831991336&trade_status=TRADE_SUCCESS&sign=d10440f551dd92b30ef83c40839a7d31&sign_type=MD5 */

// 	    log_message("ERROR_NAHAO", 'payback_server:'.var_export($_SERVER,true)."\n".'payback_get:'.var_export($_GET,true)."\n"
// 	    .'payback_post:'.var_export($_POST,true)."\n".'payback_request:'.var_export($_REQUEST,true)."\n---------------------------------\n");
	   
	    $response = array('title' => '支付失败', 'message' => '');
	    $payResult = null;
	    $paymentChannel = $this->checkPaymentChannel();

	    do {

	        if (empty ($paymentChannel) || $paymentChannel == '') {
	            $response['message'] = '非法支付渠道';
	            break;
	        }
	        $this->load->helper('url');
	        $this->load->model("business/pay/model_{$paymentChannel}", 'channel');
	        $payResult = $this->channel->response();
	      
	        #订单更新信息初始化
	        $order_updata = array('status' => ORDER_STATUS_SUCC);
	        #获取订单信息
	        $array_orderInfo = $this->student_order->get_order_by_id($payResult['order_id']);
	        $int_user_id = $array_orderInfo['student_id'];
	        //log_message("ERROR_NAHAO",'round_id:'.$array_orderInfo['round_id']);
	        #未知的订单
	        if (empty ($array_orderInfo)) {
	            $order_updata['status'] = '0';
	            $response['message'] = '订单号不存在';
	            break;#不需要更新数据库直接退出
	        }
	        
	        #支付金额有误
	        if ($array_orderInfo['spend'] != $payResult['amount']) 
	        {
	            $order_updata['status'] = ORDER_STATUS_FAIL;
	            $response['message'] = '金额校验错误';
	            #如果订单已经成功则不再更新
	            if($array_orderInfo['status'] < ORDER_STATUS_SUCC){
	                #更新订单状态,写日志
	                $array_data = array(
	                    'user_id'=>$int_user_id,
	                    'order_id' => $payResult['order_id'],
	                    'status'=>$order_updata['status'],
	                    'pay_type' =>ORDER_TYPE_ONLINE,          #支付方式
	                    'action'=>ORDER_ACTION_FAIL,             #日志动作
	                    'note'=>$response['message'],            #日志记录
	                    'user_type'=>NH_MEETING_TYPE_STUDENT
	                );
	                $this->student_order->update_order_status($array_data);
	            }
	            break;
	        }
	        
	        #支付方返回失败
	        if($payResult['status'] != ORDER_STATUS_SUCC)
	        {
	            #-2 签名错误
	            if ($payResult['status'] == ORDER_STATUS_SIGN) {
	                $order_updata['status'] = ORDER_STATUS_FAIL;
	                $response['message'] = '签名错误';
	            }
	        
	            #1 支付失败
	            if ($payResult['status'] == ORDER_STATUS_FAIL) {
	                $order_updata['status'] = ORDER_STATUS_FAIL;
	                $response['message'] = '订单支付失败';
	            }
				#如果订单已经成功则不再更新
	            if($array_orderInfo['status'] < ORDER_STATUS_SUCC){
	                #更新订单状态,写日志
	                $array_data = array(
	                    'user_id'=>$int_user_id,
    	                'order_id' => $payResult['order_id'],
    	                'status'=>$order_updata['status'],
    	                'pay_type' =>ORDER_TYPE_ONLINE,          #支付方式
    	                'action'=>ORDER_ACTION_FAIL,             #日志动作
    	                'note'=>$response['message'],            #日志记录
    	                'user_type'=>NH_MEETING_TYPE_STUDENT
	                );
	                $this->student_order->update_order_status($array_data);
	            }
	            break;
	        }
	        
	        #支付方反回成功
	        if($payResult['status'] == ORDER_STATUS_SUCC)
	        {
	            #支付返回成功之后，更改订单状态，写订单日志,写学生与课的关系
	            #更新订单信息
	            if($array_orderInfo['status'] < ORDER_STATUS_SUCC) //第一次成功
	            {
                    #更新订单状态,写日志
	                $array_data = array(
	                    'user_id'=>$int_user_id,
    	                'order_id' =>$payResult['order_id'],
    	                'status'=>$order_updata['status'],
    	                'pay_type' =>ORDER_TYPE_ONLINE,          #支付方式
    	                'action'=>ORDER_ACTION_SUCC,             #日志动作
    	                'note'=>'支付成功',                      #日志记录
    	                'user_type'=>NH_MEETING_TYPE_STUDENT
	                );
	                $bool_result = $this->student_order->update_order_status($array_data);
	                if (!$bool_result){
	                    $response['message'] = '数据库错误';
	                } 
                    #根据order_id,查找轮以及轮里面的课，添加学生与课的关系
                    $this->student_order->add_student_class_relation($payResult['order_id'],$int_user_id);
                    #添加购买人数,如果是最后一个，将状态改为已售罄
                    $this->student_order->update_round_data($array_orderInfo['round_id']);
                    #是否付过费
                    $array_user = $this->_user_detail;
                    if($array_user['has_bought'] == '0'){
                    	$this->business_user->modify_user_info(array('has_bought'=>1),$int_user_id);
                    }
	            }
	            break;
	        }
	        
	    }while(false);
	    // log_message("ERROR_NAHAO",'$payResult:'.var_export($payResult,true)."\n---------------------------------\n");
		#后台调用
	    if ( $payResult['request_type'] == 'server' ) {
	        echo ($payResult['status'] == 1) ? $payResult['response'] : '';
	    } else { 
			#用户跳转
	        if($response['message']){
	            show_error($response['message']);
	        } else{
	            redirect('/member/my_order');
	        }
	    }
	}
	
	/**
	 * 检查支付渠道
	 *
	 * @return string
	 */
	private function checkPaymentChannel()
	{
	    //目前只有支付宝一种支付渠道
	    $payChannel = config_item('pay_channel');
	    $channel = '';
	    do {
	        $merchantId = $this->input->get_post('seller_id');
	        $aliPayMerchantId = $payChannel['alipay']['merchant_id'];
	        if ( ($merchantId !== false) && $merchantId == $aliPayMerchantId) {
	            $channel = 'alipay';
	            break;
	        }
	        $merchantId = $this->input->get_post('merid');
	        $netPayMerchantId = $payChannel['netpay']['merchant_id'];
	        $netPayUPOPMerchantId = $payChannel['netpay-upop']['merchant_id'];
	        if ( ($merchantId !== false) && ($merchantId == $netPayMerchantId || $merchantId == $netPayUPOPMerchantId) ) {
	            $channel = 'netpay';
	            break;
	        }
	    } while (false);
	
	    return $channel;
	}
	
	/**
	 * Ajax检查订单是否已经支付完成
	 */
	public function check_pay()
	{
	    $int_order_id = intval($this->input->post("order_id"));
	    if ($int_order_id == 0){
	        self::json_output(array('status'=>'error','msg'=>'参数错误'));
	    }
	    #根据order_id获取订单信息从数据库中读取
	    $array_data = $this->model_order->get_order_by_id($int_order_id);
	    if (empty($array_data)){
	        self::json_output(array('status'=>'error','msg'=>'订单不存在'));
	    }
	    if ($array_data['status'] == ORDER_STATUS_SUCC){
	        self::json_output(array('status'=>'ok','msg'=>'支付成功'));
	    } else {
	        self::json_output(array('status'=>'error','msg'=>'支付失败'));
	    }
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */