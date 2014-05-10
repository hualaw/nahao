<?php
	header("content-type:text/html;charset=utf-8");
	class Student_check extends NH_Model
	{
		/**
	     * 收集验证学生数据
	     * @param string $post['nickname'],$post['password'],$post['againpassword'],$post['phone'],$post['Verification']
	     * @return boolean true flase
	     * @author shangshikai@nahao.com
	     */
		public function check($post)
		{
			//var_dump($post);die;
			if(strtolower($post['Verification'])!=strtolower($this->session->userdata('code')))
			{
				return FALSE;
			}				
			$this->load->model("model/student/check_student");
			$nickname=$this->check_student->check_nickname($post['nickname']);
			$password=$this->check_student->check_password($post['password'],$post['againpassword']);
			// if(isset($post['phone']))
			// {
			// 	$phone=$this->check_student->check_phone($post['phone']);
			// }
			if(isset($post['email']))
			{
				$email=$this->check_student->check_email($post['email']);
		    }
			if(isset($phone))
			{
				if($nickname==TRUE && $phone==TRUE && $password==TRUE)
				 {
				 	if($this->db->insert("student",$post))
				 		return TRUE;
				 	else
				 		return FALSE;
				 }
				 else
				 	return FALSE;
			}
			else
			{
				if($nickname==TRUE && $email==TRUE && $password==TRUE)
				 {
				 	if($this->db->insert("student",$post))
				 		return TRUE;
				 	else
				 		return FALSE;
				 }
				 else
				 	return FALSE;
			}
		}
	}