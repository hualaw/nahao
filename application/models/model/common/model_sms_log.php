<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 手机短信记录Model
 */
class Model_Sms_Log extends NH_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * 记录发给用户的短信验证码到sms_verify_log表中
     * @param  array  $data 要写入的数据
     * @return 插入成功返回insert_id
     */
    public function insert_captcha_log($data)
    {
        extract($data);
        $sql = "INSERT INTO sms_verify_code (phone, verify_code, create_time, deadline, ip, type)
                VALUES('{$phone}', {$verify_code}, {$create_time}, {$deadline}, {$ip}, {$type})";
        $this->db->query($sql);
        $int_insert_id = $this->db->insert_id();
        
        return $int_insert_id;
    }
    
    /**
     * 记录发送给用户的每条短信到sms_log表中
     * @param  array  $data 要写入的数据
     * @return 插入成功返回insert_id
     */
    public function insert_sms_log($data)
    {
        extract($data);
        #注册发短信的时候还没有user_id 这里加下判断
        $user_id = isset($user_id) ? $user_id : 0;
        $sql = "INSERT INTO sms_log (phone, content, create_time, status, user_id, type)
                VALUES('{$phone}', '{$content}', {$create_time}, {$status}, {$user_id}, {$type})";
        $this->db->query($sql);
        $int_insert_id = $this->db->insert_id();
        
        return $int_insert_id;
    }
}