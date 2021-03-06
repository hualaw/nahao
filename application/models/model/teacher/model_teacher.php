<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * jason
 */
class Model_Teacher extends NH_Model{

	static protected $_orderArr = array(
			1 => 'id',
			2 => 'begin_time',#开课时间
			3 => 'sequence',#权重
			4 => 'create_time',#评论时间
			5 => 'score',#评分
			6 => 'status',
	);
	
	static protected $_orderType = array(
			1=>'ASC',
			2=>'DESC'
	);
	
	protected $database = "nahao";
	
	/**
	 * 【超能统计搜索器 - 课】：
	 * param:id,teacher_id,begin_time,end_time,parent_id,status,teach_status,sale_status,subject,round_title,title,counter,order,orderType
	 **/
	public function class_seacher($param){
		$param['parent_id'] = !empty($param['parent_id']) ? $param['parent_id'] : '';
		$param['order'] = !empty($param['order']) ? $param['order'] : 1;
		$param['orderType'] = !empty($param['orderType']) ? $param['orderType'] : 1;
		$param['counter'] = !empty($param['counter']) ? $param['counter'] : '';
		#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1';
		$where .= !empty($param['id']) ? ' AND cl.id in('.$param['id'].')' : '';
		$where .= !empty($param['round_id']) ? ' AND cl.round_id='.$param['round_id'] : '';
		$where .= !empty($param['teacher_id']) ? ' AND rtr.teacher_id='.$param['teacher_id'] : '';
		$where .= !empty($param['begin_time']) ? ' AND cl.begin_time>='.$param['begin_time'] : '';
		$where .= !empty($param['end_time']) ? ' AND cl.end_time<='.$param['end_time'] : '';
		$where .= $param['parent_id']>0 ? ' AND cl.parent_id='.$param['parent_id'] 
				: ($param['parent_id']==-1 ? ' AND cl.parent_id=0' :			//-1表示只获取章
				($param['parent_id']==-2 ? ' AND cl.parent_id>0' : ''));		//-2表示只获取所有节
		$where .= !empty($param['status']) ? ' AND cl.status in('.$param['status'].')' : '';//课状态（0 初始化1 即将上课2 正在上课3 上完课4 缺课5 禁用 （不能恢复））
		$where .= !empty($param['teach_status']) ? ' AND r.teach_status in('.$param['teach_status'].')' : '';//轮授课状态（等待开课、授课中、停课（手动操作）、结课）
		$where .= !empty($param['sale_status']) ? ' AND r.sale_status in('.$param['sale_status'].')' : '';//轮销售状态（销售状态0 未审核、1 审核不通过、2 审核通过（预售）、3 销售中、4 已售罄、5 已停售（时间到了还没售罄）、6 已下架（手动下架））
		$where .= !empty($param['subject']) ? ' AND r.subject='.$param['subject'] : '';//科目
		$where .= !empty($param['round_title']) ? ' AND r.title like "%'.$param['round_title'].'%"' : '';//轮名
		$where .= !empty($param['title']) ? ' AND cl.title like "%'.$param['title'].'%"' : '';//课名
		
		$order = ' ORDER BY cl.'.self::$_orderArr[($param['order'] ? $param['order'] : 1)].' '.self::$_orderType[($param['orderType'] ? $param['orderType'] : 1)];
		if($param['order']==5){
			$order = " ORDER BY cl.parent_id ASC,cl.sequence ASC ";
		}
		$column = $param['counter']==1 ? 'count(DISTINCT cl.id) total' :'DISTINCT cl.*,r.title round_title,r.course_type,r.teach_status,r.subject,r.reward,c.score course_score,cw.name courseware_name,ct.name course_type_name,sub.name subject_name';
		$limit = !empty($param['limit']) ? ' LIMIT '.$param['limit'] : '';
		#2. 生成sql
        $this->db->query("set names utf8");
		$sql = "SELECT ".$column." 
				FROM class cl 
				LEFT JOIN round r ON cl.round_id=r.id 
				LEFT JOIN course c ON r.course_id=c.id 
				LEFT JOIN courseware cw ON cw.id=cl.courseware_id 
				LEFT JOIN round_teacher_relation rtr ON rtr.round_id=r.id 
				LEFT JOIN course_type ct ON r.course_type=ct.id 
				LEFT JOIN subject sub ON r.subject=sub.id 
				".$where.$order.$limit;
		$arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
	}
	
