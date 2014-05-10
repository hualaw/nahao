<?php
	class Student_register extends NH_Student_Controller
	{
		/**
		 * 跳转到学生手机注册页面
		 * @param 
		 * @return 
		 * @author shangshikai@nahao.com
		 */
		public function index()
		{
			$this->load->helper('captcha');
            $vals = array(
            'img_path' => './captcha/',
            'img_url' => 'http://www.nahaodev.com/captcha/',
            'img_width' => '100',
            'img_height' => 30,
            'expiration' => 7200
            );
            $cap = create_captcha($vals);
            $data2["captcha"]=$cap['image'];
            $data=array('code'=>$cap['word']);
            $this->session->set_userdata($data);
			$this->load->helper(array('form', 'url'));
  			$this->load->library('form_validation');
			$this->load->view("www/student_register",$data2);
		}
		/**
		 * 跳转到学生邮箱注册页面
		 * @param 
		 * @return 
		 * @author shangshikai@nahao.com
		 */
		public function email_register()
		{
			$this->load->helper('captcha');
            $vals = array(
            'img_path' => './captcha/',
            'img_url' => 'http://www.nahaodev.com/captcha/',
            'img_width' => '100',
            'img_height' => 30,
            'expiration' => 7200
            );
            $cap = create_captcha($vals);
            $data2["captcha"]=$cap['image'];
            $data=array('code'=>$cap['word']);
            $this->session->set_userdata($data);
			$this->load->helper(array('form', 'url'));
  			$this->load->library('form_validation');
			$this->load->view("www/email_register",$data2);
		}
		/**
	     * 收集验证学生数据
	     * @param array $this->input->post(NULL,TRUE)
	     * @return boolean true flase
	     * @author shangshikai@nahao.com
	     */
		public function check_student()
		{
			$post=$this->input->post(NULL,TRUE);
			$this->load->model("business/student/student_check");
			$check_stu=$this->student_check->check($post);
			if($check_stu==TRUE)
			{
				$this->load->model("model/student/check_student");
				$data=$this->check_student->success_register($post);
				if($data==TRUE)
				{
					echo "注册成功";
					var_dump($post);die;
					if(isset($post['email']))
					{
						$dat=$this->db->get_where("student",array("email")=$post['email'])->row_array();
						echo $dat['id'];//学生id,扩展用
					}
					else
					{
						$this->db->get_where("student",array("phone")=$post['phone']);
						echo $dat['id'];//学生id,扩展用
					}
			    }
			    else
			    	echo "注册失败";
			}
			else
				echo "验证数据失败";

		}
		/**
	     * 完善学生信息
	     * @param array $this->input->post(NULL,TRUE)
	     * @return boolean true flase
	     * @author shangshikai@nahao.com
	     */
		public function student_info()
		{
			$data['stu_id']=$this->input->get('stuid');
			$this->load->helper(array('form', 'url'));
  			$this->load->library('form_validation');
			$bool=$this->load->view("www/student_info",$data);
			if($bool==TRUE)
				echo "扩展信息成功";
			else
				echo "扩展信息失败";
		}
	}