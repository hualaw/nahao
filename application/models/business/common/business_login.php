<?php

class Business_Login extends NH_Model {

    public function submit($username, $password)
    {

        if(strlen($username) == 0 || strlen($password) == 0)
            return $this->_log_reg_info(ERROR, 'login_invalid_info', array('username'=>$username, 'password'=>$password));

        if(strpos($username, '@')!== false) $login_type = LOGIN_TYPE_EMAIL;
        else $login_type = LOGIN_TYPE_PHONE;

        $user_id = 0;
        $email = '';
        if($login_type == LOGIN_TYPE_PHONE)
        {
            $check_ret = $this->check_phone($username);
            if($check_ret['status'] != SUCCESS) return $check_ret;

            $phone = $username;
            $user_id = get_uid_phone_server($phone);
        }else {
            $check_ret = $this->check_email($username);
            if($check_ret['status'] != SUCCESS) return $check_ret;

            $email = $username;
        }

        if($user_id || $email)
        {
            $this->load->model('model/common/model_user');
            $arr_where = array();
            $arr_where['status'] = 1;
            if($user_id) $arr_where['id'] = $user_id;
            if($email) $arr_where['email'] = $email;

            $str_fields = 'id,nickname,phone_mask,password,salt';
            $ret_info = $this->model_user->get_user_by_param('user', 'list', $str_fields, $arr_where);
            if(!empty($ret_info) && isset($ret_info[0]))
            {
                $user_info = $ret_info[0];
                //var_dump($ret_info);

                $check_ret = check_password($user_info['salt'], $password, $user_info['password']);
                if($check_ret)
                {
                    //set session data
                    if(!$user_info['nickname'])
                    {
                        if($login_type == LOGIN_TYPE_PHONE) $nickname = $user_info['phone_mask'];
                        else $nickname = $email;
                    }

                    $userdata = array(
                        'nickname' => $nickname,
                        'avatar' => '',
                        'user_id' => $user_info['id'],
                    );
                    $this->session->set_userdata($userdata);
                    return $this->_log_reg_info(SUCCESS, 'login_success', array(), 'info');
                }
                else
                {
                    $info_arr = array(
                        'username' => $username,
                        'input_password' => $password,
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
                    'input_password' => $password,
                );
                return $this->_log_reg_info(ERROR, 'login_unregister_username', $info_arr);
            }
        }
        else
        {
            $info_arr = array(
                'username'=> $username,
                'input_password' => $password,
            );
            return $this->_log_reg_info(ERROR, 'login_unregister_username', $info_arr);
        }
    }

    public function check_phone($phone)
    {
        //check phone is invalid
        if(!is_mobile($phone))
        {
            return $this->_log_reg_info(ERROR, 'rl_invalid_phone', array('phone'=>$phone));
        }

        return $this->_log_reg_info(SUCCESS, 'rl_check_phone_success', array('phone'=>$phone));
    }

    public function check_email($email)
    {
        if(!is_email($email))
        {
            return $this->_log_reg_info(ERROR, 'rl_invalid_email', array('email'=>$email));
        }

        return $this->_log_reg_info(SUCCESS, 'rl_check_email_success', array('email'=>$email));
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