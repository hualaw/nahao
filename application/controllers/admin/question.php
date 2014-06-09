<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 题目管理
 * Class Round
 */
class Question extends NH_Admin_Controller {

    private $arr_response = array(
        'status' => 'error',
        'msg' => '操作失败',
    );

    function __construct(){
        parent::__construct();
        $this->load->model('business/admin/business_lesson');
    }

    /**
     * 课节题目列表
     */
    public function lesson_question(){
    	#1. 课节信息
        $lesson_id = $this->uri->segment(3,0);
        $lesson_info = $this->business_lesson->get_lesson_by_id($lesson_id);
        
		$data = array(
			'lesson_info' => $lesson_info,
		);
    	$this->smarty->assign('view', 'question_lesson_list');
    	$this->smarty->assign('data', $data);
        $this->smarty->display('admin/layout.html');
    }
	
    /**
     * 课节添题
     */
    public function lesson_add(){
    	#1. 课节信息
    	$lesson_id = $this->uri->segment(3,0);
    	$param = array(
    		'lesson_id' => $lesson_id,
    	);
    	$lesson_info = $this->business_question->lesson_question($param);
    	#2. 题目列表
    	$data = array(
    		'lesson_info' => '',
    	);
    	$this->smarty->assign('view', 'question_lesson_add');
    	$this->smarty->assign('data', $data);
        $this->smarty->display('admin/layout.html');
    }
    
    /**
     * 课节删题
     */
    public function lesson_question_delete(){
    	#1. 课节信息
    	$lesson_id = $this->uri->segment(3,0);
    	
    }
    
    /**
     * 课题列表
     */
    public function class_question(){
    	#1. 课信息
    	$class_id = $this->uri->segment(3,0);
    	
    }
    
    /**
     * 课添题
     */
    public function class_question_add(){
    	#1. 课信息
    	$class_id = $this->uri->segment(3,0);
    	
    }
    
    /**
     * 课删题
     */
    public function class_question_delete(){
    	#1. 课信息
    	$class_id = $this->uri->segment(3,0);
    	
    }
}    