<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * jason
 */
class Model_Info extends NH_Model{
	
	public function get_round_info_by_classroom_id($param)
	{
		$param['classroom_id'] = !empty($param['classroom_id']) ? $param['classroom_id'] : '';
		if(!$param['classroom_id']){
			exit('教室id不能为空');
		}
		$sql = "SELECT r.id,r.title,r.is_test,r.subject 
				FROM class c 
				LEFT JOIN round r ON r.id=c.round_id 
				WHERE c.classroom_id=".$param['classroom_id'];
		$arr_result = $this->db->query($sql)->row_array();
        return $arr_result;
	}
	
}