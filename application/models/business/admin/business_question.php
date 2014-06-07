<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 管理后台的题目管理数据
 */
class Business_Question extends NH_Model
{
    function __construct(){
        parent::__construct();
        $this->load->model('model/common/model_question');
        $this->load->model('model/teacher/model_teacher');
    }
    
    /**
     * 课节题目列表
     */
    public function lesson_question($param){
    	$detail = $this->model_question->lesson_question_seacher($param);
    	return $datail;
    }
    
    /**
     * 课节添题
     */
    public function lesson_question_add($param){
    	
    }
    
    /**
     * 课节删题
     */
    public function lesson_question_delete($param){
    	
    }
    
    /**
     * 课题目列表
     */
    public function class_question($param){
    	
    }
    
    /**
     * 课添题
     */
    public function class_question_add($param){
    	
    }
    
    /**
     * 课删题
     */
    public function class_question_delete($param){
    	
    }
}