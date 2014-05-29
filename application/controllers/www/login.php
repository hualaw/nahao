<?php

class login extends NH_Controller
{

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
         * 重新设置密码
         */
        public function reset_pwd()
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
         * 用手机找回密码
         */
        public function phone_find_pwd()
        {
            $this->smarty->assign('find_ways', 'phone');
            $this->smarty->display('www/login/findPwd.html');
        }
        
        /**
         * 用邮箱邮箱找回密码
         */
        public function email_find_pwd()
        {
            $this->smarty->assign('find_ways', 'email');
            $this->smarty->display('www/login/findPwd.html');           
        }
}
