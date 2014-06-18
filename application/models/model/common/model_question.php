<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 题目管理model
 */
class Model_Question extends NH_Model
{
	/**
	 * 【题目搜索器 - 课节】：
	 * pararm : lesson_id
	 */
	public function lesson_question_seacher($param){
		$param['question_id'] = !empty($param['question_id']) ? $param['question_id'] : '';
		$param['lesson_id'] = !empty($param['lesson_id']) ? $param['lesson_id'] : '';
		#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1';
		$where .= $param['question_id'] ? ' AND qlr.question_id='.$param['question_id'] : '';
		$where .= $param['lesson_id'] ? ' AND qlr.lesson_id='.$param['lesson_id'] : '';
		$column = 'q.*,qlr.lesson_id ';
		#2. 生成sql
        $this->db->query("set names utf8");
        
		$sql = "SELECT ".$column." 
				FROM nahao.question q 
				LEFT JOIN question_lesson_relation qlr ON q.id=qlr.question_id
				".$where;
		$arr_result = $this->db->query($sql)->result_array();
		
        return $arr_result;
	}
	
	/**
	 * 【题目搜索器 - 课节】：
	 * pararm : lesson_id
	 */
	public function class_question_seacher($param){
		$param['question_id'] = !empty($param['question_id']) ? $param['question_id'] : '';
		$param['class_id'] = !empty($param['class_id']) ? $param['class_id'] : '';
		#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1';
		$where .= $param['question_id'] ? ' AND qcr.question_id='.$param['question_id'] : '';
		$where .= $param['class_id'] ? ' AND qcr.class_id='.$param['class_id'] : '';
		$column = 'q.*,qcr.class_id ';
		#2. 生成sql
        $this->db->query("set names utf8");
        
		$sql = "SELECT ".$column." 
				FROM nahao.question q 
				LEFT JOIN question_class_relation qcr ON q.id=qcr.question_id
				".$where;
		$arr_result = $this->db->query($sql)->result_array();
		
        return $arr_result;
	}
	
	/**
     * 题目管理器
     * param : id,question,answer,options,analysis,class_id,question_id,status,sequence
     * do,delete_class_question,delete_lesson_question
     **/
     public function question_manager($param){
     	$param['question'] = !empty($param['question']) ? $param['question'] : '';
     	$param['answer'] = !empty($param['answer']) ? $param['answer'] : '';
     	$param['options'] = !empty($param['options']) ? $param['options'] : '';
     	$param['analysis'] = !empty($param['analysis']) ? $param['analysis'] : '';
     	$param['question_id'] = !empty($param['question_id']) ? $param['question_id'] : '';
     	$param['lesson_id'] = !empty($param['lesson_id']) ? $param['lesson_id'] : '';
     	$param['class_id'] = !empty($param['class_id']) ? $param['class_id'] : '';
     	if(empty($param['do'])){exit("缺少操作参数");}
     	$param['answer'] = addslashes($param['answer']);
     	$param['options'] = addslashes($param['options']);
     	$this->db->query("set names utf8");
     	switch ($param['do']){
     		case 'add':
     			$sql = "INSERT INTO nahao.question(question,answer,options,question.type,analysis) 
						VALUES('".$param['question']."','".$param['answer']."','".$param['options']."',".$param['type'].",'".$param['analysis']."')";
     			$res = $this->db->query($sql);
     			$id = $this->db->insert_id();
     			if($id){
     				//课节的题
     				if(!empty($param['add_lesson_question'])){
     					$sql = "INSERT INTO nahao.question_lesson_relation(question_id,lesson_id) 
							VALUES(".$id.",".$param['lesson_id'].")";
     					$res = $this->db->query($sql);
     				}
     				//课的题
     				if(!empty($param['add_class_question'])){
     					$sql = "REPLACE INTO nahao.question_class_relation(class_id,question_id,question_class_relation.status,sequence) 
							VALUES(".$param['class_id'].",".$id.",0,0)";
     					$res = $this->db->query($sql);
     				}
     			}
     			break;
     		case 'edit':
     			$sql = "UPDATE nahao.question 
						SET question='".$param['question']."',
							answer='".$param['answer']."',
							options='".$param['options']."',
							question.type=1,
							analysis='".$param['analysis']."' 
						WHERE 1 AND id=".$param['question_id'];
     			$res = $this->db->query($sql);
     			break;
     		case 'delete':
 				//删除题课节关系
 				if(!empty($param['delete_lesson_question'])){
 					$sql = "DELETE FROM nahao.question_lesson_relation WHERE question_id=".$param['question_id'];
 					$res = $this->db->query($sql);
 				}
 				//删除题课关系
 				if(!empty($param['delete_class_question'])){
 					$sql = "DELETE FROM nahao.question_class_relation WHERE question_id=".$param['question_id'];
 					$res = $this->db->query($sql);
 				}
 				break;
     	}
     	return $res;
     }
}
