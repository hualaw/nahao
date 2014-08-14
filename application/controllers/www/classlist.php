<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header('content-type: text/html; charset=utf-8');
class Classlist extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/student/student_course');
        $this->load->model('business/student/student_order');
        
    }
    
    /**
     * 课程列表
     * typeId 		=> 		t
	 * qualityId	=>		q
	 * stageId		=> 		st
	 * gradeId		=> 		g
	 * subjectId	=> 		su
	 * order		=>		o
	 * page			=>		p
     */
    function index($type){
    	var_dump($type);
    	$param['typeId'] = $this->input->get('typeId');
    	$this->smarty->display('www/studentClassList/index.html');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */