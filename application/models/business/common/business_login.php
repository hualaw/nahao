<?php

class Business_Login extends NH_Model {

    public function submit($username, $sha1_password, $remb_me=1)
    {
        if(strlen($username) == 0 || strlen($sha1_password) == 0)
            return $this->_log_reg_info(ERROR, 'login_invalid_info', array('username'=>$username, 'sha1_password'=>$sha1_password));

        if(strpos($username, '@')!== false) $login_type = REG_LOGIN_TYPE_EMAIL;
        else $login_type = REG_LOGIN_TYPE_PHONE;


        $user_id = 0;
        $email = '';
        if($login_type == REG_LOGIN_TYPE_PHONE)
        {
            $check_ret = $this->check_phone_format($username);
            if($check_ret['status'] != SUCCESS) return $check_ret;

            $phone = $username;
            $user_id = get_uid_phone_server($phone);
        }else {
            $check_ret = $this->check_email_format($username);
            if($check_ret['status'] != SUCCESS) return $check_ret;

            $email = $username;
        }


        if($user_id || $email)
        {
            $this->load->model('model/common/model_user');
            $arr_where = array();
            if($user_id) $arr_where['id'] = $user_id;
            if($email) $arr_where['email'] = $email;

            $str_fields = 'id,nickname,phone_mask,password,salt,email,avatar,teach_priv,status,phone_verified';
            $ret_info = $this->model_user->get_user_by_param('user', 'list', $str_fields, $arr_where);

            //log_message('debug_nahao', "in business_login submit(), ret_info is: ".print_r($ret_info,1));
            if( !empty($ret_info)) $ret_info = $this->_filter_user_info($ret_info);
            //log_message('debug_nahao', "in business_login submit(), after filter_user_info is: ".print_r($ret_info,1));

            if(isset($ret_info['status']))
            {
                if( $ret_info['status'] == 'forbidden')
                    return $this->_log_reg_info(ERROR, 'login_no_previlege', array('username'=>$username, 'sha1_password'=>$sha1_password));
                unset($ret_info['status']);
            }

            if(!empty($ret_info) && isset($ret_info[0]))
            {
                $user_info = $ret_info[0];
                //var_dump($ret_info);

                //log_message('debug_nahao', 'IN '.__CLASS__.", function: ".__FUNCTION__.", salt: {$user_info['salt']}, sha1_pwd: $sha1_password, password:".$user_info['password']);
                $check_ret = check_sha1_password($user_info['salt'], $sha1_password, $user_info['password']);
                if($check_ret)
                {
                    $phone = $user_info['phone_mask'];//未经验证的邮箱选填的手机号存储在phone_mask字段里
                    if($user_id == 0 ) $user_id = $user_info['id']; //获取email注册用户的user_id
                    if($user_id && $user_info['phone_verified']) $phone = get_pnum_phone_server($user_id);

                    //log_message('debug_nahao', "In business_login, user_id is $user_id , phone is $phone");

                    //set session data
                    $phone_mask = (strpos($user_info['phone_mask'], '*') !== false) ? $user_info['phone_mask'] : phone_blur($user_info['phone_mask']);
                    $this->set_session_data($user_info['id'], $user_info['nickname'], $user_info['avatar'],
                        $phone, $phone_mask, $user_info['email'], $login_type, $user_info['teach_priv'], $remb_me);
                    return $this->_log_reg_info(SUCCESS, 'login_success', array(), 'info');
                }
                else
                {
                    $info_arr = array(
                        'username' => $username,
                        'input_sha1_password' => $sha1_password,
                        'sys_password' => $user_info['password'],
                        'salt' => $user_info['salt'],
                    );
                    return $this->_log_reg_info(ERROR, 'login_password_verify_failed', $info_arr);
                }
            }
            else
            {
                $info_arr = array(
                    'username'=> $username,
                    'input_sha1_password' => $sha1_password,
                    'user_id' => $user_id,
                    'email' => $email,
                );
                return $this->_log_reg_info(ERROR, 'login_unregister_username', $info_arr);
            }
        }
        else
        {
            $info_arr = array(
                'username'=> $username,
                'input_sha1_password' => $sha1_password,
            );
            return $this->_log_reg_info(ERROR, 'login_unregister_username', $info_arr);
        }
    }

    protected function _filter_user_info($ret_info)
    {
        foreach($ret_info as $i => $one_info)
        {
            if(isset($one_info['status']))
            {
                if($one_info['status'] == 0)
                {
                    unset($ret_info[$i]);
                }
                else if($one_info['status'] == 1)
                {
                    $new_info = array();
                    $new_info['status'] = 'ok';
                    $new_info[0] = $one_info;
                    return $new_info;
                }
            }
        }

        if(empty($ret_info)) $ret_info['status'] = 'forbidden';
        else $ret_info['status'] = 'ok';

        return $ret_info;
    }

    /**
     * 管理员按老师身份登陆那好学生端
     * jason
     */ 
    public function login_without_pwd($user_id,$code){
    	if(empty($user_id)){exit('用户id不能为空');}
    	# 读取用户信息
    	$this->load->model('model/common/model_user');
    	$user_info = $this->business_user->get_user_detail($user_id);
    	/**验证规则**/
    	#时效参数
		$time = date('d',time()).'-'.date('H',time()).'-'.date('i',time());
		$sha1_time = sha1($time);
		#加密规则
		$name =  '_HAHA_NAHAO_'.$user_info['nickname'].'^$@&!#'.$user_id.$sha1_time;
		#生成加密
		$sha1_code = sha1($name);
		#验证
    	if($code==$sha1_code){
    		# 登陆信息
	    	$remb_me = 1;
	    	$login_type = REG_LOGIN_TYPE_EMAIL;
	    	$phone = $user_info['phone_mask'];
	    	if($user_id && $user_info['phone_verified']) $phone = get_pnum_phone_server($user_id);
//	    	$phone_mask = '';
	    	$phone_mask = (strpos($user_info['phone_mask'], '*') !== false) ? $user_info['phone_mask'] : phone_blur($user_info['phone_mask']);
	    	# 写入登陆信息
	    	$this->set_session_data($user_info['user_id'], $user_info['nickname'], $user_info['avatar'],
	        	$phone, $phone_mask, $user_info['email'], $login_type, $user_info['teach_priv'], $remb_me);
	    	return $this->_log_reg_info(SUCCESS, 'login_success', array(), 'info');
    	}else{
    		exit('<script>alert("警告：加密通道已过期或者身份验证失败！！");window.location.href="'.student_url().'";</script>');
    	}
    	
    }
}