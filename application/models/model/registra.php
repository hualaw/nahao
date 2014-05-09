<?php
	class Registra extends CI_MODEL
	{

		 /**
	     * 验证学生注册时提交的数据合法性
	     * @param int $post
	     * @return boolean true flase
	     * @author shangshikai@nahao.com
	     */
		public function veri_stu_post()
		{
			$this->form_validation->set_rules('nickname', 'Nickname', 'trim|required|min_length[5]|max_length[12]|xss_clean|callback_veri_nick_reg');
			$this->form_validation->set_rules('realname', 'Realname', 'trim|required|min_length[5]|max_length[12]|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|matches[passconf]|md5');
			$this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required|md5');
			$this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
			$this->form_validation->set_rules('age', 'Age', 'trim|required|callback_veri_num_reg');
			$this->form_validation->set_rules('phone', 'Phone', 'trim|required|callback_veri_num_reg|callback_veri_stu_reg');
		}
		/**
	     * 验证注册时提交的年龄和电话是否为电话号码
	     * @param int $mobile
	     * @return boolean true flase
	     * @author shangshikai@nahao.com
	     */
		public function veri_num_reg($phone)
		{
			return ( ! preg_match('/^(13[0-9]{1}[0-9]{8}|15[0-9]{1}[0-9]{8}|18[0-9]{1}[0-9]{8}|14[0-9]{9}|17[078][0-9]{8})$/',$phone)) ? FALSE : TRUE;
		}
		/**
	     * 验证学生注册时提交的昵称是否已存在
	     * @param int $nickname
	     * @return boolean true flase
	     * @author shangshikai@nahao.com
	     */
		public function veri_nick_reg($nickname)
		{
			$this->load->database();
			$que=$this->db->get_where("student",array("nickname"=>$nickname))->row_array();
			if($que)
				return FALSE;
			else
				return TRUE;
		}
		 /**
	     * 验证学生注册时提交的手机号是否已注册
	     * @param int $mobile
	     * @return boolean true flase
	     * @author shangshikai@nahao.com
	     */
		public function veri_stu_reg($mobile)
		{
			$config['hostname'] = "192.168.11.75";
			$config['username'] = "root";
			$config['password'] = "";
			$config['database'] = "phone";
			$config['dbdriver'] = "mysql";
			$config['dbprefix'] = "";
			$config['pconnect'] = FALSE;
			$config['db_debug'] = TRUE;
			$config['cache_on'] = FALSE;
			$config['cachedir'] = "";
			$config['char_set'] = "utf8";
			$config['dbcollat'] = "utf8_general_ci";

			$this->load->database($config);
			$que=$this->db->get_where("phone",array("phonenum"=>$mobile))->row_array();
			if($que)
				return TRUE;
			else
				return FALSE;
		}
		/**
	     * 验证老师注册时提交的数据是否合法
	     * @param varchar $email
	     * @return boolean true flase
	     * @author shangshikai@nahao.com
	     */
		 public function veri_tea_post()
		 {
			$this->form_validation->set_rules('nickname', 'Nickname', 'trim|required|min_length[5]|max_length[12]|xss_clean|callback_veri_teanick_reg');
			$this->form_validation->set_rules('realname', 'Realname', 'trim|required|min_length[5]|max_length[12]|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|matches[passconf]|md5');
			$this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required|md5');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_veri_tea_reg');
			$this->form_validation->set_rules('age', 'Age', 'trim|required|callback_veri_num_reg');
			$this->form_validation->set_rules('phone', 'Phone', 'trim|callback_veri_num_reg');
		 }
 		/**
	     * 验证老师注册时昵称是否存在
	     * @param int $nickname
	     * @return boolean true flase
	     * @author shangshikai@nahao.com
	     */
		 public function veri_teanick_reg($nickname)
		 {
			$this->load->database();
			$que=$this->db->get_where("teacher",array("nickname"=>$nickname))->row_array();
			if($que)
				return FALSE;
			else
				return TRUE;
		 }
		 /**
	     * 验证老师注册时提交的邮箱是否已注册
	     * @param int $email
	     * @return boolean true flase
	     * @author shangshikai@nahao.com
	     */
		public function veri_tea_reg($email)
		{
			$this->load->database();
			$que=$this->db->get_where("teacher",array("email"=>$email))->row_array();
			if($que)
				return TRUE;
			else
				return FALSE;
		}
		/**
	     * 学生注册时提交的数据通过
	     * @param array $_POST
	     * @return boolean true flase
	     * @author shangshikai@nahao.com
	     */
		public function stu_success()
		{
			
		}
	}