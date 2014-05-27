<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * jason
 */
class Model_Teacher extends NH_Model{

	static protected $_orderArr = array(
			1 => 'id',
			2 => 'begin_time',#开课时间
			3 => 'sequence',#权重
	);
	static protected $_orderType = array(
			1=>'ASC',
			2=>'DESC'
	);
	
	/**
	 * 【超能统计搜索器 - 课】：
	 * 课class，条件搜索列表
	 * 1.时间段begin_time,end_time
	 * 2.老师id
	 * ...
	 * 使用方法：
	 *    按需传递参数,只需摇一摇、舔一舔、再泡一泡
	 **/
	public function class_seacher($param){
		#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1';
		$where .= $param['teacher_id'] ? ' AND rtr.teacher_id='.$param['teacher_id'] : '';
		$where .= $param['begin_time'] ? ' AND cl.begin_time>='.$param['begin_time'] : '';
		$where .= $param['end_time'] ? ' AND cl.end_time<='.$param['end_time'] : '';
		$where .= $param['parent_id']>0 ? ' AND cl.parent_id='.$param['parent_id'] 
				: ($param['parent_id']==-1 ? ' AND cl.parent_id=0' :			//-1表示只获取章
				($param['parent_id']==-2 ? ' AND cl.parent_id>0' : ''));		//-2表示只获取所有节
		$where .= $param['status'] ? ' AND cl.status in('.$param['status'].')' : '';//课状态（0 初始化1 即将上课2 正在上课3 上完课4 缺课5 禁用 （不能恢复））
		$where .= $param['teach_status'] ? ' AND r.teach_status in('.$param['teach_status'].')' : '';//轮授课状态（等待开课、授课中、停课（手动操作）、结课）
		$where .= $param['sale_status'] ? ' AND r.sale_status in('.$param['sale_status'].')' : '';//轮销售状态（销售状态0 未审核、1 审核不通过、2 审核通过（预售）、3 销售中、4 已售罄、5 已停售（时间到了还没售罄）、6 已下架（手动下架））
		$where .= $param['subject'] ? ' AND r.subject='.$param['subject'] : '';//科目
		$where .= $param['round_title'] ? ' AND r.title like "%'.$param['round_title'].'%"' : '';//轮名
		$where .= $param['title'] ? ' AND cl.title like "%'.$param['title'].'%"' : '';//课名
		
		$order = ' ORDER BY cl.'.self::$_orderArr[$param['order'] ? $param['order'] : 1].' '.self::$_orderType[($param['orderType'] ? $param['orderType'] : 1)];
		$column = $param['counter']==1 ? 'count(cl.*) total' :'cl.*,r.title round_title,r.course_type,r.teach_status,r.subject,r.reward,c.score course_score';
		#2. 生成sql
        $this->db->query("set names utf8");
		$sql = "SELECT ".$column." 
				FROM class cl 
				LEFT JOIN nahao.round r ON cl.round_id=r.id 
				LEFT JOIN nahao.course c ON r.course_id=c.id 
				LEFT JOIN round_teacher_relation rtr ON rtr.round_id=r.id ".$where.$order;
		$arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
	}
	
	/**
	 * 【题目搜索器】：
	 */
	public function question_seacher(){
		#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1';
		$where .= $param['teacher_id'] ? ' AND rtr.teacher_id='.$param['teacher_id'] : '';
		#2. 生成sql
        $this->db->query("set names utf8");
        
		$sql = "";
		$arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
	}
	
	/**
	 * 老师与轮关系表：round_teacher_relation
	 * 轮表：round
	 * 系统：-1
	 * 管理员：0
	 * 学生：1
	 * 老师：2
	 * 造数据字段说明：
	 * students适合人群
	 * reward每课时报酬，price创建轮的价格。
	 * status状态：0初始化;5审核中;10运营中;15暂停;20关闭;25
	 * role 创建人角色
	 * user_id 创建人
	 * score 课程总评分
	 * bought_count 购买人数
	 * graduate_count 结课人数
	 * video 视频地址
	 * img封面原图地址
	 */
    public function teacher_today_round($param){
        $arr_result = array();
        $param['user_id'] = $param['user_id'] ? $param['user_id'] : 0;
        if(!$param['user_id']){
        	exit('老师id为空，请确认登陆状态是否过期');
        }
        $this->db->query("set names utf8");
        $sql = 'SELECT rtr.*,r.title,r.course_type,r.teach_status,r.subject,c.score course_score 
        		FROM round_teacher_relation rtr
 				LEFT JOIN nahao.round r ON rtr.round_id=r.id 
 				LEFT JOIN nahao.course c ON r.course_id=c.id 
 				WHERE rtr.teacher_id='.$param['user_id'];
        
        $arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
    }
    
    /**
     * 轮的课章节列表和进度
     * class课的状态：
     * 0 初始化 1 即将上课 2 正在上课 3 上完课 4 缺课 5 禁用 （不能恢复）
     */
    public function teacher_round_class($param){
    	$orderArr = array(
    		1 => ' ORDER BY c.parent_id',
    		2 => ' ORDER BY c.sequence',
    	);
    	$arr_result = array();
    	$param['round_id'] = $param['round_id'] ? $param['round_id'] : 0;
    	if(!$param['round_id']){
        	exit('轮id为空，请检查数据是否正确');
        }
        $order = isset($param['order']) ? $param['order'] : 1;
        $this->db->query("set names utf8");
    	$sql = 'SELECT c.*,cw.name coursewareName FROM class c
			LEFT JOIN courseware cw on c.courseware_id=cw.id 
			WHERE c.round_id='.$param['round_id'].' and c.status in(1,2,3)'.$orderArr[$order];
        $arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
    }
    
    /**
     * 开课申请，教师扩展表
     */
    public function apply_teach($param){
    	$this->db->query("set names utf8");
    	$sql = 'INSERT INTO teacher_info(user_id,realname,age,gender,hide_realname,hide_school,hide_area,
id_code,title,work_auth,teacher_auth,titile_auth,province,city,area,school,remuneration,teacher_age,stage)
VALUES('.$param['user_id'].','.$param['realname'].','.$param['age'].','.$param['gender'].','.$param['hide_realname'].'
,'.$param['hide_school'].','.$param['hide_area'].','.$param['id_code'].','.$param['title'].','.$param['work_auth'].'
,'.$param['teacher_auth'].','.$param['titile_auth'].','.$param['province'].','.$param['city'].'
,'.$param['area'].','.$param['school'].','.$param['remuneration'].','.$param['teacher_age'].','.$param['stage'].')';
    	return $this->db->query($sql);
    }
    
    /**
     * 开课申请，教师扩展表
     */
    public function get_area($param){
    	$this->db->query("set names utf8");
    	$param['id'] = isset($param['id']) ? $param['id'] : '';
    	$param['parentid'] = isset($param['parentid']) ? $param['parentid'] : -1;
    	$param['level'] = isset($param['level']) ? $param['level'] : -1;
    	$where = ' WHERE 1';
    	$where .= $param['id'] ? ' AND id='.$param['id'] : '';
    	$where .= $param['parentid'] >= 0 ? ' AND parentid='.$param['parentid'] : '';
    	$where .= $param['level'] >= 0 ? ' AND level='.$param['level'] : '';
    	$sql = 'SELECT * FROM nahao_areas'.$where;
        return $this->db->query($sql)->result_array();
    }
    
    
}