<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lecture extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->model('business/teacher/business_teacher','teacher_b');
		$this->load->model('model/teacher/model_teacher','teacher_m');
    }
    
	/**
	 * 老师端试讲申请
	 */
	public function index()
	{
		#1.模块化和页面相关
		$nav = $this->load->view('teacher/nav',array(),true);
		$siteBar = $this->load->view('teacher/siteBar',array(),true);
		$table = $this->load->view('teacher/lecture/table',array(),true);
		$pos = $this->teacher_b->get_pos('我要试讲');
		
		#2.页面数据
		$data = array(
			'nav' => $nav,
			'siteBar' => $siteBar,
			'pos' => $pos,
			'table' => $table,
		);
		$this->load->view('teacher/lectrue.php',$data);
	}
	
	/**
	 * 申请试讲成功
	 */
	public function doAdd()
	{
		$param = $_GET;
//		$res = $this->teacher_b->apply_teach($param);
//		if(!$res){
//			exit('<script>alert("申请失败，请重新操作！");history.go(-1);</script>'); 
//		}
		#1. 模块化和页面相关
		$nav = $this->load->view('teacher/nav',array(),true);
		$siteBar = $this->load->view('teacher/siteBar',array(),true);
		$success = $this->load->view('teacher/lecture/success',array(),true);
		$pos = $this->teacher_b->get_pos('申请试讲成功');
		
		#2.页面数据
		$data = array(
			'nav' => $nav,
			'siteBar' => $siteBar,
			'pos' => $pos,
			'success' => $success,
		);
		$this->load->view('teacher/lecture_success.php',$data);
	}
	
}