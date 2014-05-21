<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lectrue extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->model('teacher/tiku_model');
    }
    
	/**
	 * 老师端试讲申请
	 */
	public function index()
	{
		$this->load->view('mobile_tiku/mobile_notice_index.html');
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