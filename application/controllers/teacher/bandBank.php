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
		#1.模块化和页面相关
		$nav = $this->load->view('teacher/nav',array(),true);
		$siteBar = $this->load->view('teacher/siteBar',array('active' => 'bandBank_index'),true);
		$table = $this->load->view('teacher/bandBank/table',array(),true);
		$pos = $this->teacher_b->get_pos('绑定银行账户');
		
		#2.页面数据
		$data = array(
			'nav' => $nav,
			'siteBar' => $siteBar,
			'table' => $table,
			'pos' => $pos,
		);
		$this->load->view('teacher/bandBank.php',$data);
	}
	
}