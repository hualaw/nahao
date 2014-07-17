<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_statistics extends NH_Model{
	
    public function get_classroom_history($param = array(),$offset = 0,$per_page = PER_PAGE_NO)
    {
    	$time = time();
    	$result = $list = array();
    	$where = 'WHERE 1=1 AND ec.user_type='.NH_MEETING_TYPE_STUDENT;
    	$end_time = $param['time']+60*60*24;
    	$where .= !empty($param['time'])?' AND ec.create_time>'.$param['time'].' AND ec.create_time<'.$end_time:'';
    	$where .= !empty($param['user_id'])?' AND ec.user_id='.$param['user_id']:'';
    	$group_by = 'ec.user_id,ec.classroom_id';
    	$order_by = 'ec.create_time desc';
    	$sql_count = "SELECT count(DISTINCT ec.user_id,ec.classroom_id) as total
    			FROM entering_classroom ec
    			".$where;
    	$total = $this->db->query($sql_count)->result_array();
    	$result['total'] = $total[0]['total'];
    	
    	$sql = "SELECT DISTINCT ec.user_id,ec.classroom_id
    			FROM entering_classroom ec
    			".$where.' order by '.$order_by.' limit '.$offset.','.$per_page;
		
    	$result_tmp = $this->db->query($sql)->result_array();
    	    	
		$this->load->model('business/admin/business_class','business');
    	if (!empty($result_tmp)){
    		foreach ($result_tmp as $value){
     			$user_info = $this->get_userinfo_by_user_id($value['user_id']);
    			
    			$value['realname'] = !empty($user_info[0])?$user_info[0]['realname']:'';
    			$value['nickname'] = !empty($user_info[0])?$user_info[0]['nickname']:'';
    			$value['phone'] = get_pnum_phone_server($value['user_id']);
    			$enter_time = $this->_get_enter_class_time($value['user_id'],$value['classroom_id']);
    			$value['enter_time'] = $enter_time['str'];
    			$value['enter_num'] = $enter_time['num'];
    			$class = $this->business->get_class_by_classroom_id($value['classroom_id']);
    			$value['class_title'] = !empty($class)?$class['title']:'';
    			$list[] = $value;
    		}
    	}
    	$result['list'] = $list;
//     	print_r($result);
//     	exit();
    	return $result;
    }
	
	private function _get_enter_class_time($user_id,$classroom_id)
	{
		$sql = "SELECT create_time FROM entering_classroom WHERE user_id=".$user_id." AND classroom_id=".$classroom_id;
		$result_tmp = $this->db->query($sql)->result_array();
		$result = array();
		$result_str = '';
		$i = 1;
		if (!empty($result_tmp)){
			foreach ($result_tmp as $value){
				if($i>1){
					$result_str .= '----->'.date('Y-m-d H:i:s',$value['create_time']);
				}else{
					$result_str .=	date('Y-m-d H:i:s',$value['create_time']);
				}
				$i++;
			}
		}
		$result['str'] = $result_str;
		$result['num'] = count($result_tmp);
// 		print_r($result_str);
// 		exit();
		return $result;
		
	}
	
	public function get_userinfo_by_user_id($user_id = 0)
	{
		$where = '';
		$where .= !empty($user_id)?' u.id='.$user_id:'';
		$sql = "SELECT u.nickname,i.realname 
    			FROM user u
				LEFT JOIN user_info i ON u.id=i.user_id
    			WHERE".$where;
		$result = $this->db->query($sql)->result_array();
		return $result;
	}
	
	public function get_user_id_by_where($nickname = '',$phone = '')
	{
		if ($phone){
			$user_id = !empty(get_uid_phone_server($param))?get_uid_phone_server($param):'';
		}
		$where = 'WHERE 1=1';
		$where .= !empty($nickname)?' AND u.nickname like "%'.$nickname.'%"':'';
		$where .= !empty($user_id)?' AND u.id= '.$user_id:'';
		$sql = "SELECT u.id
    			FROM user u
    			".$where.' ';
// 		print_r($sql);
		$res = $this->db->query($sql)->result_array();
		
		return !empty($res[0])?$res[0]['id']:'';
	}
}