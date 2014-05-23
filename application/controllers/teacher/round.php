<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Round extends CI_Controller {

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
		#1.列表
		$listArr = $this->teacher_b->get_round(array('user_id'=>1));
		#2.模块化和页面相关
		$nav = $this->load->view('teacher/nav',array(),true);
		$siteBar = $this->load->view('teacher/siteBar',array('active' => 'round_index'),true);
		$listStr = $this->load->view('teacher/round/list',array('list'=>$listArr),true);
		$pos = $this->teacher_b->get_pos('课程列表');
		
		#3.页面数据
		$data = array(
			'nav' => $nav,
			'siteBar' => $siteBar,
			'list' => $listStr,
			'pos' => $pos,
		);
		$this->load->view('teacher/round.php',$data);
	}

	/**
	 * 查看章节
	 */
	public function class_list(){
		#1.模块化和页面相关
		$nav = $this->load->view('teacher/nav',array(),true);
		$siteBar = $this->load->view('teacher/siteBar',array(),true);
		$zj_list = $this->load->view('teacher/round/zj_list',array(),true);
		$pos = $this->teacher_b->get_pos('章节列表');
		
		#2.页面数据
		$data = array(
			'nav' => $nav,
			'siteBar' => $siteBar,
			'pos' => $pos,
			'class_list' => $zj_list,
			'active' => 'round_class_list',
		);
		$this->load->view('teacher/class_list.php',$data);
	}
	
	/**
	 * 添加试题
	 */
	public function question_add(){
		
	}
	
	/**
	 * 修改试题
	 */
	public function question_edit(){
		
	}
	
	/**
	 * 具体课的答题统计
	 */
	public function answer_count(){
		
	}
	
	/**
	 * 查看评价
	 */
	public function check_comment(){
		
	}
}