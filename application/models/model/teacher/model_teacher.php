<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * jason
 */
class Model_Teacher extends NH_Model{

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
        $sql = 'SELECT rtr.*,r.title,r.course_type,r.teach_status FROM round_teacher_relation rtr
 				LEFT JOIN nahao.round r ON rtr.round_id=r.id 
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
    	$arr_result = array();
    	$param['round_id'] = $param['round_id'] ? $param['round_id'] : 0;
    	if(!$param['round_id']){
        	exit('轮id为空，请检查数据是否正确');
        }
        $this->db->query("set names utf8");
    	$sql = 'SELECT c.*,cw.name coursewareName FROM class c
			LEFT JOIN courseware cw on c.courseware_id=cw.id 
			WHERE c.round_id='.$param['round_id'].' and c.status in(1,2,3) ORDER BY c.parent_id';
    	
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
}