	/**
	 * 【超能统计搜索器 - 轮】：
	 * param:id,teacher_id,begin_time,start_time,end_time
	 **/
	public function round_seacher($param){
		$param['counter'] = !empty($param['counter']) ? $param['counter'] : '';
		#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1 AND cl.parent_id>0 ';
		$where .= !empty($param['id']) ? ' AND r.id='.$param['id'] : '';
		$where .= !empty($param['class_status']) ? ' AND cl.status in('.$param['class_status'].')' : '';
		$where .= !empty($param['teacher_id']) ? ' AND rtr.teacher_id='.$param['teacher_id'] : '';
		$where .= !empty($param['start_time']) ? ' AND r.start_time>='.$param['start_time'] : '';
		$where .= !empty($param['end_time']) ? ' AND r.end_time<='.$param['end_time'] : '';
		$where .= !empty($param['teach_status']) ? ' AND r.teach_status in('.($param['teach_status']==-1 ? 0 : $param['teach_status']).')' : '';//轮授课状态（等待开课、授课中、停课（手动操作）、结课）
		$where .= !empty($param['sale_status']) ? ' AND r.sale_status in('.$param['sale_status'].')' : '';//轮销售状态（销售状态0 未审核、1 审核不通过、2 审核通过（预售）、3 销售中、4 已售罄、5 已停售（时间到了还没售罄）、6 已下架（手动下架））
		$where .= !empty($param['course_type']) ? ' AND r.course_type in('.$param['course_type'].')' : '';//轮课程状态
		$where .= !empty($param['title']) ? ' AND r.title like "%'.$param['title'].'%"' : '';//轮名
		$group = $param['counter'] ? '' : " GROUP BY r.id";
		$order = " ORDER BY cl.begin_time DESC";
		$limit = !empty($param['limit']) ? " LIMIT ".$param['limit'] : '';
		$column = $param['counter']==1 ? 'count(DISTINCT r.id) total' :'DISTINCT r.*,c.score course_score,cw.name courseware_name,cl.title class_name,cl.classroom_id,cl.begin_time class_start_time,cl.end_time class_end_time,cl.status class_status,ct.name course_type_name,sub.name subject_name';
		#2. 生成sql
        $this->db->query("set names utf8");
		$sql = "SELECT ".$column."  
				FROM round r 
				LEFT JOIN class cl ON cl.round_id=r.id 
				LEFT JOIN course c ON r.course_id=c.id 
				LEFT JOIN courseware cw ON cw.id=cl.courseware_id 
				LEFT JOIN round_teacher_relation rtr ON rtr.round_id=r.id 
				LEFT JOIN course_type ct ON r.course_type=ct.id 
				LEFT JOIN subject sub ON r.subject=sub.id 
				".$where.$group.$order.$limit;
		$arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
	}
	
	/**
	 * 老师轮状态统计
	 **/
    public function round_status_counter($param){
    	$arr_result = array();
		$where = ' WHERE 1 AND cl.parent_id>0 ';
		$where .= !empty($param['teacher_id']) ? ' AND rtr.teacher_id='.$param['teacher_id'] : '';
		$where .= !empty($param['teach_status']) ? ' AND r.teach_status in('.($param['teach_status']==-1 ? 0 : $param['teach_status']).')' : '';
    	$sql = "SELECT count(distinct r.id) total
    			FROM round r 
    			LEFT JOIN class cl ON cl.round_id=r.id 
    			LEFT JOIN round_teacher_relation rtr ON rtr.round_id=r.id 
    			".$where;
    	$arr_result = $this->db->query($sql)->result_array();
    	return $arr_result;
    } 
	
