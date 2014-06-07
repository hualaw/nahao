<?php

class login extends NH_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('business/common/business_login');
        $this->load->model('business/common/business_user');
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
            //手机找回密码部分
            $new_pwd = trim($this->input->post('setPassword'));
            $phone = trim($this->input->post('phone_number'));
            $code = trim($this->input->get('code'));//邮箱找回密码的加密口令
            $this->smarty->assign('code', $code);
            $this->smarty->assign('phone_number', $phone);
            if($new_pwd && $phone) {
                $user_id = get_uid_phone_server($phone);
                if($user_id) {
                    $a = $this->business_user->phone_reset_password($user_id, $new_pwd);
                    $this->smarty->display('www/login/setSuccess.html');die;
                }
            }
            
            //邮箱找回密码部分
            if($code) {
                $this->load->library('encrypt');
                $decrypted_code = $this->encrypt->decode($code, 'nahao');
                $decrypted_data = explode('|', $decrypted_code);
                if(count($decrypted_data) < 2 || ($decrypted_data[1] - time()) > 86400) {
                    exit('该链接已经失效,请重新发送邮件并及时查收');
                }
                list($user_email, $post_time) = $decrypted_data;
                if($new_pwd) {
                    $user_info = $this->business_user->get_user_by_email($user_email);
                    if($user_info['id']) {
                        $this->business_user->phone_reset_password($user_info['id'], $new_pwd);
                        $this->smarty->display('www/login/setSuccess.html');die;
                    }
                }
            }
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
            $this->load->library('encrypt');
            $email_address = trim($this->input->post('email'));
            $subject = '那好网重置密码邮件';
            $code = $email_address . '|' . time();
            $encrypted_code = urlencode($this->encrypt->encode($code, 'nahao'));
            $reset_pwd_url = __HOST__ . '/login/reset_pwd?code='.$encrypted_code;
            $this->load->library('mail');
            $mail_content = '<body style="margin-bottom: 0px; margin-top: 0px; padding-bottom: 0px; padding-top: 0px;">尊敬的用户：<br>
                            您好！您已申请那好网重置密码服务。<br>
                            本邮件24小时内有效，请点击如下链接来完成密码重置：<br><a href="http://'. $reset_pwd_url .'" target="_blank">'. __HOST__ .'/logi/reset_pwd?code='. $reset_pwd_url .'</a><br>
                            如果浏览器不能自动打开，请您把地址复制到浏览器地址栏中手动打开。<br><br>欢迎您使用那好网（www.nahao.com）！<br>
                            本邮件由那好系统自动发出，请勿直接回复！若非本人操作，请忽略此邮件，由此给您带来的不便请谅解！<br>
                            感谢您对那好网的支持！
                            </body>';
            $result = $this->mail->send($email_address, $subject, $mail_content);
            if($result['ret'] == 1) {
                $arr_return = array('status' => 1, 'msg' => '重设密码的邮箱已经发送到您的邮箱：'. $email_address . '请您注意查收');
            } else {
                $arr_return = array('status' => 2, 'msg' => '服务器繁忙请稍后重试');
            }
            self::json_output($arr_return);
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
                $arr_return = array('status' => 'y', 'info' => '验证通过');
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

        //log_message('debug_nahao', print_r($this->session->all_userdata(),1)."\n");

        $this->json_output($ret);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        header("Location: ".site_url());
    }
}
