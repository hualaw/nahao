<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 记录发送给用户的消息
 * class Bussiness_Logmsg
 * @author yanhengjia@tizi.com
 * @copyright (c) May 29th, 2014
 */
class Business_Logmsg extends NH_Model 
{
    public function __construct() {
        parent::__construct();
        $this->load->model('model/common/model_sms_log');
    }
    
    /**
     * 发送手机验证码
     * @param  array  $arr_param 要写入的数据
     * @return TRUE | FALSE
     */
    public function log_captcha($arr_param)
    {
        #写入验证码记录表
        $insert_id1 = $this->model_sms_log->insert_captcha_log($arr_param);
        #写入短信记录表
        $insert_id2 = $this->record_sms_log($arr_param);
        if($insert_id1 && $insert_id2) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * 将短信消息记录短信日志表
     * @param  array  $arr_param 要写入的数据
     * @return int last_insert_id
     */
    public function record_sms_log($arr_param)
    {
        $int_insert_id = $this->model_sms_log->insert_sms_log($arr_param);
        
        return $int_insert_id;
    }
}