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
		$nav = $this->load->view('teacher/nav',array(),true);
		$this->load->view('teacher/lectrue.php');
	}
	
	/**
	 * 申请试讲成功
	 */
	public function success()
	{
		
		echo '声请成功!';exit;
	}
	
}