<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 分类搜索
 * jason song
 */
class Model_CateList extends NH_Model
{

    /**
     * 获取分类课程列表
     */
    protected function _get_subject($param)
    {
		#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1';
		$where .= !empty($param['stageId']) ? ' AND r.round_id='.$param['stageId'] : '';
		$where .= !empty($param['gradeId']) ? ' AND r.round_id='.$param['gradeId'] : '';
		$where .= !empty($param['subjectId']) ? ' AND r.round_id='.$param['subjectId'] : '';
		$where .= !empty($param['qualityId']) ? ' AND r.round_id='.$param['qualityId'] : '';
		$where .= !empty($param['typeId']) ? ' AND r.round_id='.$param['typeId'] : '';
		$where .= !empty($param['versionId']) ? ' AND r.round_id='.$param['versionId'] : '';
		#2. 生成sql
        $this->db->query("set names utf8");
		$sql = "SELECT ".$column." 
				FROM round r 
				".$where.$order.$limit;
		$arr_result = $this->db->query($sql)->result_array();
    	return $res;
    }
}