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
		#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1';
		$where .= $param['lesson_id'] ? ' AND qlr.lesson_id='.$param['lesson_id'] : '';
		$column = 'q.*';
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
     * 课节题目管理器
     * param : id,question,answer,options,analysis,class_id,question_id,status,sequence
     * do,delete_class_question,delete_lesson_question
     **/
     public function question_manager($param){
     	if(!$param['do']){exit("缺少操作参数");}
     	$param['question'] = addslashes($param['question']);
     	$param['answer'] = addslashes($param['answer']);
     	$param['options'] = addslashes($param['options']);
     	$param['analysis'] = addslashes($param['analysis']);
     	$this->db->query("set names utf8");
     	switch ($param['do']){
     		case 'add':
     			$sql = "INSERT INTO nahao.question(question,answer,options,question.type,analysis) 
						VALUES('".$param['question']."','".$param['answer']."','".$param['options']."',".$param['type'].",'".$param['analysis']."')";
     			$id = $this->db->query($sql)->insert_id();
     			if($id){
     				$sql = "REPLACE INTO nahao.question_lesson_relation(id,question_id,lesson_id) 
							VALUES(".$param['id'].",".$param['question_id'].",".$param['lesson_id'].")";
     				$res = $this->db->query($sql);
     			}
     			break;
     		case 'edit':
     			$sql = "UPDATE nahao.question 
						SET question='".$param['question']."',
							answer='".$param['answer']."',
							options='".$param['options']."',
							question.type=1,
							analysis='".$param['analysis']."' 
						WHERE 1 AND id=".$param['id'];
     			$res = $this->db->query($sql);
     			break;
     		case 'delete':
 				//删除题课节关系
 				if($param['delete_lesson_question']){
 					$res = $this->db->query("DELETE FROM nahao.question_lesson_relation WHERE question_id=".$param['id']);
 				}
     	}
     	return $this->db->query($sql);
     }
}