	/**
	 * 【题目搜索器】：
	 * pararm : status,class_id,counter
	 */
	public function question_seacher($param){
		$param['counter'] = !empty($param['counter']) ? $param['counter'] : '';
		$param['status'] = !empty($param['status']) ? $param['status'] : '';
		#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1';
		$where .= $param['status']>0 ? ' AND qcr.status='.$param['status'] : 
					($param['status']==-1 ? ' AND qcr.status=0' : '');
		$where .= !empty($param['class_id']) ? ' AND qcr.class_id='.$param['class_id'] : '';
		$where .= !empty($param['classroom_id']) ? ' AND cl.classroom_id='.$param['classroom_id'] : '';
		$where .= !empty($param['sequence']) ? ' AND qcr.sequence='.$param['sequence'] : '';
		$order = " ORDER BY q.id ASC";
		$column = $param['counter']==1 ? 'count(q.id) total' :
				( $param['counter']==2 ? 'count(distinct q.id) total' 
				: 'cl.title class_title,cl.id class_id,q.*,qcr.question_id,qcr.status,qcr.sequence');
		#2. 生成sql
        $this->db->query("set names utf8");
        
		$sql = "SELECT ".$column." 
				FROM question q 
				LEFT JOIN question_class_relation qcr ON qcr.question_id=q.id 
				LEFT JOIN class cl ON qcr.class_id=cl.id ".$where.$order;
		$arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
	}
	
	/**
	 * 获取课的出题批次
	 **/
	public function get_sequence($param){
		#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1';
		$where .= !empty($param['class_id']) ? ' AND qcr.class_id='.$param['class_id'] : '';
		$where .= !empty($param['classroom_id']) ? ' AND cl.classroom_id='.$param['classroom_id'] : '';
		#2. 生成sql
        $this->db->query("set names utf8");
        
		$sql = "SELECT max(qcr.sequence) total 
				FROM question_class_relation qcr 
				LEFT JOIN class cl ON qcr.class_id=cl.id 
				".$where;
		$arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
	}
	
	/**
	 * 【练习统计器】：
	 * pararm : is_correct,sequence,class_id,student_id
	 **/
	public function practise_counter($param){
		#1. 参数组合
		$arr_result = array();
		$param['counter'] = !empty($param['counter']) ? $param['counter'] : 2;
		$where = ' WHERE 1';
		$where .= !empty($param['question_id']) ? ' AND sq.question_id='.$param['question_id'] : '';
		$where .= !empty($param['is_correct']) ? ' AND sq.is_correct='.$param['is_correct'] : '';
		$where .= !empty($param['sequence']) ? ' AND sq.sequence='.$param['sequence'] : '';
		$where .= !empty($param['class_id']) ? ' AND sq.class_id='.$param['class_id'] : '';
		$where .= !empty($param['classroom_id']) ? ' AND cl.classroom_id='.$param['classroom_id'] : '';
		$where .= !empty($param['answer']) ? ' AND FIND_IN_SET("'.$param['answer'].'",sq.answer)' : '';
		$column = $param['counter']==1 ? 'count(sq.id) total' 
			: ( $param['counter']==2 ? 'count(DISTINCT sq.student_id) total' 
			: ( $param['counter']==3 ? 'count(DISTINCT sq.question_id) total' 
			:'DISTINCT sq.*'));
		#2. 生成sql
        $this->db->query("set names utf8");
		$sql = "SELECT ".$column." 
				FROM student_question sq 
				LEFT JOIN class cl ON sq.class_id=cl.id ".$where;
		$arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
	}
	
	/**
	 * 薪酬结算统计
	 * param: teacher_id,status(0未结算,1已结算),
	 **/
	public function pay_list($param){
		#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1';
		$where .= !empty($param['id']) ? ' AND tcl.id='.$param['id'] : '';
		$where .= !empty($param['teacher_id']) ? ' AND tcl.teacher_id='.$param['teacher_id'] : '';
		$where .= !empty($param['status']) ? ' AND tcl.status='.$param['status'] : '';
		$where .= !empty($param['start_time']) ? ' AND tcl.pay_time>='.$param['start_time'] : '';
		$where .= !empty($param['end_time']) ? ' AND tcl.end_time<='.$param['end_time'] : '';
		$order = ' ORDER BY tcl.create_time DESC';
		#2. 生成sql
        $this->db->query("set names utf8");
		$sql = "SELECT tcl.*
				FROM teacher_checkout_log tcl ".$where.$order;
		$arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
	}
	
