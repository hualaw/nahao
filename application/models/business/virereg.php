<?php
	class Tea_reg extends CI_MODEL
	{
		/**
	     * 验证学生注册时提交的数据合法性
	     * @param array $_POST
	     * @return boolean true flase
	     * @author shangshikai@nahao.com
	     */
		public function veri_stu_post()
		{
			$this->load->model("model/veri_stu_post");
		}
		/**
	     * 验证老师注册时提交的数据合法性
	     * @param array $_POST
	     * @return boolean true flase
	     * @author shangshikai@nahao.com
	     */
		public function veri_tea_post()
		{
			$this->load->model("model/veri_tea_post");
		}
		/**
	     * 学生注册时提交的数据通过
	     * @param array $_POST
	     * @return boolean true flase
	     * @author shangshikai@nahao.com
	     */
		public function stu_success()
		{
			$this->load->model("model/stu_success");
		}
	}