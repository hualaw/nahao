<?php
/**
 * netpay
 */
include_once(APPPATH . 'third_party/netpay/netpayclient.php');
class Model_Netpay extends NH_Model
{

    public function __construct()
    {
        parent::__construct();
        $tmp = config_item('pay_channel');
        $this->conf = $tmp['netpay-upop'];
    }

    /**
     * 支付请求
     *
     * @param $arr
     * @return bool|string
     */
    public function request(array $arr)
    {
        $parameter = array(
            "MerId" => $this->conf['merchant_id'], //商户号，15位长度
            "OrdId" =>  $this->formatOrdId($arr['order_sn']), //订单号，16位
            "TransAmt" => $this->formatAmt($arr['amount']),//交易金额，12位长度，左补0
            "CuryId" => 156,//币种，人民币156
            "TransDate" => isset($arr['transDate']) ? date('Ymd',$arr['transDate']) : date('Ymd'),//交易日期，8位
            "TransType" => '0001',//0001 支付, 0002 退款
            "Version" => $this->conf['version'], //版本号
            "BgRetUrl" => $this->conf['pay_back_url'],//后台交易接收URL
            "PageRetUrl" => $this->conf['pay_back_url'],//页面交易接收URL
            "GateId" => '8607',//支付网关 netpay-upop走银联通道8607,netpay为空或者按需传值
            "Priv1" => md5($arr['name']),//自定义私有域
            "ChkValue" => false//数字签名
        );
        $parameter['ChkValue'] = $this->buildMySign($parameter);
        if($parameter['ChkValue'] === false)
        {
            return false;
        }
        $html = $this->buildForm($parameter, $this->conf['request_url']);
        return $html;
    }

    /**
     * 回调内容处理
     *
     * @return array
     */
    public function response()
    {
        $merid = $this->input->post('merid'); //商户id
        $orderno = $this->input->post('orderno');//商户订单号,需根据规则处理成订单id
        $transdate = $this->input->post('transdate');//交易日期Ymd
        $amount = $this->input->post('amount');//金额,需根据规则处理成订单金额
        $currencycode = $this->input->post('currencycode');//币种，人民币156
        $transtype = $this->input->post('transtype');//交易类型0001 支付, 0002 退款
        $status = $this->input->post('status');//支付状态 1001成功，不成功应该是没有返回的
        $checkvalue = $this->input->post('checkvalue');//签名，用来校验是不是平台返回的
        $GateId = $this->input->post('GateId');//网关，默认用的8607银联在线
        $Priv1 = $this->input->post('Priv1');//私有域，可以放自定义的串，我这里放的是md5后的title，可以用来校验数据

        $signResult = $this->verifyMySign($_REQUEST);
        $rData = array( 'result' => '',
                        'pay_user' => '',
                        'pay_user_id' => '',
                        'gateway' => 'netpay',
                        'bank_order_id' => '',
                        'request_type' => 'client', //后台调用时，银联根据调用返回的http状态码判断200（成功）,无需输出
                        'response'=>'');
        //状态值(status) 1 成功， 2 签名错误， 3 订单支付失败
        if($signResult === false)
        {
            $rData['status'] = ORDER_STATUS_SIGN;
        }
        elseif($status == '1001')
        {
            $rData['status'] = ORDER_STATUS_SUCC;
            //获取系统订单号
        }
        else
        {
            $rData['status'] = ORDER_STATUS_FAIL;
        }
        $rData['third_part_order_sn'] = $orderno;
        $rData['amount'] = $this->myAmt($amount);
        $rData['order_id'] = $this->myOrdId($orderno);
        return $rData;
    }

    //退款
    public function refund(array $arr)
    {
        $parameter = array(
            "MerID" => $this->conf['merchant_id'], //商户号，15位长度
            "OrderId" =>  $this->formatOrdId($arr['order_sn']), //订单号，16位
            "RefundAmount" => $this->formatAmt($arr['amount']),//交易金额，12位长度，左补0
            "TransDate" => isset($arr['transDate']) ? date('Ymd',$arr['transDate']) : date('Ymd'),//交易日期，8位
            "TransType" => '0002',//0001 支付, 0002 退款
            "Version" => $this->conf['version'], //版本号
            "ReturnURL" => $this->conf['pay_back_url'],//后台交易接收URL
            "Priv1" => $arr['refund_id'],//退款流水号
            "ChkValue" => false//数字签名
        );
        $parameter['ChkValue'] = $this->buildMySign($parameter); var_dump($parameter);
        if($parameter['ChkValue'] === false)
        {
            return false;
        }
        $post_data = $this->createLinkString($parameter);
        $data = $this->curl_redir_exec($this->conf['refund_url'],$post_data);
        return $data;
    }

