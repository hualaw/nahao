<?php

class login extends NH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('business/common/business_login');
        $this->load->model('business/admin/business_user');
    }

	public function index()
	{
		$this->smarty->display('www/login/login.html');
	}
        
        /**
         * 忘记密码页
         */
        public function forget_pwd()
        {
            $this->smarty->display('www/login/forgetPwd.html');
        }
        
        /**
         * 找回密码
         */
        public function find_pwd()
        {
            #找回密码的方法 1:手机找回, 2:邮件找回
            $find_ways = $this->input->get('find_ways');
            if($find_ways) {
                $this->smarty->assign('find_ways', $find_ways);
                $this->smarty->display('www/login/findPwd.html');
            } else {
                $this->smarty->display('www/login/forgetPwd.html');
            }
        }
        
        /**
         * 重设密码
         */
        public function reset_pwd()
        {
            $new_pwd = trim($this->input->post('setPassword'));
            $phone = trim($this->input->post('phone_number'));
            if($new_pwd && $phone) {
                $user_id = get_uid_phone_server($phone);
                if($user_id) {
                    $a = $this->business_user->phone_reset_password($user_id, $new_pwd);
                    $this->smarty->display('www/login/setSuccess.html');
                }
            }
            $this->smarty->assign('phone_number', $phone);
            $this->smarty->display('www/login/setNewPwd.html');
        }
        
        /**
         * 发送找回密码用的手机验证码,找回密码用的验证码需要先验证输入的手机是否是用户绑定的手机
         * 发送验证码的逻辑是调用register控制器的send_captcha方法
         */
        public function send_reset_captcha()
        {
            $phone = trim($this->input->post('phone'));
            $type = trim($this->input->post('type'));
            $user_id = get_uid_phone_server($phone);
            if($user_id) {
                $post_fiedls = array('phone' => $phone, 'type' => $type);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, __HOST__ . '/register/send_captcha');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fiedls);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($ch, CURLOPT_TIMEOUT, 25);
                $result = curl_exec($ch);
                exit($result);
            }
            
            self::json_output(array('status' => 'error', 'msg' => 'Sorry! 没有找到您的用户信息'));
        }
        
        /**
         * 发送重设密码邮件到用户的验证邮箱
         */
        public function send_reset_email()
        {
            $email_address = trim($this->input->post('email'));
            $subject = '那好网重置密码邮件';
            $this->load->library('mail');
            $result = $this->mail->send($email_address, $subject, '<p>找回密码</p>');
            var_dump($result);die;
        }
        
        /**
         * 检查用户的验证邮箱
         */
        public function check_user_email()
        {
            $arr_return = array();
            //param 是validFrom的固定写法
            $email = $this->input->post('param');
            $user_info = $this->business_user->get_user_by_email($email);
            if($user_info['id']) {
                $arr_return = array('status' => 'y');
            } else {
                $arr_return = array('status' => 'n', 'info' => '该邮箱未于任何用户绑定');
            }
            self::json_output($arr_return);
        }

    public function submit()
    {
        $username = trim($this->input->post('username'));
        $password = trim($this->input->post('password'));

        $ret = $this->business_login->submit($username, $password);

        //var_dump($ret);

        $this->json_output($ret);
    }
}
