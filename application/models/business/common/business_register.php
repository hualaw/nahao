<?php

class Business_Register extends NH_Model {

	public function __construct()
	{
		$this->load->model('model/common/model_user');
	}

	public function register($phone, $email, $password, $captcha, $reg_type)
	{
		$arr_return = array('status'=>SUCCESS, 'msg'=>'', 'data'=>array());

		//check data
		if($reg_type == REG_TYPE_PHONE)
		{
			$check_ret = $this->check_phone($phone);
			if($check_ret['status'] != SUCCESS){
				return $check_ret;
			}
			$phone_verified = 1;

			//check captcha
			$bool_ret = $this->_check_captcha($phone, $captcha);
			if(!$bool_ret) return $this->_log_reg_info(ERROR, 'reg_verify_captcha_failed', array('phone'=>$phone, 'captcha'=>$captcha));
		}
		else if($reg_type == REG_TYPE_EMAIL)
		{
			$phone_verified = 0;			
			$check_ret = $this->check_email($email);
			if($check_ret['status'] != SUCCESS){
				return $check_ret;
			}
		}

		//save register information, get user_id
		$phone_mask = phone_blur($phone);
		$this->load->helper('string');
        $str_salt = random_string('alnum', 6);

		$user_table_data = array(
			'nickname' => '',
			'phone_mask' => $phone_mask,
			'email' => $email,
			'salt' => $str_salt,
			'password'=> create_password($password),
			'register_time' => time(),
			'register_ip' => $this->input->ip_address(),
			'source' => 1,
			'phone_verified' => $phone_verified,
			'avatar' => '', //default avatar URI, TBD
			);
		
		$user_id = $this->model_user->create_user($user_table_data);

var_dump($user_table_data);
		var_dump($user_id);

		//if insert table failed, the $uer_id is int zero
		if($user_id === 0)
		{
			return $this->_log_reg_info(ERROR, 'reg_db_error', $user_table_data);
		}

		//store the phone number to phone_server
		$add_ret = add_user_phone_server($user_id, $phone);
		if(!$add_ret)
		{
			return $this->_log_reg_info(ERROR, 'reg_phone_server_error', array('user_id'=>$user_id, 'phone'=>$phone));
		}

		//set session

		//gen nickname
		if($phone_mask) $nickname = $phone_mask;
		else if($email) $nickname = $email;
		else {
			$this->_log_reg_info(ERROR, 'reg_no_nickname', $user_table_data);
		}

		$userdata = array(
			'nickname' => $nickname,
			'user_type' => 1,
			'user_id' => $user_id,
			);
		$this->session->set_userdata($userdata);
		return $this->_log_reg_info(SUCCESS, 'reg_success', array(), 'info');
	}

	public function check_nickname($nickname)
	{
		$nickname_count = $this->model_user->get_user_by_param('user', 'count', '*', array('nickname'=>$nickname));
		if($nickname_count >= 1)
		{
			return $this->_log_reg_info(ERROR, 'reg_dup_nickname', array('nickname'=>$nickname));
		}
		return $this->_log_reg_info(SUCCESS, 'reg_check_nickname_success', array('nickname'=>$nickname), 'info');
	}

	public function check_phone($phone)
	{
		//check phone is invalid
		if(!is_mobile($phone))
		{
			return $this->_log_reg_info(ERROR, 'reg_invalid_phone', array('phone'=>$phone));
		}

			//check phone is unique
		$user_id = get_uid_phone_server($phone);
		var_dump($user_id);
		if($user_id)
		{
			return $this->_log_reg_info(ERROR, 'reg_dup_phone', array('phone'=>$phone));
		}

		return $this->_log_reg_info(SUCCESS, 'reg_check_phone_success', array('phone'=>$phone));
	}

	public function check_email($email)
	{
		if(!is_email($email))
		{
			return $this->_log_reg_info(ERROR, 'reg_invalid_email', array('email'=>$email));
		}

		//check email is unique
		$email_count = $this->model_user->get_user_by_param('user', 'count', '*', array('email'=>$email));
		if($email_count >= 1)
		{
			return $this->_log_reg_info(ERROR, 'reg_dup_email', array('email'=>$email));
		}

		return $this->_log_reg_info(SUCCESS, 'reg_check_email_success', array('email'=>$email));
	}

	public function send_verify_email($email)
	{
		//未完成
		$this->load->library('mail');
		//$this->mail->send($email, )
	}

	function _log_reg_info($status, $msg_type, $info_arr=array(), $info_type='error')
	{
		$arr_return['status'] = $status;
		$arr_return['msg'] = $this->lang->line($msg_type);
		$arr_return['data'] = $info_arr;
		switch($info_type)
		{
			case 'error':
				log_message('error_nahao', json_encode($arr_return));
				break;
			case 'info':
				log_message('info_nahao', json_encode($arr_return));
				break;
			case 'debug':
				log_message('debug_nahao', json_encode($arr_return));
				break;
		}
		return $arr_return;
	}

	function _check_captcha($phone, $captcha)
	{
		$this->load->model('model/common/model_redis', 'redis');
		$this->redis->connect('login');	
		$redis_captcha = $this->cache->get($phone);

		return $captcha == $redis_captcha;
	}
}