    /**
     * 生成签名
     *
     * @param $para
     * @return string
     */
    private function buildMySign($para)
    {
        $merid = buildKey($this->conf['MerPrk_key']);
        if(!$merid)
        {
            return false;
        }
        if($this->conf['version'] == '20070129')
        {
            if($para['TransType'] == '0001')
            {
                $plain = $para['MerId'].$para['OrdId'].$para['TransAmt'].$para['CuryId'].$para['TransDate'].$para['TransType'].$para['Priv1'];
            }
            elseif($para['TransType'] == '0002')
            {
                $plain = $para['MerID'].$para['TransDate'].$para['TransType'].$para['OrderId'].$para['RefundAmount'].$para['Priv1'];
            }
            else
            {
                $plain = '';
            }
            return sign($plain);
        }
        else
        {
            return signOrder($para['MerId'],$para['OrdId'],$para['TransAmt'],$para['CuryId'],$para['TransDate'],$para['TransType'],$para['Priv1']);
        }
    }

    /**
     * 验证签名
     *
     * @param $para
     * @return boolean
     */
    private function verifyMySign($para)
    {
        $flag = buildKey($this->conf['PgPubk_key']);
        if(!$flag)
        {
            return false;
        }
        $flag=verifyTransResponse($para['merid'],$para['orderno'],$para['amount'],$para['currencycode'],$para['transdate'],$para['transtype'],$para['status'],$para['checkvalue']);
        return $flag;
    }

    /**
     * 格式化金额数
     * @param $amount
     * @return string 12位，最后两位为小数点后数据
     */
    private function formatAmt($amount)
    {
        return str_replace('.','',sprintf('%013.2f',$amount));
    }

    /**
     * 正常的金额显示
     */
    private function myAmt($amount)
    {
        if(strlen($amount) == 12)
        {
            return ltrim(substr($amount,0,10),0).'.'.substr($amount,10,2);
        }
        return 0;
    }

    /**
     * 格式化订单id
     * @param int $orderId（左边不能有0，不然影响myOrdId反解）
     * @return string 16位 (5-9位是商户号后五位)
     */
    private function formatOrdId($orderId)
    {
          return str_pad(substr($orderId,0,-7),4,'0',STR_PAD_LEFT).substr($this->conf['merchant_id'],-5).str_pad(substr($orderId,-7),7,'0',STR_PAD_LEFT);
    }

    /**
     * 正常订单id(自增id)
     */
    private function myOrdId($orderId)
    {
        if(strlen($orderId) == 16)
        {
            return ltrim(substr($orderId,0,4),0).ltrim(substr($orderId,9,7),0);
        }
        return 0;
    }

    /**
     * 生成FORM表单
     *
     * @param $para_temp
     * @param $gateway
     * @return string
     */
    private function buildForm($para, $gateway)
    {
        //待请求参数数组
        $sHtml = '<form id="netPay_submit" name="netPay_submit" action="'.$gateway.'" method="POST">';
        foreach ($para as $key => $val) {
            $sHtml.= '<input type="hidden" name="'.$key.'" value="'.$val.'"/>'.PHP_EOL;
        }
        //submit按钮控件请不要含有name属性
        //$sHtml = $sHtml."<input type='submit' value='提交'></form>";
        $sHtml = $sHtml."<script>document.forms['netPay_submit'].submit();</script>";
        return $sHtml;
    }

    //curl redir
    private function curl_redir_exec($url,$post_data)
    {
    	$http = curl_init();
        curl_setopt($http, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($http, CURLOPT_ENCODING, "gzip");
        curl_setopt($http, CURLOPT_TIMEOUT, 30);
        curl_setopt($http, CURLOPT_POST, 1);
        curl_setopt($http, CURLOPT_HEADER, true);
        curl_setopt($http, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($http, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($http, CURLOPT_URL, $url);
        $data = curl_exec($http);
        $ret = $data;
        list($header, $data) = explode("\r\n\r\n", $data, 2);
        $http_code = curl_getinfo($http, CURLINFO_HTTP_CODE);
        if ($http_code == 301 || $http_code == 302) {
            $matches = array();
            preg_match('/Location:(.*?)\n/', $header, $matches);
            $url = @parse_url(trim(array_pop($matches)));
            if (!$url)
            {
              $curl_loops = 0;
              return $data;
            }
            $last_url = parse_url(curl_getinfo($http, CURLINFO_EFFECTIVE_URL));
            if (!$url['scheme'])
                $url['scheme'] = $last_url['scheme'];
            if (!$url['host'])
                $url['host'] = $last_url['host'];
            if (!$url['path'])
                $url['path'] = $last_url['path'];
            $new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . (isset($url['query']) ? '?'.$url['query'] : '');
            return self::curl_redir_exec($new_url, $post_data);
        } else if ($http_code == 200) {
            list($header, $data) = explode("\r\n\r\n", $ret, 2);
            return $data;
        } else {
              return false;
        }
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
}//End Of Class Model_Natpay