<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Question相关逻辑
 * Class Business_Question
 * @author yanrui@tizi.com
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
    	$list = $this->model_question->lesson_question_seacher($param);
    	if(count($list)>0) foreach ($list as &$val){
    		$options = json_decode($val['options'],true);
    		$val['options'] = $options;
    	}
    	return $list;
    }
    
    /**
     * 课节添题
     * param = [do,question_id,lesson_id]
     */
    public function lesson_question_doWrite($param){
    	$param['type'] = count($param['answer']>1) ? 2 : 1;
    	$param['answer'] = join($param['answer'],',');
    	$param['question'] = !empty($param['question']) ? addslashes($param['question']) : '';
		$param['analysis'] = !empty($param['analysis']) ? addslashes($param['analysis']) : '';
    	$param['options']['A'] = !empty($param['A']) ? preg_replace('/data-mathml=\"[^\"]+\"/','',addslashes(mb_convert_encoding($param['A'],'utf-8','gbk'))) : '';
		$param['options']['B'] = !empty($param['B']) ? preg_replace('/data-mathml=\"[^\"]+\"/','',addslashes(mb_convert_encoding($param['B'],'utf-8','gbk'))) : '';
		$param['options']['C'] = !empty($param['C']) ? preg_replace('/data-mathml=\"[^\"]+\"/','',addslashes(mb_convert_encoding($param['C'],'utf-8','gbk'))) : '';
		$param['options']['D'] = !empty($param['D']) ? preg_replace('/data-mathml=\"[^\"]+\"/','',addslashes(mb_convert_encoding($param['D'],'utf-8','gbk'))) : '';
		$param['options']['E'] = !empty($param['E']) ? preg_replace('/data-mathml=\"[^\"]+\"/','',addslashes(mb_convert_encoding($param['E'],'utf-8','gbk'))) : '';
		
		$input = array(
    		'question' => $param['question'],
    		'analysis' => $param['analysis'],
    		'answer' => $param['answer'],
    		'options' => json_encode($param['options']),
    		'type' => $param['type'],
    		'do' => $param['do'],
    		'lesson_id' => $param['lesson_id'],
    	);
		return $this->model_question->question_manager($input);
    }
    
    /**
     * 课节删题
     * param = [do,question_id,lesson_id]
     */
    public function lesson_question_delete($param){
    	return $this->model_question->question_manager($param);
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
