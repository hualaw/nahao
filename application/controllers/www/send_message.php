<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header('Content-Type:text/html;Charset=UTF-8');
/**
 * 发送短信的控制器
 * @copyright (c) May 28th, 2014
 */
class send_message extends NH_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->library('sms');
        $this->load->model('business/common/business_logmsg', 'logmsg');
    }
    
    /**
     * 向手机发送验证吗
     * $phone 手机号码
     * $validate_type 验证码类型 1 注册 2 订单前绑定手机 3 找回密码'
     */
    public function send_captcha()
    {
        $phone = trim($this->input->post('phone'));
        $validate_type = (int)$this->input->post('validate_type');
        $validate_code = mt_rand(10000, 99999);
        $time = time();
        $send_time     = date('H:i', $time);
        $arr_msg_content = array(3 => '验证码:%s(您于%s申请手机验证找回那好账号密码)【那好网】');
        $content = sprintf($arr_msg_content[3], $validate_code, $send_time);
        $this->sms->setPhoneNums('18612596890');
        $this->sms->setContent($content);
        $res = $this->sms->send();
        if($res['error'] == 'Ok') {
            $status = 1;
        } else {
            $status = 0;
        }
        $insertData = array(
            'phone' => '18612596890',
            'content' => $content,
            'create_time' => $time,
            'deadline' => $time + 3000,
            'status' => $status,
            'type' => 3,
            'verify_code' => $validate_code,
            'ip' => '1000000',
        );
        $a = $this->logmsg->log_captcha($insertData);
    }
}