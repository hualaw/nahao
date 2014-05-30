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
		$nickname = trim($this->input->post('nickname'));

		echo parent::json_output($this->business_register->check_nickname($nickname));
	}

	public function check_phone()
	{
		$phone = trim($this->input->post('phone'));

		echo parent::json_output($this->business_register->check_phone($phone));
	}

	public function check_email()
	{
		$email = trim($this->input->post('email'));

		echo parent::json_output($this->business_register->check_email($email));
	}

	public function submit()
	{
		$phone = trim($this->input->post('phone'));
		$ephone = trim($this->input->post('ephone'));//email注册时选填的手机号
		$email = trim($this->input->post('email'));
		$password = trim($this->input->post('password'));
		$captcha = trim($this->input->post('captcha'));

		if(empty($phone)) $reg_type = REG_TYPE_EMAIL;
		else $reg_type = REG_TYPE_PHONE;

		if($reg_type == REG_TYPE_EMAIL) $phone = $ephone;////email注册时选填的手机号

		$register_ret = $this->business_register->register($phone, $email, $password, $captcha, $reg_type);

		echo parent::json_output($register_ret);
	}

	//ajax interface
	public function send_captcha()
	{
		$phone = trim($this->input->post('phone'));
        $type = trim($this->input->post('type')); //1,注册；2，订单绑定手机；3，找回密码
		$this->load->library('sms');
		$this->sms->setPhoneNums($phone);

		$this->load->helper('string');
		$verify_code = random_string('nozero', 4);
		$msg = $this->lang->line('reg_verify_phone_msg').$verify_code;
		$this->sms->setContent($msg);
        $create_time = time();
		$send_ret = $this->sms->send();
        //$send_ret['error'] = 'Ok';

		$info = array(  'phone' => $phone,
						'verify_code'=>$verify_code,
						'msg'=>$msg,
                        'create_time'=>$create_time,
                        'type' => $type,
					);
		//print_r($send_ret);
		if($send_ret['error'] == 'Ok')
		{
			//store the captcha into redis
			$this->load->model('model/common/model_redis', 'redis');
			$this->redis->connect('login');

			//store the phone-verify code list to list
			$this->cache->redis->lpush($phone, json_encode(array(
                        't'=>$type,
                        'vc'=>$verify_code,
                        'et'=>$create_time + REDIS_VERIFY_CODE_EXPIRE_TIME
                    )));

			$send_info = $this->_log_reg_info(SUCCESS, 'reg_send_verify_code_success', $info);
		}
		else
		{
			$tmp_array = array_merge($info, $send_ret);
			$send_info = $this->_log_reg_info(ERROR, 'reg_send_verify_code_failed', $tmp_array);
		}

        //unset($send_info['data']);
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
