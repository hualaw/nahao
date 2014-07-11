<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 */
class view_sms_balance extends NH_Controller
{
    /**
     * 加载数据库连接
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
	/**
	 * @desc 查看短信账户信息，短信账户不足1000时，给指定人发短信。
	 * @author shuaiqi_zhang
	 */
    public function index()
    {
    	$this->load->library('Get_3tong_balance');
    	
    	$g3b_obj = new Get_3tong_balance();
    	$result = $g3b_obj->send();
    	if(empty($result['result'])){
    		if(!empty($result['sms'])){
    			log_message('error_nahao','短信账户剩余金额为：'.$result['sms']['amount'].',短信剩余条数为：'.$result['sms']['number'].'。');
    			if($result['sms']['amount'] < 1000){
    				$this->load->library('sms');
    				$this->sms->setPhoneNums(SMS_SEND_MOBILE);
    				$msg = '短信账户余额已不足1000，剩余为：'.$result['sms']['amount'].'。';
    				$this->sms->setContent($msg);
    				$send_ret = $this->sms->send();
    			}
    		}else{
    			log_message('error_nahao','意外错误，xml为：'.print_r($result['xml'],true));
    		}
    	}else{
    		log_message('error_nahao','接口出错，错误代码为：'.print_r($result['result'],true).',描述：'.print_r($result['desc'],true).',xml为：'.print_r($result['xml'],true));
    	}
    }
   
}