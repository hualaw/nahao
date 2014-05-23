<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pay extends NH_Student_Controller {

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
	    var_dump($array_data);die;
	    $this->smarty->assign('array_data', $array_data);
	    $this->smarty->display('student/pay_product');
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
	    redirect("/pay/order/{$int_order_id}/$payment_method", 'location', 302);
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
	    if (!$array_order OR $array_order['student_id']!=$this->user['id'] )#TODO用户id
	    {
	        #order不存在 或者不是本人订单
	        show_error('订单不存在'); 
	    }
	    #订单状态 > 1 跳转到我的订单
	    if($array_order['status'] > 1){
	        redirect('/my/purchased');
	    }
	    var_dump($array_order);die;
	    $this->smarty->assign('array_order', $array_order);
	    $this->smarty->assign('payment_method', $payment_method);
	    $this->smarty->display('student/pay_order');   
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */