<?php

class Buesiness_Register extends NH_Model {

	public function register($phone, $email, $password, $captcha, $reg_type)
	{
		$arr_return = array('status'=>REG_SUCCESS, 'msg'=>'', 'data'=>array());

		$this->load->model('model/common/model_user');

		//check data
		if($reg_type == REG_TYPE_PHONE)
		{
			//check phone is invalid
			if(!is_mobile($phone))
			{
				return $this->_log_reg_info(REG_INVALID_PHONE, 'reg_invalid_phone', array('phone'=>$phone));
			}

			//check phone is unique
			$user_id = get_uid_phone_server($phone);
			if(!$user_id)
			{
				return $this->_log_reg_info(REG_DUP_PHONE, 'reg_dup_phone', array('phone'=>$phone));
			}

			//check captcha, TBD
		}
		else if($reg_type == REG_TYPE_EMAIL))
		{
			if(!is_email($email))
			{
				return $this->_log_reg_info(REG_INVALID_EMAIL, 'reg_invalid_email', array('email'=>$email));
			}

			//check email is unique
			$email_count = $this->model_user->get_user_by_param('user', 'count', '*', array('email'=>$email));
			if($email_count >= 1)
			{
				return $this->_log_reg_info(REG_DUP_EMAIL, 'reg_dup_email', array('email'=>$email));
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
			'register_time' => time();
			'register_ip' => $this->input->ip_address(),
			'source' => 1,
			'avatar' => '', //default avatar URI, TBD
			);
		
		$user_id = $this->model_user->create_user($user_table_data);

		//if insert table failed, the $uer_id is int zero
		if($user_id === 0)
		{
			return $this->_log_reg_info(REG_DB_ERROR, 'reg_db_error', $user_table_data);
		}

		//store the phone number to phone_server
		$add_ret = add_user_phone_server($user_id, $phone);
		if(!$add_ret)
		{
			return $this->_log_reg_info(REG_PHONE_SERVER_ERROR, 'reg_phone_server_error', array('user_id'=>$user_id, 'phone'=>$phone));
		}

		//set session
		$userdata = array(
			'phone_mask' => $phone_mask,
			'email' => $email,
			'user_type' => 1,
			'user_id' => $user_id,
			);
		$this->session->set_userdata($userdata);
		return $this->_log_reg_info(REG_SUCCESS, 'reg_success', array(), 'info');
	}


	public function send_verify_email($email)
	{
		//未完成
		$this->load->library('mail');
		$this->mail->send($email, )
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
}