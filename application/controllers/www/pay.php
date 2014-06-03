<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pay extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/student/student_order');
        $this->load->model('business/student/student_course');
    }


	public function index()
	{
	    
	}
	
	/**
	 * 点击立即购买到核对订单信息页面
	 * @param $int_product_id '就是轮id'
	 */
	public function product($product_id = 1)
	{
	    header('content-type: text/html; charset=utf-8');
	    #判断是否登录
// 	    if(! $this->user){
// 	        redirect('/login');
// 	    }
	    $int_product_id = max(intval($product_id),1);
	    #检查这个$int_product_id是否有效
	    $bool_flag = $this->student_course->check_round_id($int_product_id);
	    if (!$bool_flag)
	    {
	        show_error("商品id错误");
	    }
	    #根据$int_product_id获取订单里面该轮的部分信息
	    $array_data = $this->student_order->get_order_round_info($int_product_id);
	    //var_dump($array_data);die;
	    $this->smarty->assign('array_data', $array_data);
	   // var_dump($array_data);die;
	    $this->smarty->display('www/studentCart/infoCheck.html');
	}
	
	/**
	 * 创建新订单
	 * @param  $product_id
	 */
	public function neworder($product_id = 1)
	{
	    header('content-type: text/html; charset=utf-8');
	    #判断是否登录
	    // 	    if(! $this->user){
	    // 	        redirect('/login');
	    // 	    }
	    $int_product_id = max(intval($product_id),1);
	    #检查这个$int_product_id是否有效
	    $bool_flag = $this->student_course->check_round_id($int_product_id);
	    if (!$bool_flag)
	    {
	        show_error("商品id错误");
	    }
	    #线上或者线下支付
	    #$payment_method = $this->input->post('payment_method');
	    $payment_method = 'online';
	    $payment_method = $payment_method == 'online' ? 'online':'remittance';
	    #创建订单,向数据库里面写一条记录，写一条订单日志记录
	    $array_result = $this->student_order->create_order($int_product_id,$payment_method);
	    $int_order_id = $array_result['order_id'];
	    if (!$array_result['status'])
	    {
	        show_error("创建订单失败");
	    }
	    redirect("/pay/adminOrder/{$int_order_id}/$payment_method", 'location', 302);
	}
	
	/**
	 * 成功提交订单页面
	 * @param  $int_order_id
	 * @param  $payment_method
	 */
	public function order($int_order_id=1, $payment_method='online')
	{
	    header('content-type: text/html; charset=utf-8');
	    #是否登录
/* 	    if(! $this->user)
	    {
	        redirect('/login');
	    } */
	    $int_order_id = max(intval($int_order_id), 10000);
	    $payment_method = $payment_method == 'online' ? 'online':'remittance';
	    #根据order_id获取订单信息
	    $array_order = $this->student_order->get_order_by_id($int_order_id);
/* 	    if (!$array_order OR $array_order['student_id']!=$this->user['id'] )#TODO用户id
	    {
	        #order不存在 或者不是本人订单
	        show_error('订单不存在'); 
	    } */
	    #订单状态 > 1 跳转到我的订单
	    if($array_order['status'] > 1){
	        redirect('/my/purchased');
	    }
	    //var_dump($array_order);die;
	    $bank_code = config_item('bank_code');
	    $this->smarty->assign('bank_code', $bank_code);
	    $this->smarty->assign('array_order', $array_order);
	    $this->smarty->assign('payment_method', $payment_method);
	    $this->smarty->display('www/studentCart/toPay.html');   
	}
	
	/**
	 * 请求支付
	 * @param  $int_order_id
	 */
	public function request($int_order_id=10000)
	{
	    header('content-type: text/html; charset=utf-8');
/* 	    #判断是否登录
	    if(! $this->user)
	    {
	        redirect('/login');
	    } */
	    $int_order_id = max(intval($int_order_id), 10000);
	    #根据order_id获取订单信息
	    $array_order = $this->student_order->get_order_by_id($int_order_id);
	    if (!$array_order) 
	    {
	        #order不存在
	        show_error('订单不存在'); 
	    }
	    
	    if($array_order['status'] > 1)
	    {
	        #我的订单
	        redirect('/my/purchased');
	    }
	    
	    $method = $this->input->post('method');
	    if($method == 'netpay')
	    {
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
	    $name = $array_order['id'];
	    $desc = '33';
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
	            'payerName' => '1',#$this->user['nickname'],                     #TODO用户的昵称
	            'TransDate' => date('Ymd',$create_time)
	    );
	    if($payChannel == 'netpay')
	    {
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
	    header('content-type: text/html; charset=utf-8');
	    //测试页面跳转回调连接
	    //http://www.91waijiao.com/pay/payback?is_success=T&sign=b1af584504b8e845ebe40b8e0e733729&sign_type=MD5&body=Hello&buyer_email=xinjie_xj%40163.com&buyer_id=2088101000082594&exterface=create_direct_pay_by_user&out_trade_no=1534&payment_type=1&seller_email=chao.chenc1%40alipay.com&seller_id=2088901014223251&subject=%E5%A4%96%E9%83%A8FP&total_fee=2000.00&trade_no=2008102303210710&trade_status=TRADE_FINISHED&notify_id=RqPnCoPT3K9%252Fvwbh3I%252BODmZS9o4qChHwPWbaS7UMBJpUnBJlzg42y9A8gQlzU6m3fOhG&notify_time=2008-10-23+13%3A17%3A39&notify_type=trade_status_sync&extra_common_param=%E4%BD%A0%E5%A5%BD%EF%BC%8C%E8%BF%99%E6%98%AF%E6%B5%8B%E8%AF%95%E5%95%86%E6%88%B7%E7%9A%84%E5%B9%BF%E5%91%8A%E3%80%82&bank_seq_no=%E6%8B%9B%E8%A1%8C%E7%9A%84%E8%AE%A2%E5%8D%95%E5%8F%B7%E5%BD%A2%E5%A6%829220031730%3B%0D%0A%E5%BB%BA%E8%A1%8C%E7%9A%84%E5%BD%A2%E5%A6%8220100329000000859967

	    log_message("ERROR_NAHAO", var_export($_SERVER,true)."\n".var_export($_GET,true)."\n"
	    .var_export($_POST,true)."\n---------------------------------------------------------
	    ---------------------------\n");
	    $response = array('title' => '支付失败', 'message' => '');
	    $payResult = null;
	    $paymentChannel = $this->checkPaymentChannel();
	    do {
	    
	        if (empty ($paymentChannel) || $paymentChannel == '') 
	        {
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
	        
	        #未知的订单
	        if (empty ($array_orderInfo)) 
	        {
	            $order_updata['status'] = '0';
	            $response['message'] = '订单号不存在';
	            break;#不需要更新数据库直接退出
	        }
	        
	        #支付金额有误
	        if ($array_orderInfo['spend'] != $payResult['amount']) 
	        {
	            $order_updata['status'] = ORDER_STATUS_FAIL;
	            $response['message'] = '金额校验错误';
	            if($array_orderInfo['status'] < ORDER_STATUS_SUCC)#如果订单已经成功则不再更新
	            {
	                #更新订单状态,写日志
	                $array_data = array(
	                    'order_id' => $payResult['order_id'],
	                    'status'=>$order_updata['status'],
	                    'pay_type' =>ORDER_TYPE_ALIPAY,          #支付方式
	                    'action'=>ORDER_STATUS_FAIL,             #日志动作
	                    'note'=>$response['message']             #日志记录
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
	        
	            if($array_orderInfo['status'] < ORDER_STATUS_SUCC)#如果订单已经成功则不再更新
	            {
	                #更新订单状态,写日志
	                $array_data = array(
    	                'order_id' => $payResult['order_id'],
    	                'status'=>$order_updata['status'],
    	                'pay_type' =>ORDER_TYPE_ALIPAY,          #支付方式
    	                'action'=>ORDER_STATUS_FAIL,             #日志动作
    	                'note'=>$response['message']             #日志记录
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
    	                'order_id' => $payResult['order_id'],
    	                'status'=>$order_updata['status'],
    	                'pay_type' =>ORDER_TYPE_ALIPAY,          #支付方式
    	                'action'=>ORDER_STATUS_SUCC,             #日志动作
    	                'note'=>'支付成功'                          #日志记录
	                );
	                $bool_result = $this->student_order->update_order_status($array_data);
	                if (! $bool_result) 
	                {
	                    $response['message'] = '数据库错误';
	                } else {
	                    #根据order_id,查找轮以及轮里面的课，添加学生与课的关系
	                    $this->student_order->add_student_class_relation($payResult['order_id']);
	                }
	        
	            }
	        }
	        
	    }while(false);
	    
	    if ( $payResult['request_type'] == 'server' ) #后台调用
	    {
	        echo ($payResult['status'] == 1) ? $payResult['response'] : '';
	    } else { #用户跳转
	        if($response['message'])
	        {
	            show_error($response['message']);
	        }
	        else
	        {
	            redirect('/my/purchased');
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */