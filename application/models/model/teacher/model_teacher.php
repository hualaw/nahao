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
		#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1';
		$where .= $param['id'] ? ' AND cl.id='.$param['id'] : '';
		$where .= $param['round_id'] ? ' AND cl.round_id='.$param['round_id'] : '';
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
		
		$order = ' ORDER BY cl.'.self::$_orderArr[($param['order'] ? $param['order'] : 1)].' '.self::$_orderType[($param['orderType'] ? $param['orderType'] : 1)];
		$column = $param['counter']==1 ? 'count(cl.id) total' :'cl.*,r.title round_title,r.course_type,r.teach_status,r.subject,r.reward,c.score course_score,cw.name courseware_name,ct.name course_type_name,sub.name subject_name';
		#2. 生成sql
        $this->db->query("set names utf8");
		$sql = "SELECT ".$column." 
				FROM nahao.class cl 
				LEFT JOIN nahao.round r ON cl.round_id=r.id 
				LEFT JOIN nahao.course c ON r.course_id=c.id 
				LEFT JOIN nahao.courseware cw ON cw.id=cl.courseware_id 
				LEFT JOIN nahao.round_teacher_relation rtr ON rtr.round_id=r.id 
				LEFT JOIN nahao.course_type ct ON r.course_type=ct.id 
				LEFT JOIN nahao.subject sub ON r.subject=sub.id 
				".$where.$order;
		$arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
	}
	
	/**
	 * 【超能统计搜索器 - 轮】：
	 * param:id,teacher_id,begin_time,start_time,end_time
	 **/
	public function round_seacher($param){
		#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1 AND cl.parent_id>0';
		$where .= $param['id'] ? ' AND r.id='.$param['id'] : '';
		$where .= $param['teacher_id'] ? ' AND rtr.teacher_id='.$param['teacher_id'] : '';
		$where .= $param['start_time'] ? ' AND r.start_time>='.$param['start_time'] : '';
		$where .= $param['end_time'] ? ' AND r.end_time<='.$param['end_time'] : '';
		$where .= $param['teach_status'] ? ' AND r.teach_status in('.($param['teach_status']==-1 ? 0 : $param['teach_status']).')' : '';//轮授课状态（等待开课、授课中、停课（手动操作）、结课）
		$where .= $param['course_type'] ? ' AND r.course_type in('.$param['course_type'].')' : '';//轮课程状态
		$where .= $param['title'] ? ' AND r.title like "%'.$param['title'].'%"' : '';//轮名
		$group = " GROUP BY r.id";
		$order = " ORDER BY cl.begin_time DESC";
		$limit = $param['limit'] ? " LIMIT ".$param['limit'] : '';
		$column = $param['counter']==1 ? 'count(r.id) total' :'r.*,c.score course_score,cw.name courseware_name,cl.title class_name,cl.begin_time class_start_time,cl.end_time class_end_time,ct.name course_type_name,sub.name subject_name';
		#2. 生成sql
        $this->db->query("set names utf8");
		$sql = "SELECT ".$column."  
				FROM nahao.round r 
				LEFT JOIN nahao.class cl ON cl.round_id=r.id 
				LEFT JOIN nahao.course c ON r.course_id=c.id 
				LEFT JOIN nahao.courseware cw ON cw.id=cl.courseware_id 
				LEFT JOIN nahao.round_teacher_relation rtr ON rtr.round_id=r.id 
				LEFT JOIN nahao.course_type ct ON r.course_type=ct.id 
				LEFT JOIN nahao.subject sub ON r.subject=sub.id 
				".$where.$group.$order;
		$arr_result = $this->db->query($sql)->result_array();
		
        return $arr_result;
	}
	
	/**
	 * 老师轮状态统计
	 **/
    public function round_status_counter($param){
    	$arr_result = array();
		$where = ' WHERE 1 ';
		$where .= $param['teacher_id'] ? ' AND rtr.teacher_id='.$param['teacher_id'] : '';
		$where .= $param['teach_status'] ? ' AND r.teach_status in('.($param['teach_status']==-1 ? 0 : $param['teach_status']).')' : '';
    	$sql = "SELECT count(distinct r.id) total
    			FROM nahao.round r 
    			LEFT JOIN nahao.round_teacher_relation rtr ON rtr.round_id=r.id 
    			".$where;
    	$arr_result = $this->db->query($sql)->result_array();
    	return $arr_result;
    } 
	
	/**
	 * 【题目搜索器】：
	 * pararm : status,class_id,counter
	 */
	public function question_seacher(){
		#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1';
		$where .= $param['status'] ? ' AND qcr.status='.$param['status'] : ' AND qcr.status=1';
		$where .= $param['class_id'] ? ' AND cl.id='.$param['class_id'] : '';
		$order = " ORDER BY q.id ASC";
		$column = $param['counter']==1 ? 'count(q.id) total' :'cl.title class_title,cl.id class_id,q.*';
		#2. 生成sql
        $this->db->query("set names utf8");
        
		$sql = "SELECT ".$column." 
				FROM nahao.question q 
				LEFT JOIN nahao.question_class_relation qcr ON qcr.question_id=q.id 
				LEFT JOIN nahao.class cl ON qcr.class_id=cl.id ".$where.$order;
		$arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
	}
	
	/**
	 * 【练习统计器】：
	 * pararm : is_correct,sequence,class_id,student_id
	 **/
	public function practise_counter(){
		#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1';
		$where .= $param['is_correct'] ? ' AND sq.is_correct='.$param['is_correct'] : '';
		$where .= $param['sequence'] ? ' AND sq.sequence='.$param['sequence'] : '';
		$where .= $param['class_id'] ? ' AND sq.class_id='.$param['class_id'] : '';
		$column = $param['counter']==1 ? 'count(sq.id) total' :'sq.*';
		#2. 生成sql
        $this->db->query("set names utf8");
		$sql = "SELECT count(sq.id) total 
				FROM nahao.sutdent_question sq ".$where;
		$arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
	}
	
	/**
	 * 薪酬结算统计
	 * param: teacher_id,status(0未结算,1已结算),
	 **/
	public function pay_list(){
		#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1';
		$where .= $param['teacher_id'] ? ' AND tcl.teacher_id='.$param['teacher_id'] : '';
		$where .= $param['status'] ? ' AND tcl.status='.$param['status'] : '';
		$where .= $param['start_time'] ? ' AND tcl.pay_time>='.$param['start_time'] : '';
		$where .= $param['end_time'] ? ' AND tcl.end_time<='.$param['end_time'] : '';
		
		#2. 生成sql
        $this->db->query("set names utf8");
		$sql = "SELECT tcl.*
				FROM nahao.teacher_checkout_log tcl ".$where;
		$arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
	}
	
	/**
	 * 学员评价
	 * param:round_id,class_id,
	 **/
	public function student_comment($param){
		#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1';
		$where .= $param['round_id'] ? ' AND cf.round_id='.$param['round_id'] : '';
		$where .= $param['class_id'] ? ' AND cf.class_id='.$param['class_id'] : '';
		$where .= $param['student_id'] ? ' AND cf.student_id='.$param['student_id'] : '';
		$where .= $param['is_show'] ? ' AND cf.is_show='.$param['is_show'] : '';
		$where .= $param['score'] ? ' AND cf.score='.$param['score'] : '';
		$order = " ORDER BY cf.".self::$_orderArr[($param['order'] ? $param['order'] : 4)]." ".self::$_orderType[($param['orderType'] ? $param['orderType'] : 1)];
		$column = $param['counter']==1 ? 'count(cf.id) total' :'cf.*';
		#2. 生成sql
        $this->db->query("set names utf8");
		$sql = "SELECT ".$column." 
				FROM nahao.class_feedback cf ".$where.$order;
		$arr_result = $this->db->query($sql)->result_array();
        return $arr_result;
	} 
	
	/**
     * 获得地区
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
	
    /**
     * 录题管理器
     * param : id,question,answer,options,analysis,class_id,question_id,status,sequence
     * do,delete_class_question,delete_lesson_question
     **/
     public function question_manager($param){
     	if(!$param['do']){exit("缺少操作参数");}
     	$param['question'] = addslashes($param['question']);
     	$param['answer'] = addslashes($param['answer']);
     	$param['options'] = addslashes($param['options']);
     	$param['analysis'] = addslashes($param['analysis']);
     	$this->db->query("set names utf8");
     	switch ($param['do']){
     		case 'add': 
     			$sql = "INSERT INTO nahao.question(question,answer,options,question.type,analysis) 
						VALUES('".$param['question']."','".$param['answer']."','".$param['options']."',".$param['type'].",'".$param['analysis']."')";
     			$id = $this->db->query($sql)->insert_id();
     			if($id){
     				$sql = "REPLACE INTO nahao.question_class_relation(id,class_id,question_id,question_class_relation.status,sequence) 
							VALUES(".$param['id'].",".$param['class_id'].",".$param['question_id'].",".$param['status'].",".$param['sequence'].")";
     				$res = $this->db->query($sql);
     			}
     			break;
     		case 'edit':
     			$sql = "UPDATE nahao.question 
						SET question='".$param['question']."',
							answer='".$param['answer']."',
							options='".$param['options']."',
							question.type=1,
							analysis='".$param['analysis']."' 
						WHERE 1 AND id=".$param['id'];
     			$res = $this->db->query($sql);
     			break;
     		case 'delete':
     			if(!$param['delete_class_question'] || !$param['delete_class_question']){
     				exit('为防止误操作，不可缺少参数');
     			}
     			$sql = "DELETE FROM nahao.question WHERE id=".$param['id'];
     			$ress = $this->db->query($sql);
     			if($ress){
     				//删除题课关系
     				if($param['delete_class_question']){
     					$res = $this->db->query("DELETE FROM nahao.question_class_relation WHERE question_id=".$param['id']);
     				}
     				//删除题课节关系
     				if($param['delete_lesson_question']){
     					$res = $this->db->query("DELETE FROM nahao.question_lesson_relation WHERE question_id=".$param['id']);
     				}
     			}
     	}
     	return $this->db->query($sql);
     }
    
     /**
      * 讲义管理器
      **/ 
     public function courseware_manager(){
     	$this->db->query("set names utf8");
     	switch ($param['do']){
     		case 'add':
     			$sql = "INSERT INTO nahao.courseware(name,create_time,courseware.status) 
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
    	if(!$param['class_id']){
    		exit('为避免误操作，班次id不能为空');
    	}
    	#1. 参数组合
		$arr_result = array();
		$where = ' WHERE 1';
		$where .= $param['class_id'] ? ' AND qcr.class_id='.$param['class_id'] : '';
		$where .= $param['question_id'] ? ' AND qcr.question_id in('.$param['question_id'].')' : '';
		$set = $param['sequence'] > 0 ? ' SET qcr.status=1,qcr.sequence='.$param['sequence'] //出题
			: ($param['sequence'] == -1 ? ' SET qcr.status=0,qcr.sequence=0' : ' SET qcr.status=1,qcr.sequence=1');//取消出题
		#2. 生成sql
        $this->db->query("set names utf8");
		$sql = "UPDATE nahao.question_class_relation qcr ".$set.$where;
        return $this->db->query($sql);
    }
     
    /**
     * 做题
     * param:class_id,student_id,question_id,answer,is_correct,sequence
     **/
    public function reply_question($param){
    	$this->db->query("set names utf8");
    	$sql = "INSERT INTO nahao.sutdent_question(class_id,student_id,question_id,answer,is_correct,sequence) 
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
    	$sql = 'INSERT INTO nahao.teacher_lecture(course,resume,subject,teacher_lecture.status,admin_id,
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
	 * 获取所有课程状态
	 **/
     public function get_course_type(){
     	$this->db->query("set names utf8");
     	$sql = "SELECT * FROM nahao.course_type";
     	return $this->db->query($sql)->result_array();
     }
     
     /**
	 * 获取所有课程状态
	 **/
     public function get_subject(){
     	$this->db->query("set names utf8");
     	$sql = "SELECT * FROM nahao.subject";
     	return $this->db->query($sql)->result_array();
     }
}