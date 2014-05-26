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
		#2.页面数据
		$data = array(
			'active' => 'lecture_index',
			'title' => '试讲申请',
			'host' => 'http://'.$_SERVER ['HTTP_HOST'],
		);
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/lecture.html');
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
		$data = array(
			'active' => 'lecture_lecture_success',
			'title' => '申请试讲成功',
			'host' => 'http://'.$_SERVER ['HTTP_HOST'],
		);
		
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/lecture_success.html');
	}
	
}