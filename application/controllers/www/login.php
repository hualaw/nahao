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
        //登录之后，要跳转到首页
        if($this->is_login) redirect('/');
		$this->smarty->display('www/login/login.html');
	}


    /*
     * 完善个人信息页
     */
    public function perfect()
    {
        /*未登录的时候跳转到登录页*/
        if(!$this->is_login)
        {
            redirect('/login');
        }
        $this->load->model('business/admin/business_lecture');
        $this->load->model('business/common/business_subject');
        #省和直辖市
        $province = $this->business_lecture->all_province();
        #科目
//        $subjects = $this->business_subject->get_subjects();
        $subjects = $this->config->item('education_subject');
        unset($subjects[1][0]);
        unset($subjects[2][0]);
        #学科辅导科目
        $xueke_subjects = $subjects[1];
        #素质教育科目
        $suzhi_subjects = $subjects[2];
        #年纪
        $grades = $this->config->item('grade');
        $this->smarty->assign('grades', $grades);
        $gender = $this->config->item('gender');
        $this->smarty->assign('province', $province);
        $this->smarty->assign('xueke_subjects', $xueke_subjects);
        $this->smarty->assign('suzhi_subjects', $suzhi_subjects);
        $this->smarty->assign('gender', $gender);
        $this->smarty->display('www/login/loginAfter.html');
    }

    /**
     * 忘记密码页
     */
    public function forget_pwd()
    {
        //登录之后，要跳转到首页
        if($this->is_login) redirect('/');
        $this->smarty->display('www/login/forgetPwd.html');
    }
    /**
     * 找回密码
     */
    public function find_pwd()
    {
        //登录之后，要跳转到首页
        if($this->is_login) redirect('/');

        #找回密码的方法 1:手机找回, 2:邮件找回
        $find_ways = $this->input->get('find_ways');
        if($find_ways) {
            #把用户登陆信息清掉,确保是在无登陆状态下找回密码
            $this->session->sess_destroy();
            $this->smarty->assign('find_ways', $find_ways);
            $this->smarty->display('www/login/findPwd.html');
        } else {
            $this->smarty->display('www/login/forgetPwd.html');
        }
    }

    /**
     * 重设密码时ajax请求的接口
     */
    public function ajax_reset_pwd()
    {
        $arr_return = array('status' => ERROR, 'info' => '参数不正确', 'url' => student_url());
        $new_pwd = trim($this->input->post('encrypt_set_password'));
        $phone = $this->session->userdata('reset_pwd_phone');
        $code = trim($this->input->post('code'));//邮箱找回密码的加密口令
        if(!$code && !$phone) {
            //上边两个都没有是非法请求,直接跳转首页
            self::json_output($arr_return);
        }
        
        //手机找回密码部分
        if($new_pwd && $phone) {
            $user_id = get_uid_phone_server($phone);
            if($user_id) {
                $this->business_user->reset_password($user_id, $new_pwd);
                $this->session->set_userdata('reset_pwd_phone', 0);
                self::json_output(array('status' => SUCCESS, 'url' => student_url() . 'login/reset_pwd_success?find_ways=1'));
            } else {
                $arr_return['info'] = '无效的用户';
                self::json_output($arr_return);
            }
        }
                    
        //邮箱找回密码部分
        if($code) {
            $this->load->library('encrypt');
            $encrytion_key = $this->config->item('encryption_key');
            $user_email = $this->encrypt->decode($code, $encrytion_key);
            $this->load->model('model/common/model_redis', 'redis');
			$this->redis->connect('login');
            $email_record_str = $this->cache->redis->get(md5($user_email));
            $email_record_arr = json_decode($email_record_str, true);
            if(empty($email_record_arr)) {
                $arr_return['info'] = '邮件已失效';
                self::json_output($arr_return);
            }

            if($new_pwd) {
                $user_info = $this->business_user->get_user_by_email($user_email);
                if(isset($user_info['id'])) {
                    $this->business_user->reset_password($user_info['id'], $new_pwd);
                    #clear redis cache after user reset password through email
                    $this->cache->redis->delete(md5($user_email));
                    self::json_output(array('status' => SUCCESS, 'url' => student_url() . 'login/reset_pwd_success?find_ways=2'));
                } else {
                    $arr_return['info'] = '无效的用户';
                    self::json_output($arr_return);
                }
            }
        }
        
        self::json_output($arr_return);
    }

    /**
         * 重设密码
     */
    public function reset_pwd()
    {
        //登录之后，要跳转到首页
        if($this->is_login) redirect('/');

        $phone = $this->session->userdata('reset_pwd_phone');
        $code = trim($this->input->get('code'));//邮箱找回密码的加密口令
        if(!$code && !$phone) {
            //上边两个都没有是非法请求,直接跳转首页
            redirect(student_url());
        }
        if($code) {
            $this->load->library('encrypt');
            $encrytion_key = $this->config->item('encryption_key');
            $user_email = $this->encrypt->decode($code, $encrytion_key);
            $this->load->model('model/common/model_redis', 'redis');
			$this->redis->connect('login');
            $email_record_str = $this->cache->redis->get(md5($user_email));
            $email_record_arr = json_decode($email_record_str, true);
            if(empty($email_record_arr)) {
                redirect(student_url() . '/login/find_pwd?find_ways=2');
            }
        }
        $this->smarty->assign('code', urlencode($code));
        $this->smarty->assign('phone_number', $phone);
        $this->smarty->display('www/login/setNewPwd.html');
    }
    
    /**
     * 重设密码成功页面
     */
    public function reset_pwd_success()
    {
        $find_ways = intval($this->input->get('find_ways'));
        $this->smarty->assign('find_ways', $find_ways);
        $this->smarty->display('www/login/setSuccess.html');
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
            $send_info = $this->_send_captcha($phone, $type);
            self::json_output($send_info);
        }

        self::json_output(array('status' => 'error', 'msg' => '您的手机号尚未绑定，请核对后重新输入。'));
    }
        
    /**
     * 发送重设密码邮件到用户的验证邮箱
     */
    public function send_reset_email()
    {
        $email_address = trim($this->input->post('email'));
        $send_result = array();
        if(is_email($email_address)) {
            $this->load->model('model/common/model_redis', 'redis');
			$this->redis->connect('login');
            $email_record_str = $this->cache->redis->get(md5($email_address));
            $email_record_arr = json_decode($email_record_str, true);
            if(isset($email_record_arr['send_time']) && time() - $email_record_arr['send_time'] < 60) {
                $send_result = array('status' => ERROR, 'info' => '邮递员正在忙着派送您的邮件, 请休息一分钟再来召唤他');
                self::json_output($send_result);
            }
            
            $this->load->library('encrypt');
            $subject = '那好网重置密码邮件';
            $encryption_key = $this->config->item('encryption_key');
            $encrypted_code = urlencode($this->encrypt->encode($email_address, $encryption_key));
            $reset_pwd_url = student_url() . 'login/reset_pwd?code='.$encrypted_code;
            $mail_content = '<body style="margin-bottom: 0px; margin-top: 0px; padding-bottom: 0px; padding-top: 0px;">尊敬的用户：<br>
                            您好！您已申请那好网重置密码服务。<br>
                            本邮件24小时内有效，请点击如下链接来完成密码重置：<br><a href="'. $reset_pwd_url .'" target="_blank">'. $reset_pwd_url .'</a><br>
                            如果浏览器不能自动打开，请您把地址复制到浏览器地址栏中手动打开。<br><br>欢迎您使用那好网（www.nahao.com）！<br>
                            本邮件由那好系统自动发出，请勿直接回复！若非本人操作，请忽略此邮件，由此给您带来的不便请谅解！<br>
                            感谢您对那好网的支持！
                            </body>';
            $success_msg = '重设密码的邮件已经发送到您的邮箱：'. $email_address . '请您注意查收';
            $send_result = $this->_send_email($email_address, $subject, $mail_content, $success_msg);
            
        }
        self::json_output($send_result);
    }
        
    /**
     * 检查用户的验证邮箱
     */
    public function check_user_email()
    {
        $arr_return = array();
        //param 是validFrom的固定写法
        $email = $this->input->post('email');
        $user_info = $this->business_user->get_user_by_email($email);
        if(isset($user_info['id'])) {
            $arr_return = array('status' => SUCCESS, 'info' => '验证通过');
        } else {
            $arr_return = array('status' => ERROR, 'info' => '该邮箱未于任何用户绑定');
        }
        self::json_output($arr_return);
    }
        
    public function submit()
    {
        //登录之后，要跳转到首页
        if($this->is_login)
        {
            $ret = array(
                'status' => 'ok',
                'data' => array('redirect_url'=>site_url()),
                'msg' => '',
            );
            $this->json_output($ret);
        }

        $username = trim($this->input->post('username'));
        $sha1_password = trim($this->input->post('password'));
        $redirect_url = trim($this->input->post('redirect_url'));
        $rembme = trim($this->input->post('rembme'));

        if($rembme == 'on') $remb_me = 1;
        else $remb_me = 0;

        $ret = $this->business_login->submit($username, $sha1_password, $remb_me);

        if(isset($ret['data']))
        {
            $ret['data']['redirect_url'] = $redirect_url == "" ? site_url() : $redirect_url;
            //修改密码不跳回到原来的页面
            if(strpos($redirect_url, "/login/reset_pwd_success") !== false)
            {
                $ret['data']['redirect_url'] = site_url();
            }
        }

        //log_message('debug_nahao', print_r($this->session->all_userdata(),1)."\n");

        $this->json_output($ret);
    }

    public function logout()
    {
        //未登录时，要跳转到首页
        if(!$this->is_login) redirect('/');
        $this->session->sess_destroy();
        header("Location: ".student_url());
    } 
    
    /**
     * 检查昵称是否可用
     */
    public function check_unique_nickname()
    {
        $nickname = trim($this->input->post('nickname'));
        $arr_return = array('status' => ERROR);
        if($nickname) {
            $length_ret = check_name_length($nickname);
            if(!$length_ret) {
                $arr_return['info'] = '昵称要控制在4~25个字符,一个汉字按两个字符计算';
            } else {
                $userdata = $this->business_user->get_user_by_nickname($nickname);
                if(isset($userdata['id'])) {
                    $arr_return['info'] = '该昵称已被占用';
                } else {
                    $arr_return = array('status' => SUCCESS, 'info' => '昵称可用');
                }
            }
        }
        
        self::json_output($arr_return);
    }
    
    /**
     * 检查用户真实姓名的长度
     */
    public function check_realname_length()
    {
        $arr_return = array('status' => ERROR);
        $realname = trim($this->input->post('realname'));
        $check_ret = check_name_length($realname);
        if(!$check_ret) {
            $arr_return['info'] = '真实姓名要控制在4~25个字符,一个汉字按两个字符计算';
        } else {
            $arr_return['status'] = SUCCESS;
            $arr_return['info'] = '验证通过';
        }
        
        self::json_output($arr_return);
    }
}
