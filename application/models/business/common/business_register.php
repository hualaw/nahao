<?php

class Business_Register extends NH_Model {

	public function __construct()
	{
		$this->load->model('model/common/model_user');
	}

	public function submit($phone, $email, $sha1_password, $captcha, $reg_type)
	{
        $check_ret = $this->_check_register_data($phone, $email, $sha1_password, $captcha, $reg_type);
        if($check_ret['status'] != SUCCESS) return $check_ret;

		//check data
		if($reg_type == REG_LOGIN_TYPE_PHONE)
		{
			$check_ret = $this->check_phone($phone);
			if($check_ret['status'] != SUCCESS){
				return $check_ret;
			}
			$phone_verified = 1;
            $nickname = $phone_mask = phone_blur($phone);

			//check captcha
			$bool_ret = $this->_check_captcha($phone, $captcha, REGISTER_VERIFY_CODE);
			if(!$bool_ret) return $this->_log_reg_info(ERROR, 'reg_verify_captcha_failed', array('phone'=>$phone, 'captcha'=>$captcha));
		}
		else if($reg_type == REG_LOGIN_TYPE_EMAIL)
		{
			$phone_verified = 0;			
			$check_ret = $this->check_email($email);
            $nickname = $email;
            $phone_mask = $phone; //存储phone_mask用明文phone，这样方便登录时取出未验证的完整手机号

			if($check_ret['status'] != SUCCESS){
				return $check_ret;
			}
		}

		//save register information, get user_id
		$this->load->helper('string');
        $str_salt = random_string('alnum', 6);

		$user_table_data = array(
			'nickname' => $nickname,
			'phone_mask' => $phone_mask,
			'email' => $email,
			'salt' => $str_salt,
			'password'=> create_sha1_password($str_salt, $sha1_password),
			'register_time' => time(),
			'register_ip' => ip2long($this->input->ip_address()),
			'source' => 1,
			'phone_verified' => $phone_verified,
			'avatar' => '', //default avatar URI, TBD
            'reg_type' => $reg_type
			);
		
		$user_id = $this->model_user->create_user($user_table_data);


        //if insert table failed, the $uer_id is int zero
        if($user_id === 0)
		{
            $user_table_data['error'] = 'user_insert_failed';
			return $this->_log_reg_info(ERROR, 'reg_db_error', $user_table_data);
		}

        //insert a record into user_info table
        $insert_affected_rows = $this->model_user->create_user_info(array('user_id'=>$user_id));
        if($insert_affected_rows < 1)
        {
            //标记user表里的记录为无效状态
            $this->model_user->update_user(array('status'=>0), array('id'=>$user_id));
            $user_table_data['error'] = 'user_info_insert_failed';
            return $this->_log_reg_info(ERROR, 'reg_db_error', $user_table_data);
        }

        if($reg_type == REG_LOGIN_TYPE_PHONE)
        {
            //store the phone number to phone_server
            $add_ret = add_user_phone_server($user_id, $phone);
            if(!$add_ret)
            {
                //标记user表里的记录为无效状态
                $this->model_user->update_user(array('status'=>0), array('id'=>$user_id));
                //标记user_info表里的记录为无效状态
                $this->model_user->update_user_info(array('status'=>0), array('user_id'=>$user_id));

                $log_info = array(
                    'error' => 'reg_phone_server_error',
                    'user_id' => $user_id,
                    'phone' => $phone,
                );
                return $this->_log_reg_info(ERROR, 'reg_phone_server_error', $log_info);
            }
        }

        //set session
        $avatar = '';
        $phone_mask = (strpos($phone_mask, '*') !== false) ? $phone_mask : phone_blur($phone_mask);
        $this->set_session_data($user_id, $nickname, $avatar, $phone, $phone_mask, $email, $reg_type, NH_MEETING_TYPE_STUDENT);
		return $this->_log_reg_info(SUCCESS, 'reg_success', array(), 'info');
	}

	public function send_verify_email($email)
	{
		//未完成
		$this->load->library('mail');
		//$this->mail->send($email, )
	}

	function _check_captcha($phone, $captcha, $type)
	{
        $compare_result = false;
		$this->load->model('model/common/model_redis', 'redis');
		$this->redis->connect('login');
		$captcha_count = $this->cache->redis->llen($phone);
        //echo "captcha_count is $captcha_count";
        if($captcha_count >= 1)
        {
            $captcha_info_arr = $this->cache->redis->lrange($phone, 0, $captcha_count-1);
            foreach($captcha_info_arr as $i => $one_info)
            {
                $one_info_arr = json_decode($one_info, true);
                //print_r($one_info_arr);

                if(time() <= $one_info_arr['et'])
                {
                    if(($one_info_arr['t'] == $type) && ($captcha == $one_info_arr['vc']))
                    {
                        $compare_result = true;
                        break;
                    }
                }
                else {
                    //clean expire captcha automatically
                    $this->cache->redis->lrem($phone, $one_info, 1);
                }
            }
        }

        return $compare_result;
	}

    function _check_register_data($phone, $email, $sha1_password, $captcha, $reg_type)
    {
        $sign = 1;
        if($reg_type == REG_LOGIN_TYPE_PHONE)
        {
            if(strlen($phone) == 0 || strlen($sha1_password) == 0 || strlen($captcha) == 0)
            {
                $sign = 2;
            }
        }
        else if($reg_type == REG_LOGIN_TYPE_EMAIL)
        {
            if(strlen($email) == 0 || strlen($sha1_password) == 0)
            {
                $sign = 2;
            }
        }

        if( $sign == 2 )
        {
            $arr_log = array(
                'phone'=>$phone,
                'email'=>$email,
                'sha1_password'=>$sha1_password,
                'captcha'=>$captcha,
                'reg_type'=>$reg_type,
            );
            return $this->_log_reg_info(ERROR, 'reg_invalid_info', $arr_log);
        }

        return $this->_log_reg_info(SUCCESS, 'reg_valid_info', array());
    }
}