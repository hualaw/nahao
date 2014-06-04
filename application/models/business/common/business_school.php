<?php

class Business_School extends NH_Model{
	
	/**
	 * 通过父节点获取所有孩子节点数据[area表]
	 * @param integer $parentid
	 * @param string $fields
	 * @return array
	 * @author liuhua
	 */
	public function id_children($parentid, $fields = "*"){
		$id_replace = array(2,25,27,32,33);
		$rel = array(
			2 => 52,
			25 => 321,
			27 => 343,
			32 => 394,
			33 => 395
		);
		if (in_array($parentid, $id_replace)){
			$parentid = $rel[$parentid];
		}
		$result = $this->db->query("select {$fields} from ".TABLE_NAHAO_AREAS." where
				parentid=?", array($parentid))->result_array();
		return $result;
	}


    /**
     * 根据县/区的ID获取学校列表
     * @param integer $county_id
     * @param string $fields
     * @return array
     * @author liuhua
     */
    public function county_schools($county_id, $sctype, $fields = "*"){
        $r = $this->db->query("select {$fields} from ".TABLE_NAHAO_SCHOOLS." where
                                county_id=? and sctype=?", array($county_id, $sctype))->result_array();
        return $r;
    }
}