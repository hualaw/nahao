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
        if($this->is_login) redirect('/');
		if($this->session->userdata('user_id'))
		{
			$this->smarty->assign('nickname', $this->session->userdata('nickname'));
			$this->smarty->display('www/studentHomePage/index.html');
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
		$sha1_password = trim($this->input->post('password'));
		$captcha = trim($this->input->post('captcha'));

		if(empty($phone)) $reg_type = REG_LOGIN_TYPE_EMAIL;
		else $reg_type = REG_LOGIN_TYPE_PHONE;

		if($reg_type == REG_LOGIN_TYPE_EMAIL) $phone = $ephone;////email注册时选填的手机号

		$reg_ret = $this->business_register->submit($phone, $email, $sha1_password, $captcha, $reg_type);

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

    public function submit_personal_info()
    {
        $input_names = array('email', 'nickname', 'province', 'city', 'area', 'grade', 'realname', 'gender', 'selected_subjects', 'school_id',
                              'schoolname', 'province_id', 'city_id', 'area_county_id', 'school_type');
        foreach($input_names as $input_name)
        {
            $$input_name = $this->_check_input($input_name);
        }

        $user_id = $this->session->userdata('user_id');
        $user_info_arr = array(
            'user_id'=> $user_id,
            'realname' => $realname,
            'gender'   => $gender,
            'province' => $province,
            'city'     => $city,
            'area'     => $area, //area id
            'grade'    => $grade, // grade id
            'school'   => $school_id, //school id
        );
        $user_info_arr = array_filter($user_info_arr);
        if(!empty($schoolname)) {
            //province_id 是学校省份Id 上面的province是用户的地区, 两者可能不一样
            $this->load->model('business/common/business_school');
            $custom_school['schoolname'] = $schoolname;
            $custom_school['province_id'] = $province_id;
            $custom_school['city_id'] = $city_id;
            $custom_school['county_id'] = $area_county_id;
            $custom_school['school_type'] = $school_type;
            $new_school_id = $this->business_school->add_custom_school($custom_school);
            $user_info_arr['school'] = intval($new_school_id);
            $user_info_arr['custom_school'] = $user_info_arr['school'] ? 1 : 0;
        } else if(!empty($school_id) && empty($schoolname)){
            $user_info_arr['custom_school'] = 0;
        }
        //create user_info table record
        $this->load->model('model/common/model_user');
        $this->model_user->update_user_info($user_info_arr, array('user_id'=> $user_id));
        if(!empty($selected_subjects))
        {
            //create student_subject table record
            $this->load->model('business/common/business_user');
            $this->business_user->update_user_subject($selected_subjects, $user_id, 'student');
        }

        //update nickname and email
        !empty($nickname) && $arr_params['nickname'] = $nickname;
        !empty($email) && $arr_params['email'] = $email;
        $arr_where = array('id'=> $user_id);
        $this->model_user->update_user($arr_params, $arr_where);

        //update nickname in session data
        !empty($nickname) && $this->session->set_userdata('nickname', $nickname);
        !empty($email) && $this->session->set_userdata('email', $email);

        $arr_return = array('status' => SUCCESS, 'info' => '提交成功', 'url' => student_url());
        self::json_output($arr_return);
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
        if($arr_info['effective']) {
            //验证码有效,把这个手机号存入到session
            $this->session->set_userdata('reset_pwd_phone', $phone);
        }
        self::json_output($arr_info);
    }

    function _check_input($field_name)
    {
        return trim($this->input->post($field_name));
    }
    
    public function check_phones()
    {
        $name = trim($this->input->post('name'));
        $param = trim($this->input->post('phone'));
        if($param == $this->session->userdata('phone')) {
            self::json_output(array('status' => 'ok'));//这块是为了让前台未更改手机号时验证通过
        }
        $result = $this->business_register->check_phone($param);
        if($result['status']=='ok') {
            $arr_return = array('status' => 'ok', 'info' => $result['msg']);
        } else {
            $arr_return = array('status' => 'error', 'info' => $result['msg']);
        }
        self::json_output($arr_return);
    }
}
