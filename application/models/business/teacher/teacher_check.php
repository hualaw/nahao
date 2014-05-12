<?php
	header("content-type:text/html;charset=utf-8");
	class Teacher_check extends NH_Model
	{
		/**
	     * 收集验证老师数据
	     * @param string $post['nickname'],$post['password'],$post['againpassword'],$post['Verification']
	     * @return boolean true flase
	     * @author shangshikai@nahao.com
	     */
		public function check($post)
		{
			if(strtolower($post['Verification'])!=strtolower($this->session->userdata('code')))
			{
				return FALSE;
			}	
			$this->load->model("model/teacher/check_teacher");
			$nickname=$nickname=$this->check_teacher->check_nickname($post['nickname']);
			$password=$this->check_teacher->check_password($post['password'],$post['againpassword']);
			$email=$this->check_teacher->check_email($post['email']);
			//var_dump($post);die;
			if($nickname==TRUE && $email==TRUE && $password==TRUE)
		 	{
			 	if($this->db->insert("teacher",$post))
			 		return TRUE;
			 	else
			 		return FALSE;
		 	}
		 	else
		 		return FALSE;
		}
	}