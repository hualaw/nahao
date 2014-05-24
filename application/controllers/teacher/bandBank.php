<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BandBank extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->model('business/teacher/business_teacher','teacher_b');
		$this->load->model('model/teacher/model_teacher','teacher_m');
    }
    
	/**
	 * 老师课程列表
	 */
	public function index()
	{
		#2.页面数据
		$data = array(
			'title' => '绑定银行账户',
			'active' => 'bandBank_index',
		);
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/bandBank.html');
	}
	
}