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
		//$name = trim($this->input->post('name'));
		$param = trim($this->input->post('param'));

		echo parent::json_output($this->business_register->check_phone($param));
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

		$reg_ret = $this->business_register->submit($phone, $email, $password, $captcha, $reg_type);

		echo parent::json_output($reg_ret);
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
		$msg = $verify_code.$this->lang->line('reg_verify_phone_msg');
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

    public function reg_success()
    {
        $this->smarty->display('www/login/regSuccess.html');
    }


    //for tizi user
    public function login_after()
    {
        $this->smarty->display('www/login/loginAfter.html');
    }

    public function submit_personal_info()
    {
        $input_names = array('email', 'nickname', 'area', 'grade', 'realname', 'gender', 'focus_subjects', 'school');
        foreach($input_names as $input_name)
        {
            $$input_name = $this->_check_input($input_name);
        }

        $user_id = $this->session->userdata('user_id');
        $user_info_arr = array(
            'user_id'=> $user_id,
            'realname' => $realname,
            'gender'   => $gender,
            'area'     => $area, //area id
            'grade'    => $grade, // grade id
            'school'   => $school, //school id
        );

        //TBD, to handle email

        //create user_info table record
        $this->load->model('model/common/model_user');
        $this->model_user->create_user_info($user_info_arr);

        if(!empty($focus_subjects))
        {
            //create student_subject table record
            $this->load->model('model/student/model_student_subject');
            $focus_subject_arr = explode(",", $focus_subjects);
            $this->model_student_subject->add($user_id, $focus_subject_arr);
        }

        if(!empty($nickname))
        {
            //update nickname
            $arr_params = array('nickname'=> $nickname);
            $arr_where = array('id'=> $user_id);
            $this->model_user->update_user($arr_params, $arr_where);

            //update nickname in session data
            $this->session->set_userdata('nickname', $nickname);
        }

        //TBD, assign variables
        $this->smarty->display('www/studentHomePage/index.html');
    }

	function _log_reg_info($status, $msg_type, $info_arr=array(), $info_type='error')
	{
		$arr_return['status'] = $status;
		$arr_return['msg'] = $this->lang->line($msg_type);
		$arr_return['data'] = $info_arr;
		switch($info_type)
		{
			case 'error':
				log_message('ERROR_NAHAO', json_encode($arr_return));
				break;
			case 'info':
				log_message('INFO_NAHAO', json_encode($arr_return));
				break;
			case 'debug':
				log_message('DEBUG_NAHAO', json_encode($arr_return));
				break;
		}
		return $arr_return;
	}
    
    /**
     * 检查验证码是否有效
     * ajax interface
     */
    public function check_captcha()
    {
        $phone = trim($this->input->post('phone'));
        $verify_code = intval($this->input->post('verify_code'));
        $code_type = intval($this->input->post('code_type'));
        $exists = $this->business_register->_check_captcha($phone, $verify_code, $code_type);
        $arr_info['effective'] = $exists ? 1 : 0;
        
        self::json_output($arr_info);
    }

    function _check_input($field_name)
    {
        return trim($this->input->post($field_name));
    }
    
    public function check_phones()
    {
        $name = trim($this->input->post('name'));
        $param = trim($this->input->post('param'));
        $result = $this->business_register->check_phone($param);
        if($result['status']=='ok') {
            $arr_return = array('status' => 'y', 'info' => $result['msg']);
        } else {
            $arr_return = array('status' => 'n', 'info' => $result['msg']);
        }
        self::json_output($arr_return);
    }
}
