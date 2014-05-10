<?php
	class Check_teacher extends NH_Model
	{
		/**
	     * 验证老师昵称合法性
	     * @param string nickname
	     * @return boolean true flase
	     * @author shangshikai@nahao.com
	     */
		public function check_nickname($nickname)
		{
			if(trim($nickname)=="")
			{
				return FALSE;
			}
			$arr=$this->db->get_where("teacher",array("nickname"=>$nickname))->row_array();
			if(empty($arr)==TRUE)
			{
				if(strlen($nickname)>30)
					return FALSE;
				else
					return TRUE;
			}
			else
				return FALSE;
		}
		/**
	     * 收集验证老师电话
	     * @param string $phone
	     * @return boolean true flase
	     * @author shangshikai@nahao.com
	     */
		public function check_phone($phone)
		{
			$pattern = '/^(13[0-9]{1}[0-9]{8}|15[0-9]{1}[0-9]{8}|18[0-9]{1}[0-9]{8}|14[0-9]{9}|17[078][0-9]{8})$/';
		    if(preg_match($pattern, $mobilephone))
		        {
		        	//此处验证电话是否已存在
		        }
		        else
		            return FALSE;
		}
		/**
	     * 收集验证老师密码
	     * @param string $password,$againpassword
	     * @return boolean true flase
	     * @author shangshikai@nahao.com
	     */
		public function check_password($password,$againpassword)
		{
			if(trim($password)=="" || strlen($password)<8)
			{
				return FALSE;
			}
			if($password==$againpassword)
			{
				return TRUE;
			}
			else
				return FALSE;
		}
		/**
	     * 收集验证老师邮箱
	     * @param string $email
	     * @return boolean true flase
	     * @author shangshikai@nahao.com
	     */
		public function check_email($email)
		{
			$pattern="/^[a-z0-9]{1}[-_\.|a-z0-9]{0,19}@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{0,3}([\.][a-z]{1,3})?$/i";
			if(preg_match($pattern,$email))
			{
				$arr=$this->db->get_where("student",array("email"=>$email))->row_array();
				if(empty($arr)==TRUE)
					return TRUE;
				else
					return FALSE;
			}
			else
				return FALSE;
		}
		/**
	     * 老师信息通过验证
	     * @param array $post
	     * @return boolean true flase $c
	     * @author shangshikai@nahao.com
	     */
		public function success_register($post)
		{
			$post['salt']=uniqid();
			$post['password']=sha1(sha1($post['password']).$post['salt']);
			$post["status"]=0;
			//var_dump($post);
			unset($post['againpassword']);
			unset($post['Verification']);
			return $this->db->insert("teacher",$post);
		}
	}