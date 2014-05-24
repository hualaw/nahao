<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 12-8-2
 * Time: 上午11:19
 * To change this template use File | Settings | File Templates.
 */
class Model_Alipay extends NH_Model
{

    public function __construct()
    {
        parent::__construct();
        $tmp = config_item('pay_channel');
        $this->conf = $tmp['alipay'];
        $this->conf['payback'] = config_item('pay_back_url');
    }

    /**
     * 支付宝支付请求
     *
     * @param $arr
     * @return bool|string
     */
    public function request(array $arr)
    {
        $parameter = array(
            "service" => "create_direct_pay_by_user",
            "payment_type" => "1",
            "partner" => $this->conf ['merchant_id'],
            "_input_charset" => 'UTF-8',
            "seller_id" => $this->conf ['request_url'],
            "seller_email" => $this->conf ['account'],
            "return_url" => $this->conf['payback'],
            "notify_url" => $this->conf['payback'],
            "out_trade_no" => $arr['order_sn'],
            "subject" => $arr['name'],
            "body" => $arr['desc'],
            "total_fee" => $arr['amount'],
            "paymethod" => $arr['paymethod'],
            "defaultbank" => $arr['bank'],
            "default_login" => 'Y',
            "anti_phishing_key" => '',
            "exter_invoke_ip" => '',
            "show_url" => '',
            "extra_common_param" => '',
            "royalty_type" => '',
            "royalty_parameters" => '',
        );

        $actionUrl = $this->conf ['request_url'] . '_input_charset=UTF-8';

        $html = $this->buildForm($parameter, $actionUrl);

        return $html;
    }

    /**
     * 处理支付宝回调
     *
     * @return array
     */
    public function response()
    {
        $sign = $this->input->get_post('sign');//加密串
        $out_trade_no = $this->input->get_post('out_trade_no');//商户订单号
        $trade_no = $this->input->get_post('trade_no');//支付宝订单号
        $price = $this->input->get_post('total_fee');//金额
        $buyer_email = $this->input->get_post('buyer_email');//购买用户的账号
        $buyer_id = $this->input->get_post('buyer_id');//购买用户的账号
        $is_success = $this->input->get_post('is_success');//支付状态
        $trade_status = $this->input->get_post('trade_status');//支付状态
        $bank_order_id = $this->input->get_post('bank_seq_no');//银行订单id
        $rData = array(
            'result' => $trade_status,
            'third_part_order_sn' => $trade_no,
            'amount' => $price,
            'order_id' => $out_trade_no,
            'pay_user' => $buyer_email,
            'pay_user_id' => $buyer_id,
            'request_type' => $is_success == 'T' ? 'client' : 'server',
            'gateway' => 'alipay',
            'bank_order_id' => $bank_order_id,
            'response' => in_array($trade_status, array('TRADE_FINISHED', 'TRADE_SUCCESS')) ? 'success' : '',
        );

        if($this->input->server('REQUEST_METHOD') == 'POST')
        {
            //ci默认遍历$_POST变量所有字段urldecode 会导致签名不一致
            $_POST['notify_id'] = $_REQUEST['notify_id'];
            $parameters = $_POST;
        }
        else
        {
            //ci默认遍历$_GET变量所有字段urldecode 会导致签名不一致
            $_GET['notify_id'] = $_REQUEST['notify_id'];
            $parameters = $_GET;
        }

        $sort_para = $this->paraFilter($parameters);

        ksort($sort_para);
        reset($sort_para);

        $mySign = $this->buildMySign($sort_para, $this->conf ['merchant_key']);
        //状态值(status) 1 成功， 2 签名错误， 3 订单支付失败
        $rData['status'] = ORDER_STATUS_SUCC;
        if ($sign != $mySign) {
            $rData['status'] = ORDER_STATUS_SIGN;
        }

        if ($trade_status  != 'TRADE_FINISHED' && $trade_status != 'TRADE_SUCCESS') {
            $rData['status'] = ORDER_STATUS_FAIL;
        }
        return $rData;
    }

    /**
     * 生成加密串
     *
     * @param $sort_para
     * @param $key
     * @param string $sign_type
     * @return string
     */
    private function buildMySign($sort_para, $key, $sign_type = "MD5")
    {
    	//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
    	$preStr = $this->createLinkstring($sort_para);

    	//把拼接后的字符串再与安全校验码直接连接起来
      $preStr = $preStr.$key;

    	//把最终的字符串签名，获得签名结果
    	$mySign = $this->sign($preStr, $sign_type);
    	return $mySign;
    }

    /**
     * 生成链接串
     *
     * @param $para
     * @return string
     */
    private function createLinkString($para)
    {
    	$arg  = "";
        foreach ($para as $key => $val) {
    		$arg .= $key."=".$val."&";
    	}
    	//去掉最后一个&字符
    	$arg = substr($arg, 0, count($arg)-2);

    	//如果存在转义字符，那么去掉转义
    	if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

    	return $arg;
    }

    /**
     * 加密串 -- md5
     *
     * @param $preStr
     * @param string $sign_type
     * @return string
     */
    private function sign($preStr,$sign_type='MD5')
    {
    	$sign='';
    	if($sign_type == 'MD5') {
    		$sign = md5($preStr);
    	}elseif($sign_type =='DSA') {
    		//DSA 签名方法待后续开发
    		die("DSA 签名方法待后续开发，请先使用MD5签名方式");
    	}else {
    		die("支付宝暂不支持".$sign_type."类型的签名方式");
    	}
    	return $sign;
    }

    /**
     * 生成FORM表单
     *
     * @param $para_temp
     * @param $gateway
     * @return string
     */
    private function buildForm($para_temp, $gateway)
    {
        //待请求参数数组
        //$para = $this->buildRequestPara($para_temp,$aliapy_config);
        $para = $this->paraFilter($para_temp);

        ksort($para);
        reset($para);

        //签名结果与签名方式加入请求提交参数组中
        $mySign = $this->buildMySign( $para, $this->conf ['merchant_key'] );
        $para['sign'] = $mySign;
        $para['sign_type'] = 'MD5';

        $sHtml = "<form id='aliPay_submit' name='aliPay_submit' action='".$gateway."' method='POST'>";
        foreach ($para as $key => $val) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>".PHP_EOL;
        }

        //submit按钮控件请不要含有name属性
        //$sHtml = $sHtml."<input type='submit' value='提交'></form>";

        $sHtml = $sHtml."<script>document.forms['aliPay_submit'].submit();</script>";

        return $sHtml;
    }

    //去掉加密类型、加密串和为空的字段
    private function paraFilter($para)
    {
        $para_filter = array();
        foreach ($para as $key => $val) {
            if($key == "sign" || $key == "sign_type" || $val == "")continue;
            else	$para_filter[$key] = $para[$key];
        }

        return $para_filter;
    }
}
