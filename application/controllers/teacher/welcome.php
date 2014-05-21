<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->model('teacher/tiku_model');
    }
    
	/**
	 * 老师端口首页
	 * 当天即将开课提醒
	 */
	public function index()
	{
		$nav = $this->load->view('teacher/nav',array(),true);
		
		echo '老师端!';exit;
	}
}