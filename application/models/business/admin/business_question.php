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
    public function lesson_question($param,$str_type = 'common'){
    	$list = $this->model_question->lesson_question_seacher($param,$str_type);
    	if(count($list)>0){
            if($str_type=='common'){
                foreach ($list as &$val){
                    $options = json_decode($val['options'],true);
//    		$options = unserialize(mb_convert_encoding(serialize($options),'utf-8','gbk'));
                    $val['options'] = $options;
                    if($val['options']) foreach ($val['options'] as &$v){
		    			$v = urldecode($v);
		    		}
                }
            }
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
//    	$param['options']['A'] = !empty($param['A']) ? preg_replace('/data-mathml=\"[^\"]+\"/','',addslashes($param['A'])) : '';
//		$param['options']['B'] = !empty($param['B']) ? preg_replace('/data-mathml=\"[^\"]+\"/','',addslashes($param['B'])) : '';
//		$param['options']['C'] = !empty($param['C']) ? preg_replace('/data-mathml=\"[^\"]+\"/','',addslashes($param['C'])) : '';
//		$param['options']['D'] = !empty($param['D']) ? preg_replace('/data-mathml=\"[^\"]+\"/','',addslashes($param['D'])) : '';
//		$param['options']['E'] = !empty($param['E']) ? preg_replace('/data-mathml=\"[^\"]+\"/','',addslashes($param['E'])) : '';
		$param['options']['A'] = !empty($param['A']) ? urlencode($param['A']) : '';
		$param['options']['B'] = !empty($param['B']) ? urlencode($param['B']) : '';
		$param['options']['C'] = !empty($param['C']) ? urlencode($param['C']) : '';
		$param['options']['D'] = !empty($param['D']) ? urlencode($param['D']) : '';
		$param['options']['E'] = !empty($param['E']) ? urlencode($param['E']) : '';
		
		$input = array(
    		'question' => $param['question'],
    		'analysis' => $param['analysis'],
    		'answer' => $param['answer'],
    		'options' => json_encode($param['options']),//json_encode会自动转义
    		'type' => $param['type'],
    		'do' => $param['do'],
    		'add_lesson_question' => 1,
    		'lesson_id' => $param['lesson_id'],
    	);
    	#修改
    	if(!empty($param['question_id'])){
    		$input['question_id'] = $param['question_id'];
    	}
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
    	$list = $this->model_question->class_question_seacher($param);
    	if(count($list)>0) foreach ($list as &$val){
    		$options = json_decode($val['options'],true);
//    		$options = unserialize(mb_convert_encoding(serialize($options),'utf-8','gbk'));
    		$val['options'] = $options;
    		if($val['options']) foreach ($val['options'] as &$v){
    			$v = urldecode($v);
    		}
    	}
    	return $list;
    }
    
    /**
     * 课添题
     * param = [do,question_id,lesson_id]
     */
    public function class_question_doWrite($param){
        $param['type'] = isset($param['answer']) ? (count($param['answer']>1) ? 2 : 1) : '';
        $param['answer'] = isset($param['answer']) ? (join($param['answer'],',')) : '';
    	$param['question'] = !empty($param['question']) ? addslashes($param['question']) : '';
		$param['analysis'] = !empty($param['analysis']) ? addslashes($param['analysis']) : '';
//    	$param['options']['A'] = !empty($param['A']) ? preg_replace('/data-mathml=\"[^\"]+\"/','',addslashes($param['A'])) : '';
//		$param['options']['B'] = !empty($param['B']) ? preg_replace('/data-mathml=\"[^\"]+\"/','',addslashes($param['B'])) : '';
//		$param['options']['C'] = !empty($param['C']) ? preg_replace('/data-mathml=\"[^\"]+\"/','',addslashes($param['C'])) : '';
//		$param['options']['D'] = !empty($param['D']) ? preg_replace('/data-mathml=\"[^\"]+\"/','',addslashes($param['D'])) : '';
//		$param['options']['E'] = !empty($param['E']) ? preg_replace('/data-mathml=\"[^\"]+\"/','',addslashes($param['E'])) : '';
		$param['options']['A'] = !empty($param['A']) ? urlencode($param['A']) : '';
		$param['options']['B'] = !empty($param['B']) ? urlencode($param['B']) : '';
		$param['options']['C'] = !empty($param['C']) ? urlencode($param['C']) : '';
		$param['options']['D'] = !empty($param['D']) ? urlencode($param['D']) : '';
		$param['options']['E'] = !empty($param['E']) ? urlencode($param['E']) : '';
		$input = array(
    		'question' => $param['question'],
    		'analysis' => $param['analysis'],
    		'answer' => $param['answer'],
    		'options' => json_encode($param['options']),
    		'type' => $param['type'],
    		'do' => $param['do'],
    		'add_class_question' => 1,
    		'class_id' => $param['class_id'],
    	);
    	#修改
    	if(!empty($param['question_id'])){
    		$input['question_id'] = $param['question_id'];
    	}var_dump($input);
		return $this->model_question->question_manager($input);
    }
    
    /**
     * 课删题
     */
    public function class_question_delete($param){
    	return $this->model_question->question_manager($param);
    }
}
