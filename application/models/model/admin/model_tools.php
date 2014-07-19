<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 系统工具model
 */
class Model_Tools extends NH_Model
{
	/**
	 * 学生与订单清除者
	 * @应用场景：临时买课脚本
	 */
	public function student_order_clearer($param)
	{
		$param['student_id'] 	= !empty($param['student_id']) ? $param['student_id'] : '';
		$param['round_id'] 		= !empty($param['round_id']) ? $param['round_id'] : '';
		if(!$param['student_id'] || !$param['round_id']){
			exit('学生id与轮id都不能为空');
		}
		$where = " WHERE 1";
		$where .= $param['student_id'] ? ' AND student_id in('.$param['student_id'].')' : '';
		$where .= $param['round_id'] ? ' AND round_id in('.$param['round_id'].')' : '';
		$where .= $param['status'] ? ' AND status in('.$param['status'].')' : '';
		$sql = "DELETE FROM student_order WHERE ".$where;
		$affect = $this->db->affected_rows($sql);
        return $affect > 0 ? true :false;
	}
	
	/**
	 * 学生与课清除者
	 * @应用场景：临时买课脚本
	 */
	public function student_class_clearer($param)
	{
		$param['student_id'] 	= !empty($param['student_id']) ? $param['student_id'] : '';
		$param['round_id'] 		= !empty($param['round_id']) ? $param['round_id'] : '';
		if(!$param['student_id'] || !$param['round_id']){
			exit('学生id与轮id都不能为空');
		}
		$where = " WHERE 1";
		$where .= $param['student_id'] ? ' AND student_id in('.$param['student_id'].')' : '';
		$where .= $param['round_id'] ? ' AND round_id in('.$param['round_id'].')' : '';
		
	}
	
	/**
	 * 订单日志制造者
	 * @应用场景：临时买课脚本
	 */
	public function order_log_maker($param)
	{
		
	}
	
	/**
	 * 学生与订单制造者
	 * @应用场景：临时买课脚本
	 */
	public function student_order_maker($param)
	{
		
	}
	
	/**
	 * 学生与课制造者
	 * @应用场景：临时买课脚本
	 */
	public function student_class_maker($param)
	{
		
	}
	
	/**
	 * 轮信息提取者
	 * 如：购买人数当前值，最大值查询
	 * @应用场景：临时买课脚本
	 */
	public function round_info_getter($param)
	{
		$param['student_id'] 	= !empty($param['student_id']) ? $param['student_id'] : '';
		$param['round_id'] 		= !empty($param['round_id']) ? $param['round_id'] : '';
		if(!$param['student_id'] || !$param['round_id']){
			exit('学生id与轮id都不能为空');
		}
		$sql = "SELECT id, ";
	}
	
	/**
	 * 轮信息升级者：
	 * 如：购买人数自增减1
	 * @应用场景：临时买课脚本
	 */
	public function round_upgrader($param)
	{
		
	}
	
	/**
	 * 用户信息升级者
	 * 如：付费类型修改为付费用户/非付费用户
	 * @应用场景：临时买课脚本
	 */
	public function userinfo_upgrader($param)
	{
		
	}
}