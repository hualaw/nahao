<?php
/**
 * 支付宝wap支付
 */
class Model_Wap_Alipay extends NH_Model
{

    public function __construct()
    {
        parent::__construct();
        $tmp = config_item('pay_channel');
        $this->conf = $tmp['alipay_wap'];
        $this->conf['format'] = 'xml';
        $this->conf['v'] = '2.0';
    }

    /**
     * 支付宝支付请求
     *
     * @param $arr
     * @return bool|string
     */
    public function request($req_id, $data)
    {
        //业务详细
        $req_data = '<auth_and_execute_req><request_token>' . $data['request_token'] . '</request_token></auth_and_execute_req>';
        //必填
        
        //构造要请求的参数数组，无需改动
        $parameter = array(
        		"service" => "alipay.wap.auth.authAndExecute",
        		"partner" => $this->conf ['merchant_id'],
        		"sec_id" => $this->conf['sign_type'],
        		"format"	=> $this->conf['format'],
        		"v"	=> $this->conf['v'],
        		"req_id"	=> $req_id,
        		"req_data"	=> $req_data,
        		"_input_charset" => $this->conf['input_charset']
        );

        $actionUrl = $this->conf ['request_url'] . '_input_charset='.$this->conf['input_charset'];

        $html = $this->buildForm($parameter, $actionUrl);

        return $html;
    }
    
    /**
     *  建立请求获取token授权令牌
     */
    public function getRequestToken($req_id,$data)
    {
        $req_data = '<direct_trade_create_req><notify_url>' . $this->conf['notify_url'] . '</notify_url><call_back_url>' . $this->conf['pay_back_url'] . '</call_back_url><seller_account_name>' . $this->conf['account'] . '</seller_account_name><out_trade_no>' . $data['out_trade_no'] . '</out_trade_no><subject>' . $data['subject'] . '</subject><total_fee>' . $data['total_fee'] . '</total_fee></direct_trade_create_req>';
        
        //构造要请求的参数数组
        $para_token = array(
                "service" => "alipay.wap.trade.create.direct",
                "partner" => $this->conf ['merchant_id'],
                "sec_id" => $this->conf['sign_type'],
                "format"	=> $this->conf['format'],
                "v"	=> $this->conf['v'],
                "req_id"	=> $req_id,
                "req_data"	=> $req_data,
                "_input_charset"	=> $this->conf['input_charset']
        );
        //建立请求
        $html_text = $this->buildRequestHttp($para_token);
        
        //URLDECODE返回的信息
        $html_text = urldecode($html_text);
        //解析远程模拟提交后返回的信息
        $para_html_text = $this->parseResponse($html_text);       
        //获取request_token
        $request_token = isset($para_html_text['request_token']) ? $para_html_text['request_token'] : false;
        return $request_token;
    }

    /**
     * 处理支付宝回调
     *
     * @return array
     */
    public function response($act = 'call_back')
    {
		//对待签名参数数组排序
		if($act == 'call_back') {
		    $return = $this->input->get();
		    $para = $this->paraFilter($return);
			ksort($para);
	        reset($para);
		} else {
		    $return = $this->input->post();//post数据
		    if(isset($return['notify_data']))
		    {
    		    $doc = new DOMDocument();
    		    $doc->loadXML($return['notify_data']);
    		    //通知id
    		    $notify_id = $doc->getElementsByTagName( "notify_id" )->item(0)->nodeValue;
    		    
    		    //获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
    		    $responseTxt = 'true';
    		    $veryfy_url = "http://notify.alipay.com/trade/notify_query.do?partner=" . $this->conf['merchant_id'] . "&notify_id=" . $notify_id;
    		    $responseTxt = getHttpResponse($notify_id);
    		    if ((int)preg_match("/true$/i",$responseTxt) === 0)
    		    {
    		       return false;// 验证 不是支付宝发来的消息
    		    }
		    }
		    else
		    {
		    	return false;
		    }
		    $para_sort = $this->paraFilter($return);
			$para['service'] = $para_sort['service'];
			$para['v'] = $para_sort['v'];
			$para['sec_id'] = $para_sort['sec_id'];
			$para['notify_data'] = $para_sort['notify_data'];
			$para = $para_sort;
			unset($para_sort);
			
			//商户订单号
			$return['out_trade_no'] = $doc->getElementsByTagName( "out_trade_no" )->item(0)->nodeValue;
			//支付宝交易号
			$return['trade_no'] = $doc->getElementsByTagName( "trade_no" )->item(0)->nodeValue;
			//交易状态
			$trade_status = $doc->getElementsByTagName( "trade_status" )->item(0)->nodeValue;
			if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS')
			{
			    $return['result'] = 'success';
			}
		}		
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = $this->createLinkstring($para);
		
		$isSgin = false;
		switch ($this->conf['sign_type']) {
			case "MD5" :
				$isSgin = $this->md5Verify($prestr, $return['sign'], $this->conf['merchant_key']);
				break;
			default :
				$isSgin = false;
		}
		if($isSgin)
		{
            $rData = array(
                'result' => $return['result'],//结果 success
                'third_part_order_sn' => $return['trade_no'],//支付宝订单号
                'order_id' => substr($return['out_trade_no'],4), //商户订单号
                'gateway' => 'alipay', //网关
            );
            return $rData;
		}
		return false;//验证失败，返回false;
    }

