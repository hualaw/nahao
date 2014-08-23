<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 分类搜索
 * jason song
 */
class Model_List extends NH_Model
{

	protected static $_orderConfig = array(
		1	=> ' ORDER BY r.sequence DESC,r.start_time DESC',//权重降序+时间降序
		2	=> ' ORDER BY (r.bought_count+r.extra_bought_count) DESC',//销量降序
		3 	=> ' ORDER BY r.sale_price DESC',//售价降序
		4	=> ' ORDER BY r.sale_price ASC',//售价升序
		5	=> ' ORDER BY r.start_time DESC',//时间降序
	);
	
    /**
     * 获取分类课程列表
     * 默认条件：销售中，非测试轮
     */
    public function search($param)
    {
		#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1 AND r.sale_status=3 AND r.is_test=0 AND r.is_live=0';//默认条件：销售中，非测试轮，非直播轮
		$where .= !empty($param['typeId']) ? ' AND r.education_type='.$param['typeId'] : '';
		$where .= !empty($param['stageId']) ? ' AND r.stage='.$param['stageId'] : '';
		$where .= !empty($param['gradeId']) ? ' AND r.grade_from<='.$param['gradeId'].' AND r.grade_to>='.$param['gradeId'] : '';
		$where .= !empty($param['subjectId']) ? ' AND r.subject='.$param['subjectId'] : '';
		$where .= !empty($param['qualityId']) ? ' AND r.quality='.$param['qualityId'] : '';
		$group = ' GROUP BY r.id ';
		$order = !empty($param['order']) ? self::$_orderConfig[$param['order']] : '';
		$limit = !empty($param['page']) && $param['page']>0 ? ' LIMIT '.(($param['page']-1)*$param['num']).','.$param['num'] : ' LIMIT '.$param['num']; 
		#2. 生成sql
        $this->db->query("set names utf8");
        if(!empty($param['counter']) && $param['counter']==1){#统计所有
        	$column = 'count(DISTINCT r.id) total';
        	$sql = 'SELECT '.$column.' FROM round r'.$where;
        }else{#分页记录
        	$column = 'DISTINCT r.id,r.title,r.subtitle,u.id teacher_id,u.nickname,u.avatar,ui.teacher_age,ui.teacher_intro,r.img,r.price,r.sale_price,r.sell_begin_time,r.sell_end_time,r.start_time,r.bought_count,r.extra_bought_count,r.teach_status,r.material_version,r.course_type';
			$sql = "SELECT ".$column." FROM round r 
				LEFT JOIN round_teacher_relation rtr ON rtr.round_id=r.id 
				LEFT JOIN user u ON u.id=rtr.teacher_id 
				LEFT JOIN user_info ui ON u.id=ui.user_id 
				".$where.$group.$order.$limit;
        }
		$arr_result = $this->db->query($sql)->result_array();
    	return $arr_result;
    }
    
    /**
     * 搜索推荐-猜你喜欢
     * 默认条件：销售中，非测试轮
     */
    public function search_suggest($param){
    	#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1 AND r.sale_status=3 AND r.is_test=0 AND r.is_live=0';//默认条件：销售中，非测试轮，非直播轮
		$where .= !empty($param['typeId']) ? ' AND r.education_type='.$param['typeId'] : '';
		$order = !empty($param['order']) ? self::$_orderConfig[$param['order']] : '';
		$limit = !empty($param['page']) && $param['page']>0 ? ' LIMIT '.(($param['page']-1)*$param['num']).','.$param['num'] : ' LIMIT '.$param['num']; 
    	#2. 生成sql
        $this->db->query("set names utf8");
		$column = 'DISTINCT r.id,r.title,r.img,r.price,r.sale_price,r.bought_count,r.extra_bought_count';
			$sql = "SELECT ".$column." FROM round r 
				".$where.$order.$limit;
		$arr_result = $this->db->query($sql)->result_array();
    	return $arr_result;
    } 
}