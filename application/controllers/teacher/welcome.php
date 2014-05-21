<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('business/teacher/business_teacher','teacher');
    }
    
	/**
	 * 老师端口首页
	 * 当天即将开课提醒
	 */
	public function index()
	{
		$nav = $this->load->view('teacher/nav',array(),true);
		$arr = $this->teacher->get_today_round(array('user_id'=>1));
		
		$this->load->view('teacher/nav',$nav);
		$this->view('teacher/index');
	}
}