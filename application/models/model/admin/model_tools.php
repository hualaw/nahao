<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 系统工具model
 */
class Model_Tools extends NH_Model
{
	/**
	 * 查找学生与轮是否有已付款的订单
	 */
	public function search_student_order($param)
	{
		if(empty($param['round_id']) || empty($param['user_id'])){exit('轮id和学生id都不能为空');}
		$sql = 'SELECT COUNT(id) num FROM student_order 
			WHERE round_id='.$param['round_id'].' 
			AND student_id='.$param['user_id'].' 
			AND status='.ORDER_STATUS_SUCC;
		$this->db->query('set names utf8');
		$arr_result = $this->db->query($sql)->row_array();
        return $arr_result['num'];
	}
	
	/**
	 * 订单日志制造者
	 * @应用场景：临时买课脚本
	 */
	public function order_log_maker($param)
	{
		if(empty($param['user_id'])){exit('学生id不能为空');}
		if(empty($param['order_id'])){exit('订单id不能为空');}
		$param['now_price'] = !empty($param['now_price']) ? $param['now_price'] : 0;
		
		$sql = "INSERT INTO order_action_log(order_id,user_type,user_id,action,create_time,note) 
				VALUES(".$param['order_id'].",0,".$param['user_id'].",0,".time().",'创建订单成功')";
		
		$this->db->query($sql);
		$sql = "INSERT INTO order_action_log(order_id,user_type,user_id,action,create_time,note) 
				VALUES(".$param['order_id'].",0,".$param['user_id'].",2,".time().",'".$param['now_price']."元课程支付成功')";
		$this->db->query($sql);
		
		$int_result = $this->db->affected_rows();
        return $int_result;
	}
	
	/**
	 * 学生与订单制造者
	 * @应用场景：临时买课脚本
	 */
	public function student_order_maker($param)
	{
		if(empty($param['round_id']) || empty($param['user_id'])){exit('轮id和学生id都不能为空');}
		if(empty($param['sale_price']) || empty($param['now_price'])){exit('原价和现价都不能为空');}
		$sql = "INSERT INTO student_order(student_id,round_id,create_time,confirm_time,pay_type,price,spend,status,is_delete) 
				VALUES(".$param['user_id'].",".$param['round_id'].",".time().",".time().",4,".$param['sale_price'].",".$param['now_price'].",2,0)";
		$this->db->query($sql);
		$id = $this->db->insert_id();
        return $id > 0 ? $id : 0;
	}
	
	/**
	 * 学生与课制造者
	 * @应用场景：临时买课脚本
	 */
	public function student_class_maker($param)
	{
		if(empty($param['round_id']) || empty($param['user_id']) || empty($param['course_id']) || empty($param['class_id'])){
			exit('学生id与轮id与课程id与课id都不能为空');
		}
		$sql = "INSERT INTO student_class(student_id,course_id,round_id,class_id,status) 
				VALUES(".$param['round_id'].",".$param['user_id'].",".$param['course_id'].",".$param['class_id'].",0)";
		$this->db->query($sql);
		$int_result = $this->db->affected_rows();
        return $int_result;
	}
	
	/**
	 * 轮信息提取者
	 * 如：购买人数当前值，最大值查询
	 * @应用场景：临时买课脚本
	 */
	public function round_info_searcher($param)
	{
		$param['round_id'] 		= !empty($param['round_id']) ? $param['round_id'] : '';
		$param['round_name'] 	= !empty($param['round_name']) ? $param['round_name'] : '';
		if(empty($param['round_id']) && empty($param['round_name'])){
			exit('轮名与轮id不能都为空1');
		}
		$where = ' WHERE 1';
		$where .= !empty($param['round_name']) ? ' AND r.title like "%'.$param['round_name'].'%"' : '';
		$where .= !empty($param['round_id']) ? ' AND r.id='.$param['round_id'] : '';
		$order = ' ORDER BY r.start_time DESC';
		$limit = ' LIMIT 10';
		$sql = 'SELECT r.course_id,r.id,r.title,r.students,r.subject,r.sale_price,r.sale_status,r.teach_status,r.bought_count,r.caps,r.start_time,r.img,r.class_count,u.nickname teacherName,s.name subjectName
				FROM round r 
				LEFT JOIN round_teacher_relation rtr ON r.id=rtr.round_id 
				LEFT JOIN user u ON u.id=rtr.teacher_id 
				LEFT JOIN subject s ON s.id=r.subject
				'.$where.$order;
		$this->db->query('set names utf8');
		$arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
	}
	
	/**
	 * 查找一个轮可以买的具体的课id
	 */
	public function round_allow_class($param)
	{
		if(empty($param['round_id'])){exit('轮id不能为空');}
		$sql = "SELECT id FROM class 
				WHERE round_id=".$param['round_id']." 
				AND parent_id>0 
				AND status IN(".CLASS_STATUS_INIT.",".CLASS_STATUS_SOON_CLASS.")";
		$arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
	}
	 
	/**
	 * 查找一个轮可以买的课的数量：【初始化，即将开课】
	 */
	public function round_allow_num($param){
		if(empty($param['round_id'])){exit('轮id不能为空');}
		$sql = "SELECT count(id) as num FROM class WHERE 
				round_id=".$param['round_id']." AND 
				status in(".CLASS_STATUS_INIT.",".CLASS_STATUS_SOON_CLASS.") AND 
				parent_id>0";
		$arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
	}
	
	/**
	 * 用户信息提取者
	 * @应用场景：模糊查找用户
	 */
	public function user_info_searcher($param)
	{
		$param['user_id'] 	= !empty($param['user_id']) ? $param['user_id'] : '';
		$param['nickname'] 	= !empty($param['nickname']) ? $param['nickname'] : '';
		if(empty($param['nickname']) && empty($param['user_id'])){
			exit('昵称与用户id不能都为空');
		}
		$where = ' WHERE 1';
		$where .= !empty($param['nickname']) ? ' AND nickname like "%'.$param['nickname'].'%"' : '';
		$where .= !empty($param['user_id']) ? ' AND id='.$param['user_id'] : '';
		$limit = ' LIMIT 10';
		$sql = 'SELECT id,nickname FROM user '.$where.$limit;
		$this->db->query('set names utf8');
		$arr_result = $this->db->query($sql)->result_array();
		
        return $arr_result;
	}
	
	/**
	 * 轮信息升级者：
	 * 如：购买人数自增减1
	 * @应用场景：临时买课脚本
	 */
	public function round_upgrader($param)
	{
		if(empty($param['round_id'])){exit('轮id不能为空');}
		$sql = "UPDATE round SET bought_count=bought_count+1 WHERE id=".$param['round_id'];
		$this->db->query($sql);
		$int_result = $this->db->affected_rows();
        return $int_result;
	}
	
	/**
	 * 用户信息升级者
	 * 如：付费类型修改为付费用户/非付费用户
	 * @应用场景：临时买课脚本
	 */
	public function userinfo_upgrader($param)
	{
		if(empty($param['user_id'])){exit('学生id不能为空');}
		$sql = "UPDATE user_info SET has_bought=1 WHERE user_id=".$param['user_id'];
		$this->db->query($sql);
		$int_result = $this->db->affected_rows();
        return $int_result;
	}
}