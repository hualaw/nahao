<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Income extends CI_Controller {

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
			'title' => '课酬结算列表',
			'active' => 'income_index',
		);
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/income.html');
	}
	
	/**
	 * 课酬详情
	 */
	public function detail()
	{
		#1.课酬id
		$id = $_GET['id'];
		#2.页面数据
		$data = array(
			'title' => '课酬结算详情',
			'active' => 'income_detail',
		);
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/income_detail.html');
	}
}