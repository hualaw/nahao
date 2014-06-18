<?php

class Business_Login extends NH_Model {

    public function submit($username, $password, $remember_me='')
    {
        if(strlen($username) == 0 || strlen($password) == 0)
            return $this->_log_reg_info(ERROR, 'login_invalid_info', array('username'=>$username, 'password'=>$password));

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
            $arr_where['status'] = 1;
            if($user_id) $arr_where['id'] = $user_id;
            if($email) $arr_where['email'] = $email;

            $str_fields = 'id,nickname,phone_mask,password,salt,email,avatar,teach_priv';
            $ret_info = $this->model_user->get_user_by_param('user', 'list', $str_fields, $arr_where);
            if(!empty($ret_info) && isset($ret_info[0]))
            {
                $user_info = $ret_info[0];
                //var_dump($ret_info);

                $check_ret = check_password($user_info['salt'], $password, $user_info['password']);
                if($check_ret)
                {
                    //set session data
                    $nickname = '';
                    if(!$user_info['nickname'])
                    {
                        if($login_type == REG_LOGIN_TYPE_PHONE) $nickname = $user_info['phone_mask'];
                        else $nickname = $email;
                    }

                    $phone = '';
                    if($user_id) $phone = get_pnum_phone_server($user_id);

                    $this->set_session_data($user_info['id'], $nickname, $user_info['avatar'],
                        $phone, $user_info['phone_mask'], $user_info['email'], $login_type, $user_info['teach_priv']);

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
                'input_password' => $password,
            );
            return $this->_log_reg_info(ERROR, 'login_unregister_username', $info_arr);
        }
    }
}