    /**
     * 生成加密串
     *
     * @param $sort_para
     * @param $key
     * @param string $sign_type
     * @return string
     */
    private function buildMySign($sort_para, $sign_type = 'MD5')
    {
    	//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
    	$preStr = $this->createLinkstring($sort_para);
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
    	switch (strtoupper($sign_type)) {
    		case "MD5" :
    		    $sign = $this->md5Sign($preStr, $this->conf['merchant_key']);
    		    break;
    		case "RSA" :
    		    break;
    		default :
    		    $sign = "";
    		    break;
    	}    	
    	return $sign;
    }
    
    /**
     * 签名字符串
     * @param $prestr 需要签名的字符串
     * @param $key 私钥
     * return 签名结果
     */
    private function md5Sign($prestr, $key) {
        $prestr = $prestr . $key;
        return md5($prestr);
    }
    
    /**
     * 验证签名
     * @param $prestr 需要签名的字符串
     * @param $sign 签名结果
     * @param $key 私钥
     * return 签名结果
     */
    private function md5Verify($prestr, $sign, $key) {
        $prestr = $prestr . $key;
        $mysgin = md5($prestr);
    
        if($mysgin == $sign) {
            return true;
        }
        else {
            return false;
        }
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
        $para = $this->buildRequestPara($para_temp);
        $sHtml = "<form id='aliPay_submit' name='aliPay_submit' action='".$gateway."' method='GET'>";//手机用post有问题啊啊啊
        foreach ($para as $key => $val) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>".PHP_EOL;
        }
        //submit按钮控件请不要含有name属性
        //$sHtml = $sHtml."<input type='submit' value='提交'></form>";
        $sHtml = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/><title>请等待,连接银行中...</title></head><body>请等待,连接银行中...{$sHtml}<script>document.forms['aliPay_submit'].submit();</script></body></html>";
        return $sHtml;
    }

    //去掉加密类型、加密串和为空的字段
    private function paraFilter($para)
    {
        $para_filter = array();
        foreach ($para as $key => $val) {
            if($key == "sign" || $key == "sign_type" || $val == ""){
                continue;
            }else{
                $para_filter[$key] = $para[$key];
            }
        }

        return $para_filter;
    }
    
    /**
     * 建立请求，以模拟远程HTTP的POST请求方式构造并获取支付宝的处理结果
     * @param $para_temp 请求参数数组
     * @return 支付宝处理结果
     */
    private function buildRequestHttp($para_temp) {
        $sResult = '';        
        //待请求参数数组字符串
        $request_data = $this->buildRequestPara($para_temp);
        //远程获取数据
        $sResult = getHttpResponse($this->conf['request_url'].'_input_charset='.$this->conf['input_charset'], true, $request_data);
    
        return $sResult;
    }
    
    /**
     * 生成要请求给支付宝的参数数组
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组
     */
    private function buildRequestPara($para_temp) {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->paraFilter($para_temp);    
        //对待签名参数数组排序
        ksort($para_filter);
        reset($para_filter);    
        //生成签名结果
        $mysign = $this->buildMySign($para_filter);   
        //签名结果与签名方式加入请求提交参数组中
        $para_filter['sign'] = $mysign;   
        return $para_filter;
    }
    
    /**
     * 解析远程模拟提交后返回的信息
	 * @param $str_text 要解析的字符串
     * @return 解析结果
     */
    private function parseResponse($str_text) {
        //以“&”字符切割字符串
        $para_split = explode('&',$str_text);
        //把切割后的字符串数组变成变量与数值组合的数组
        foreach ($para_split as $item) {
            //获得第一个=字符的位置
            $nPos = strpos($item,'=');
            //获得字符串长度
            $nLen = strlen($item);
            //获得变量名
            $key = substr($item,0,$nPos);
            //获得数值
            $value = substr($item,$nPos+1,$nLen-$nPos-1);
            //放入数组中
            $para_text[$key] = $value;
        }
    
        if( ! empty ($para_text['res_data'])) {            
            if($this->conf['sign_type'] == '0001' || $this->conf['sign_type'] == 'RSA') {
                //解析加密部分字符串
            }           	
            //token从res_data中解析出来（也就是说res_data中已经包含token的内容）
            $doc = new DOMDocument();
            $doc->loadXML($para_text['res_data']);
            $para_text['request_token'] = $doc->getElementsByTagName( "request_token" )->item(0)->nodeValue;
        }
    
        return $para_text;
    }
}