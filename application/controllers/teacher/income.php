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
		#1.模块化和页面相关
		$nav = $this->load->view('teacher/nav',array(),true);
		$siteBar = $this->load->view('teacher/siteBar',array('active' => 'income_index'),true);
		$list = $this->load->view('teacher/income/list',array(),true);
		$pos = $this->teacher_b->get_pos('课酬结算列表');
		
		#2.页面数据
		$data = array(
			'nav' => $nav,
			'siteBar' => $siteBar,
			'list' => $list,
			'pos' => $pos,
		);
		$this->load->view('teacher/income.php',$data);
	}
	
	/**
	 * 课酬详情
	 */
	public function detail()
	{
		#1.课酬id
		$id = $_GET['id'];
		#2.模块化和页面相关
		$nav = $this->load->view('teacher/nav',array(),true);
		$siteBar = $this->load->view('teacher/siteBar',array('active' => 'income_detail'),true);
		$detail = $this->load->view('teacher/income/detail',array(),true);
		$pos = $this->teacher_b->get_pos('课酬结算详情');
		
		#1.页面数据
		$data = array(
			'nav' => $nav,
			'siteBar' => $siteBar,
			'detail' => $detail,
			'pos' => $pos,
		);
		$this->load->view('teacher/income_detail.php',$data);
	}
}