	/**
	 * 学员评价
	 * param:round_id,class_id,
	 **/
	public function student_comment($param){
		$param['counter'] = !empty($param['counter']) ? $param['counter'] : '';
		$param['order'] = !empty($param['order']) ? $param['order'] : '';
		$param['orderType'] = !empty($param['orderType']) ? $param['orderType'] : '';
		#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1';
		$where .= !empty($param['course_id']) ? ' AND cf.course_id='.$param['course_id'] : '';
		$where .= !empty($param['round_id']) ? ' AND cf.round_id='.$param['round_id'] : '';
		$where .= !empty($param['class_id']) ? ' AND cf.class_id='.$param['class_id'] : '';
		$where .= !empty($param['student_id']) ? ' AND cf.student_id='.$param['student_id'] : '';
		$where .= !empty($param['is_show']) ? ' AND cf.is_show='.$param['is_show'] : '';
		$where .= !empty($param['score']) ? ' AND cf.score='.$param['score'] : '';
		$order = " ORDER BY cf.".self::$_orderArr[($param['order'] ? $param['order'] : 4)]." ".self::$_orderType[($param['orderType'] ? $param['orderType'] : 1)];
		$column = $param['counter']==1 ? 'count(cf.id) total' :
				($param['counter']==2 ? 'avg(cf.score) score' : 'cf.*');
		#2. 生成sql
        $this->db->query("set names utf8");
		$sql = "SELECT ".$column." 
				FROM class_feedback cf ".$where.$order;
		$arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
	} 
	
	/**
     * 获得地区
     */
    public function get_area($param){
    	$this->db->query("set names utf8");
    	$param['id'] = !empty($param['id']) ? $param['id'] : '';
    	$param['parentid'] = !empty($param['parentid']) ? $param['parentid'] : -1;
    	$param['level'] = !empty($param['level']) ? $param['level'] : -1;
    	$where = ' WHERE 1';
    	$where .= $param['id'] ? ' AND id='.$param['id'] : '';
    	$where .= $param['parentid'] >= 0 ? ' AND parentid='.$param['parentid'] : '';
    	$where .= $param['level'] >= 0 ? ' AND level='.$param['level'] : '';
    	$sql = 'SELECT * FROM nahao_areas'.$where;
        return $this->db->query($sql)->result_array();
    }
	
    /**
     * 录题管理器
     * param : id,question,answer,options,analysis,class_id,question_id,status,sequence
     * do,delete_class_question,delete_lesson_question
     **/
     public function question_manager($param){
     	if(empty($param['do'])){exit("缺少操作参数");}
     	$param['id'] = !empty($param['id']) ? $param['id'] : '';
     	$param['question'] = !empty($param['question']) ? $param['question'] : '';
     	$param['answer'] = !empty($param['answer']) ? $param['answer'] : '';
     	$param['options'] = !empty($param['options']) ? $param['options'] : '';
     	$param['type'] = !empty($param['type']) ? $param['type'] : '';
     	$param['analysis'] = !empty($param['analysis']) ? $param['analysis'] : '';
     	$param['question_id'] = !empty($param['question_id']) ? $param['question_id'] : '';
     	$param['status'] = !empty($param['status']) ? $param['status'] : '';
     	$param['sequence'] = !empty($param['sequence']) ? $param['sequence'] : '';
     	$this->db->query("set names utf8");
     	switch ($param['do']){
     		case 'add':
     			$sql = "INSERT INTO question(question,answer,options,question.type,analysis) 
						VALUES('".$param['question']."','".$param['answer']."','".$param['options']."',".$param['type'].",'".$param['analysis']."')";
     			$res= $this->db->query($sql);
     			$id = $this->db->insert_id();
     			if($id){
     				$sql = "REPLACE INTO question_class_relation(class_id,question_id,question_class_relation.status,sequence) 
							VALUES(".$id.",".$param['question_id'].",".$param['status'].",".$param['sequence'].")";
     				$res = $this->db->query($sql);
     			}
     			break;
     		case 'edit':
     			$sql = "UPDATE question 
						SET question='".$param['question']."',
							answer='".$param['answer']."',
							options='".$param['options']."',
							question.type=1,
							analysis='".$param['analysis']."' 
						WHERE 1 AND id=".$param['id'];
     			$res = $this->db->query($sql);
     			break;
     		case 'delete':
     			if(!empty($param['delete_class_question']) && !empty($param['delete_lesson_question'])){
     				exit('为防止误操作，不可缺少参数');
     			}
     			$sql = "DELETE FROM question WHERE id=".$param['id'];
     			$ress = $this->db->query($sql);
     			if($ress){
     				//删除题课关系
     				if($param['delete_class_question']){
     					$res = $this->db->query("DELETE FROM question_class_relation WHERE question_id=".$param['id']);
     				}
     				//删除题课节关系
     				if($param['delete_lesson_question']){
     					$res = $this->db->query("DELETE FROM question_lesson_relation WHERE question_id=".$param['id']);
     				}
     			}
     			break;
     	}
     	return $res;
     }
    
