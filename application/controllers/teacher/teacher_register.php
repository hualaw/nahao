<?php
	class Teacher_register extends NH_Teacher_Controller
	{
		/**
		 * 跳转到老师注册页面
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
			$this->load->view("teacher/teacher_register",$data2);
		}
		 /**
	     * 收集验证老师数据
	     * @param array $this->input->post(NULL,TRUE)
	     * @return boolean true flase
	     * @author shangshikai@nahao.com
	     */
		public function check_teacher()
		{
			$this->load->model("business/teacher/teacher_check");
			$check_tea=$this->teacher_check->check($this->input->post(NULL,TRUE));
			if($check_tea==TRUE)
			{
				$this->load->model("model/teacher/check_teacher");
				$data=$this->check_teacher->success_register($post);
				if($data==TRUE)
				{
					echo "注册成功";
					$dat=$this->db->get_where("teacher",array("email")=$post['email'])->row_array();
							echo $dat['id'];//老师id,扩展用
				}
				else
					echo "注册失败";
			}
			else
				echo "验证数据失败";
		}
	}