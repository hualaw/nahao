<?php

class register extends NH_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('business/common/business_register');
	}

	public function index()
	{
		if($this->session->userdata('user_id'))
		{
			$this->smarty->assign('nickname', $this->session->userdata('nickname'));
			$this->smarty->display('www/index.html');		
		}
		else
		{
			$this->smarty->display('www/login/reg.html');
		}
	}

	public function check_nickname()
	{
		$nickname = trim($this->input->get('nickname'));

		echo parent::json_output($this->business_register->check_nickname($nickname));
	}

	public function check_phone()
	{
		$phone = trim($this->input->get('phone'));

		echo parent::json_output($this->business_register->check_phone($phone));
	}

	public function check_email()
	{
		$email = trim($this->input->get('email'));

		echo parent::json_output($this->business_register->check_email($email));
	}

	public function submit()
	{
		$phone = $this->input->post('phone');
		$email = $this->input->post('email');
		$password = $this->input->post('password');
	}

	//ajax interface
	public function send_captcha($phone)
	{
		$this->load->library('sms');
		$this->sms->setPhoneNums($phone);

		$this->load->helper('string');
		$verify_code = random_string('nozero', 4);
		$this->sms->setContent($this->lang->line('reg_verify_phone_msg').$verify_code);
		$send_ret = $this->sms->send();
		if($send_ret)
		{
			//store the captcha into redis
		$this->load->model('model/common/redis_model', 'redis');
		$this->redis->connect('login');

		//store the key-value pair
		$this->cache->save('cache_test_key', '234');

		echo $this->cache->get('cache_test_key');
			$send_info = $this->_log_reg_info(REG_SEND_VERIFY_CODE_SUCCESS, 'reg_send_verify_code_success');
		}
		else
		{
			$send_info = $this->_log_reg_info(REG_SEND_VERIFY_CODE_FAILED, 'reg_send_verify_code_failed', array('phone'=>$phone, 'verify_code'=>$verify_code));
		}
		self::json_output($send_info);
	}

	public function verify_email()
	{

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