     /**
      * 讲义管理器
      **/ 
     public function courseware_manager($param){
     	if(empty($param['do'])){exit("缺少操作参数");}
     	$param['do'] = !empty($param['do']) ? $param['do'] : '';
     	$this->db->query("set names utf8");
     	switch ($param['do']){
     		case 'add':
     			$sql = "INSERT INTO courseware(name,create_time,courseware.status) 
						VALUES('对外汉语.pdf',1401321321,0)";
     			$id = $this->db->query($sql)->insert_id();
     			break;
     		case 'edit':
     			break;
     		case 'delete':
     			break;
     	}
     }
     
    /**
     * 出题与反出题
     * param:class_id,question_id in(1,2,3)
     **/
    public function set_question($param){
    	if(empty($param['class_id']) && empty($param['classroom_id'])){
    		exit('为避免误操作，班次id不能为空');
    	}
    	$param['class_id'] = !empty($param['class_id']) ? $param['class_id'] : '';
    	$param['question_id'] = !empty($param['question_id']) ? $param['question_id'] : '';
    	$param['sequence'] = !empty($param['sequence']) ? $param['sequence'] : '';
    	#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1';
		$where .= $param['class_id'] ? ' AND qcr.class_id='.$param['class_id'] : '';
		$where .= $param['question_id'] ? ' AND qcr.question_id in('.$param['question_id'].')' : '';//一批题
		$set = $param['sequence'] > 0 ? ' SET qcr.status=1,qcr.sequence='.$param['sequence'] //出题
			: ($param['sequence'] == -1 ? ' SET qcr.status=0,qcr.sequence=0' : ' SET qcr.status=1,qcr.sequence=1');//取消出题
		#2. 生成sql
        $this->db->query("set names utf8");
		$sql = "UPDATE question_class_relation qcr ".$set.$where;
        return $this->db->query($sql);
    }
     
