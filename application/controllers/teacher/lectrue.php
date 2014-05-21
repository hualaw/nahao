<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lectrue extends CI_Controller {

	/**
	 * 老师端试讲申请
	 */
	public function index()
	{
		$nav = $this->load->view('teacher/nav',array(),true);
		echo '试讲申请!';exit;
	}
	
	/**
	 * 申请试讲成功
	 */
	public function success()
	{
		$nav = $this->load->view('teacher/nav',array(),true);
		echo '声请成功!';exit;
	}
	
}