    /**
     * 做题
     * param:class_id,student_id,question_id,answer,is_correct,sequence
     **/
    public function reply_question($param){
    	$param['class_id'] = !empty($param['class_id']) ? $param['class_id'] : '';
    	$param['student_id'] = !empty($param['student_id']) ? $param['student_id'] : '';
    	$param['question_id'] = !empty($param['question_id']) ? $param['question_id'] : '';
    	$param['answer'] = !empty($param['answer']) ? $param['answer'] : '';
    	$param['is_correct'] = !empty($param['is_correct']) ? $param['is_correct'] : '';
    	$param['sequence'] = !empty($param['sequence']) ? $param['sequence'] : '';
    	$this->db->query("set names utf8");
    	$sql = "INSERT INTO sutdent_question(class_id,student_id,question_id,answer,is_correct,sequence) 
				VALUES(".$param['class_id'].",".$param['student_id'].",".$param['question_id'].",'".$param['answer']."',
				".$param['is_correct'].",".$param['sequence'].")";
    	return $this->db->query($sql);
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
        $param['user_id'] = !empty($param['user_id']) ? $param['user_id'] : 0;
        if(!$param['user_id']){
        	exit('老师id为空，请确认登陆状态是否过期');
        }
        $this->db->query("set names utf8");
        $sql = 'SELECT rtr.*,r.title,r.course_type,r.teach_status,r.subject,c.score course_score 
        		FROM round_teacher_relation rtr
 				LEFT JOIN round r ON rtr.round_id=r.id 
 				LEFT JOIN course c ON r.course_id=c.id 
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
    	$param['round_id'] = !empty($param['round_id']) ? $param['round_id'] : 0;
    	if(!$param['round_id']){
        	exit('轮id为空，请检查数据是否正确');
        }
        $order = !empty($param['adminOrder']) ? $param['adminOrder'] : 1;
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
    	$param['course'] = !empty($param['course']) ? $param['course'] : '';
    	$param['resume'] = !empty($param['resume']) ? $param['resume'] : '';
    	$param['subject'] = !empty($param['subject']) ? $param['subject'] : '';
    	$param['status'] = !empty($param['status']) ? $param['status'] : '';
    	$param['admin_id'] = !empty($param['admin_id']) ? $param['admin_id'] : '';
    	$param['province'] = !empty($param['province']) ? $param['province'] : '';
    	$param['city'] = !empty($param['city']) ? $param['city'] : '';
    	$param['area'] = !empty($param['area']) ? $param['area'] : '';
    	$param['school'] = !empty($param['school']) ? $param['school'] : '';
    	$param['stage'] = !empty($param['stage']) ? $param['stage'] : '';
    	$param['teach_years'] = !empty($param['teach_years']) ? $param['teach_years'] : '';
    	$param['course_intro'] = !empty($param['course_intro']) ? $param['course_intro'] : '';
    	$param['teach_type'] = !empty($param['teach_type']) ? $param['teach_type'] : '';
    	$param['gender'] = !empty($param['gender']) ? $param['gender'] : '';
    	$param['title'] = !empty($param['title']) ? $param['title'] : '';
    	$param['age'] = !empty($param['age']) ? $param['age'] : '';
    	$param['phone'] = !empty($param['phone']) ? $param['phone'] : '';
    	$param['email'] = !empty($param['email']) ? $param['email'] : '';
    	$param['qq'] = !empty($param['qq']) ? $param['qq'] : '';
    	$param['start_time'] = !empty($param['start_time']) ? $param['start_time'] : '';
    	$param['end_time'] = !empty($param['end_time']) ? $param['end_time'] : '';
    	$param['name'] = !empty($param['name']) ? $param['name'] : '';
    	$param['user_id'] = !empty($param['user_id']) ? $param['user_id'] : '';
    	$this->db->query("set names utf8");
    	$sql = 'INSERT INTO teacher_lecture(course,resume,subject,teacher_lecture.status,admin_id,
    		create_time,province,city,area,school,stage,teach_years,course_intro,teach_type,gender,title,
    		age,phone,email,qq,start_time,end_time,name,user_id) 
		VALUES("'.$param['course'].'","'.$param['resume'].'","'.$param['subject'].'",'.$param['status'].',
			'.$param['admin_id'].','.time().','.$param['province'].','.$param['city'].','.$param['area'].',
			'.$param['school'].','.$param['stage'].','.$param['teach_years'].',"'.$param['course_intro'].'",
			'.$param['teach_type'].','.$param['gender'].','.$param['title'].','.$param['age'].',"'.$param['phone'].'",
			"'.$param['email'].'","'.$param['qq'].'",'.$param['start_time'].','.$param['end_time'].',"'.$param['name'].'",'.$param['user_id'].')';
    	return $this->db->query($sql);
    }
    
    /**
     * 课学生出勤率，注意：分2次读，第一次读进入教室的次数，如果大于0才继续统计。否则终止
     **/
     public function class_attendance($param){
     	$param['class_id'] = !empty($param['class_id']) ? $param['class_id'] : '';
     	$param['status'] = !empty($param['status']) ? $param['status'] : '';
     	$arr_result = array();
        $where = ' WHERE 1';
       	$where .= $param['class_id'] ? ' AND class_id='.$param['class_id'] : '';
       	$where .= $param['status'] ? ' AND status in('.$param['status'].')' : '';
        $this->db->query("set names utf8");
        $sql = 'SELECT count(id) total FROM student_class '.$where;
        $arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
     }
    
    /**
	 * 获取所有课程状态
	 **/
     public function get_course_type(){
     	$this->db->query("set names utf8");
     	$sql = "SELECT * FROM course_type";
     	return $this->db->query($sql)->result_array();
     }
     
     /**
	 * 获取所有课程状态
	 **/
     public function get_subject(){
     	$this->db->query("set names utf8");
     	$sql = "SELECT * FROM subject";
     	return $this->db->query($sql)->result_array();
     }
     
     /**
	  * 获取课堂学生动作统计，赞快慢
	  **/ 
     public function count_classroom_action($param){
     	$param['class_id'] = !empty($param['class_id']) ? $param['class_id'] : '';
     	$param['classroom_id'] = !empty($param['classroom_id']) ? $param['classroom_id'] : '';
     	$arr_result = array();
        $where = ' WHERE 1';
        $where .= $param['class_id'] ? ' AND cl.id='.$param['class_id'] : '';
       	$where .= $param['classroom_id'] ? ' AND cl.classroom_id='.$param['classroom_id'] : '';
       	$group = ' GROUP BY scl.action';
     	$column = "DISTINCT scl.action,COUNT(scl.action) total";
     	$sql = "SELECT ".$column." FROM student_class_log scl 
				LEFT JOIN class cl ON scl.class_id=cl.id 
				".$where.$group;
     	return $this->db->query($sql)->result_array();
     }
     
     /**
      * 只获取教师课id列表
      **/
      public function teacher_class_ids($param){
      	 $sql = "SELECT cl.id FROM class cl 
      	         LEFT JOIN round_teacher_relation rtr ON cl.round_id=rtr.round_id
      	         WHERE 1 AND rtr.teacher_id=".$param['teacher_id'];
      	 return $this->db->query($sql)->result_array();
      }
      
      /**
      * 只获取教师课id列表
      **/
      public function teacher_round_ids($param){
      	 $sql = "SELECT r.id FROM round r 
      	         LEFT JOIN round_teacher_relation rtr ON r.id=rtr.round_id
      	         WHERE 1 AND rtr.teacher_id=".$param['teacher_id'];
      	 return $this->db->query($sql)->result_array();
      }
     
     /*******************************		自动运行操作start		********************************/
     /**
      * 重置课平均分
      **/ 
     public function set_class_score($param){
     	$param['class_id'] = !empty($param['class_id']) ? $param['class_id'] : 0;
     	$param['score'] = !empty($param['score']) ? round($param['score'],'2') : 0;
     	$sql = "UPDATE class cl SET cl.score='".$param['score']."' WHERE cl.id=".$param['class_id']." LIMIT 1";
     	$this->db->query($sql);
     	$int_row = $this->db->affected_rows();
        return $bool_result = $int_row > 0  ? true : false;
     }
     
     /**
      * 重置轮平均分
      **/ 
     public function set_round_score($param){
     	$param['round_id'] = !empty($param['round_id']) ? $param['round_id'] : 0;
     	$param['score'] = !empty($param['score']) ? round($param['score'],'2') : 0;
     	$sql = "UPDATE round r SET r.score='".$param['score']."' WHERE r.id=".$param['round_id']." LIMIT 1";
     	$this->db->query($sql);
     	$int_row = $this->db->affected_rows();
        return $bool_result = $int_row > 0  ? true : false;
     }
     
     /**
      * 重置课程平均分
      **/ 
     public function set_course_score($param){
     	$param['course_id'] = !empty($param['course_id']) ? $param['course_id'] : 0;
     	$param['score'] = !empty($param['score']) ? round($param['score'],'2') : 0;
     	$sql = "UPDATE course c SET c.score='".$param['score']."' WHERE c.id=".$param['course_id']." LIMIT 1";
     	$this->db->query($sql);
     	$int_row = $this->db->affected_rows();
        return $bool_result = $int_row > 0  ? true : false;
     }
     
     /**
	  * 设置订单状态
	  **/
     public function set_order_status($param){
     	$where = ' WHERE 1 ';
     	$where .= !empty($param['create_time_from']) ? ' AND so.create_time<'.$param['create_time_from'] : '';
//     	$where .= !empty($param['create_time_to']) ? ' AND so.create_time<'.$param['create_time_to'] : '';
     	$where .= !empty($param['statusFrom']) ? ' AND so.status in('.$param['statusFrom'].')' : '';
     	$where .= !empty($param['sale_status']) ? ' AND r.sale_status in('.$param['sale_status'].')' : '';
     	$set = '';
     	$set .= !empty($param['statusTo']) ? ',so.status='.$param['statusTo'] : '';
     	$set = trim($set,',');
     	$sql = "UPDATE student_order so 
     			LEFT JOIN round r ON r.id=so.round_id 
     			SET ".$set.$where;
     	$this->db->query($sql);
     	$int_row = $this->db->affected_rows();
     	return $bool_result = $int_row > 0  ? true : false;
     } 
     
     /**
	  * 按整月取上课记录，属于上述方法class_seacher（27行）的简单版。
	  **/ 
     public function month_class_seacher($param){
     	$this->db->query("set names utf8");
     	$where = ' WHERE 1 AND cal.user_id>0';
     	$where .= !empty($param['start_time']) ? ' AND cl.begin_time>'.$param['start_time'].' AND cl.end_time>'.$param['start_time'] : '';
     	$where .= !empty($param['end_time']) ? ' AND cl.begin_time<='.$param['end_time'].' AND cl.end_time<='.$param['end_time'] : '';
     	$where .= !empty($param['status']) ? ' AND cl.status in('.$param['status'].')' : '';
     	$sql = "SELECT DISTINCT cl.id,cl.title,cl.round_id,cl.school_hour,rtr.teacher_id,r.reward FROM class cl 
				LEFT JOIN round_teacher_relation rtr ON rtr.round_id=cl.round_id 
				LEFT JOIN round r ON r.id=cl.round_id 
				LEFT JOIN class_action_log cal ON cal.user_id=rtr.teacher_id AND cal.classroom_id=cl.classroom_id 
				".$where;
     	return $this->db->query($sql)->result_array();
     }
     
     /**
      * 修改每一个课的结算状态
      **/
     public function set_class_checkout_status($param){
     	$param['to_checkout_status'] = !empty($param['to_checkout_status']) ? $param['to_checkout_status'] : 1;
     	$sql = "UPDATE class SET checkout_status=".$param['to_checkout_status']." WHERE id=".$param['class_id'];
     	$this->db->query($sql);
     	$int_row = $this->db->affected_rows();
     	return $bool_result = $int_row > 0  ? true : false;
     }
     
      
     /**
	  *	生成新月份课酬统计记录
	  **/ 
     public function create_teacher_checkout_log($param){
     	$sql = "INSERT INTO teacher_checkout_log(teacher_id,status,teach_times,class_times,
     				gross_income,net_income,deduct,tax,create_time,pay_time,checkout_time,class_ids)
				VALUES(".$param['teacher_id'].",".$param['status'].",".$param['teach_times'].",
					".$param['class_times'].",".$param['gross_income'].",".$param['net_income'].",
					".$param['deduct'].",".$param['tax'].",unix_timestamp(),'','','".$param['class_ids']."')";
     	$this->db->query($sql);
     	$int_row = $this->db->affected_rows();
     	return $bool_result = $int_row > 0  ? true : false;
     }

    /**
     * 根据user_id查教室id
     * @author shangshikai@tizi.com
     */
    public function get_by_lecture_class_classroom_id($user_id)
    {
        return $this->db->select(TABLE_LECTURE_CLASS.'.classroom_id')->from(TABLE_LECTURE_CLASS)->order_by(TABLE_LECTURE_CLASS.'.id','desc')->where(TABLE_LECTURE_CLASS.'.user_id',$user_id)->get()->row_array();
    }
}