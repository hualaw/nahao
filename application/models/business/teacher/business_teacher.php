<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * jason
 */
class Business_Teacher extends NH_Model
{
    function __construct(){
        parent::__construct();
        $this->load->model('model/teacher/model_teacher');
    }

    public function get_pos($actName){
    	$pos = '<div class="nav_crumbs">
				    <a href="/orderList/index/">首页></a>
				    <a href="/orderList/index/">班次列表></a>
				    <a href="/orderList/index/">班次章节></a>
				    <a href="javascript:void(0)" class="nav_detail">练习题管理</a>
				</div>';
    	return $pos;
    }
    
    /**
     * 今日上课数据
     **/
     public function today_class($param){
     	if(empty($param['teacher_id'])){exit('请检查您的登录状态');}
     	$param = array(
     		'teacher_id' => $param['teacher_id'],
     		'begin_time' => strtotime(date("Y-m-d")),
     		'end_time' => strtotime(date("Y-m-d",strtotime("+1 day"))),
     		'parent_id' => -2,
     		'status' => "1,2,3",
     		'order' => 2,
     	);
     	$res = $this->model_teacher->class_seacher($param);
     	if($res) foreach($res as &$val){
     		$total_param_item = array(
     			'teacher_id' => $param['teacher_id'],
     			'parent_id' => -2,
     			'status' => "1,2,3",
     			'round_id' => $val['round_id'],
     			'counter' => 1,
     		);
     		$already_param_item = array(
     			'teacher_id' => $param['teacher_id'],
     			'parent_id' => -2,
     			'status' => "3",
     			'round_id' => $val['round_id'],
     			'counter' => 1,
     		);
     		$total = $this->model_teacher->class_seacher($total_param_item);
     		$val['total'] = $total[0]['total'] ? $total[0]['total'] : 0;
     		$total = $this->model_teacher->class_seacher($already_param_item);
     		$val['already_total'] = $total[0]['total'] ? $total[0]['total'] : 0;
     	}
     	return $res;
     }
     
     /**
      * 所有轮（班次）数据
      **/
     public function round_list($param){
     	if(empty($param['teacher_id'])){exit('请检查您的登录状态');}
     	#数据字典
     	$round_teach_status = config_item('round_teach_status');
     	#搜索
     	$res = $this->model_teacher->round_seacher($param);
     	#统计的情况
     	if(!empty($param['counter'])){return $res[0]['total'];}
     	if($res) foreach($res as $key => &$val){
     		$val['status_name'] = $round_teach_status[$val['teach_status']];
     		#全部
     		$total_param_item = array(
     			'teacher_id' => $param['teacher_id'],
     			'parent_id' => -2,
     			'status' => "0,1,2,3,4,5",
     			'round_id' => $val['id'],
     			'counter' => 1,
     		);
     		#已完成
     		$already_param_item = array(
     			'teacher_id' => $param['teacher_id'],
     			'parent_id' => -2,
     			'status' => "3",
     			'round_id' => $val['id'],
     			'counter' => 1,
     		);
     		#查询进度
     		$total = $this->model_teacher->class_seacher($total_param_item);
     		$val['total'] = $total[0]['total'] ? $total[0]['total'] : 0;
     		$total = $this->model_teacher->class_seacher($already_param_item);
     		$val['already_total'] = $total[0]['total'] ? $total[0]['total'] : 0;
     		#查找当前进度节
     		$val['cur_class'] = $this->round_progress(array('round_id'=>$val['id']));
     	}
     	return $res;
     }
     
     /**
      * 轮循环中获取当前进度节信息
      * 1. 有即将开课显示该课
      * 2. 没有即将开课显示结课的最后一课
      **/ 
     public function round_progress($param){
     	if(empty($param['round_id'])){exit('轮id不能为空');}
     	$input = array(
     		'round_id'=>$param['round_id'],
     		'status'=>'0,1',
     		'parent_id' => -2,//只获取节
     		'order' => 2,//按开始时间
     		'limit' => '1',
     	); 
     	#1. 查看是否有即将开课的课，显示最前一个
     	$class = $this->model_teacher->class_seacher($input);
     	if(!empty($class[0])){
     		$cur_class = $class[0];
     	}else{
     		$input = array(
	     		'round_id'=>$param['round_id'],
	     		'status'=>'3',
	     		'parent_id' => -2,//只获取节
	     		'order' => 2,//按开始时间
	     		'orderType' => 2,
	     		'limit' => '1',
	     	);
	     	#2. 查看是否有结课的课，显示最后一个
     		$class = $this->model_teacher->class_seacher($input);
     	}
     	return !empty($class[0]) ? $class[0] : array();
     }
     
     /**
      * 必须单独存在，老师轮状态统计
      **/
     public function round_status_count($param){
     	$param['teacher_id'] = !empty($param['teacher_id']) ? $param['teacher_id'] : '';
     	if(empty($param['teacher_id'])){exit('请检查您的登录状态');}
     	$res = array();
     	$status_array = config_item('round_teach_status');
     	#全部
     	$param['teach_status'] = join(array_keys($status_array),',');
     	$total = $this->model_teacher->round_status_counter($param);
     	$res['teach_status_all_total'] = array(
     		'total' => $total[0]['total'] ? $total[0]['total'] : 0,
     		'status_name' => '班次数量',
     	);
     	foreach ($status_array as $key => $val){
     		$param['teach_status'] = $key;
     		$total = $this->model_teacher->round_status_counter($param);
     		$res['teach_status_'.$key.'_total'] = array(
     			'total' => $total[0]['total'] ? $total[0]['total'] : 0,
     			'status_name' => $val,
     		);
     	}
//     	#即将上课
//     	$param['teach_status'] = -1;
//     	$total = $this->model_teacher->round_status_counter($param);
//     	$res['teach_status_0_total'] = $total[0]['total'] ? $total[0]['total'] : 0;
     	#等待开课
//     	$param['teach_status'] = 1;
//     	$total = $this->model_teacher->round_status_counter($param);
//     	$res['teach_status_1_total'] = $total[0]['total'] ? $total[0]['total'] : 0;
//     	#授课中
//     	$param['teach_status'] = 2;
//     	$total = $this->model_teacher->round_status_counter($param);
//     	$res['teach_status_2_total'] = $total[0]['total'] ? $total[0]['total'] : 0;
//     	#停课
//     	$param['teach_status'] = 3;
//     	$total = $this->model_teacher->round_status_counter($param);
//     	$res['teach_status_3_total'] = $total[0]['total'] ? $total[0]['total'] : 0;
//     	#结课
//     	$param['teach_status'] = 4;
//     	$total = $this->model_teacher->round_status_counter($param);
//     	$res['teach_status_4_total'] = $total[0]['total'] ? $total[0]['total'] : 0;
//     	#过期
//     	$param['teach_status'] = 5;
//     	$total = $this->model_teacher->round_status_counter($param);
//     	$res['teach_status_5_total'] = $total[0]['total'] ? $total[0]['total'] : 0;
     	
     	return $res;
     } 
     
     /**
      * 统计数据中章节数据状态次数
      **/ 
     public function count_zj_status($data){
     	$count = array(
     		'z_count' => array('name'=>'章','num'=>0),
     		'j_count' => array('name'=>'节','num'=>0),
//     		'j_status_5' => 0,//禁用
//     		'j_status_4' => 0,//缺课
//     		'j_status_3' => 0,//已结课
//     		'j_status_2' => 0,//正在上课
//     		'j_status_1' => 0,//即将上课
//     		'j_status_0' => 0,//初始化
     	);
     	#初始化已定义的状态数目
     	$status = config_item('class_teach_status');
     	foreach($status as $key => $val){
     		$count['j_status_'.$key] = array(
     			'name' => $val,
     			'num'  => 0,
     		);
     	}
     	if(count($data)>0){
     		$count['z_count']['num'] = count($data);
     		foreach ($data as $val){
     			
     			$count['j_count']['num'] += isset($val['jArr']) ? count($val['jArr']) : 0;
     			if(isset($val['jArr'])) foreach ($val['jArr'] as $v){
     				$v['status'] = !empty($v['status']) ? $v['status'] : 0;
     				$count['j_status_'.$v['status']]['num'] += 1;
//     				$count['j_status_5'] += $v['status'] == 5 ? 1 : 0;
//	     			$count['j_status_4'] += $v['status'] == 4 ? 1 : 0;
//	     			$count['j_status_3'] += $v['status'] == 3 ? 1 : 0;
//	     			$count['j_status_2'] += $v['status'] == 2 ? 1 : 0;
//	     			$count['j_status_1'] += $v['status'] == 1 ? 1 : 0;
//	     			$count['j_status_0'] += $v['status'] == 0 ? 1 : 0;
     			}
     		}
     	}
     	return $count;
     }
     
     /**
     * 所有课数据
     **/
     public function class_list($param){
     	if(empty($param['teacher_id'])){exit('请检查您的登录状态');}
     	$class_teach_status = config_item('class_teach_status');
     	$res = $this->model_teacher->class_seacher($param);
     	$zjArr = array();
     	if($res) foreach($res as &$val){
     		$val['status'] = !empty($val['status']) ? $val['status'] : 0;
     		$val['status_name'] = isset($class_teach_status[$val['status']]) ? $class_teach_status[$val['status']] : '没有状态';
     		#全部
     		$total_param_item = $already_param_item = $param; 
     		$total_param_item['status'] = "";
     		$total_param_item['counter'] = 1;
     		$total_param_item['round_id'] = $val['round_id'];
     		#已完成
     		$already_param_item['status'] = "3";
     		$already_param_item['counter'] = 1;
     		$already_param_item['round_id'] = $val['round_id'];
     		#查询进度
     		$total = $this->model_teacher->class_seacher($total_param_item);
     		$val['total'] = $total[0]['total'] ? $total[0]['total'] : 0;
     		$total = $this->model_teacher->class_seacher($already_param_item);
     		$val['already_total'] = $total[0]['total'] ? $total[0]['total'] : 0;     		
     		if($val['parent_id']==0){
     			#如果已经出现了章,章节的排序值相同可能导致先出现节后出现章的问题
     			$zjArr[$val['id']] = isset($zjArr[$val['id']]) ? array_merge($val,$zjArr[$val['id']]) : $val;
     		}else{
     			#统计出勤率
     			$persent = $this->class_attendance(array('class_id'=>$val['id']));;
     			$val['attendance_persent'] = $persent;
     			$zjArr[$val['parent_id']]['jArr'][] = $val;
     		}
     	}
     	return $zjArr;
     }
     
	/**
	* 课堂出勤率统计
	**/
	public function class_attendance($param){
		if(empty($param['class_id'])){exit('缺少课堂参数');}
		$total = $this->model_teacher->class_attendance(array('class_id'=>$param['class_id'],'status'=>'2'));
		$study_num = $total[0]['total'] ? $total[0]['total'] : 0;
		if($study_num){
			$total = $this->model_teacher->class_attendance(array('class_id'=>$val['class_id']));
			$num = $total[0]['total'];
			$persent = (round($study_num/$num,3)*100).'%';
		}else{
			$persent = "0.00%";
		}
		return $persent;
	} 
     
    /**
     * 教师今天即将开课列表
     * [教师id]
     * class课的状态：
     * 0 初始化 1 即将上课 2 正在上课 3 上完课 4 缺课 5 禁用 （不能恢复）
     */
    public function get_today_round($param)
    {
        $res = $this->model_teacher->teacher_today_round($param);
        if($res) foreach($res as &$val){
        	#章节
        	$zjArr = $jArr = array();
        	$rateNum = 0;
        	$rateRes = $this->model_teacher->teacher_round_class(array('round_id'=>$val['round_id']));
        	if($rateRes) foreach ($rateRes as $v){
        		if($v['parent_id']>0){#节，后遍历
        			$v['parent_name'] = $zjArr[$v['parent_id']]['title'];
        			$jArr[$v['id']] = $v;
        			$zjArr[$v['parent_id']]['son_class'][] = $v;
        			$rateNum += ($v['status'] == 3) ? 1 : 0;
        		}else{#章，先遍历
        			$zjArr[$v['id']] = $v;
        		}
        	}
        	$val['total_class'] = count($jArr);//轮的总课数（小节）
        	$val['rate'] = $rateNum;//进度
        	$val['zjArr'] = $zjArr;//章节列表
        }
        return $res;
    }

	/**
	 * 申请开课
	 */
	public function apply_teach($param){
		$res = $this->model_teacher->apply_teach($param);
		return $res;
	}
	
	/**
     * 教师开课列表
     */
    public function get_round($param)
    {
        $res = $this->model_teacher->teacher_today_round($param);
        if($res) foreach($res as &$val){
        	#章节
        	$zjArr = $jArr = array();
        	$rateNum = 0;
        	$rateRes = $this->model_teacher->teacher_round_class(array('round_id'=>$val['round_id']));
        	if($rateRes) foreach ($rateRes as $v){
        		if($v['parent_id']>0){#节，后遍历
        			$v['parent_name'] = $zjArr[$v['parent_id']]['title'];
        			$jArr[$v['id']] = $v;
        			$zjArr[$v['parent_id']]['son_class'][] = $v;
        			$rateNum += ($v['status'] == 3) ? 1 : 0;
        		}else{#章，先遍历
        			$zjArr[$v['id']] = $v;
        		}
        	}
        	$val['total_class'] = count($jArr);//轮的总课数（小节）
        	$val['rate'] = $rateNum;//进度
        	$val['zjArr'] = $zjArr;//章节列表
        }
        return $res;
    }
    
    /**
     * 轮的章节列表
     **/
    public function get_zjList($param){
    	$sortArr = array();
    	$param['adminOrder'] = 2;
		$teach_status = config_item('class_teach_status');#授课状态
    	if(isset($param['round_id'])){
    		$res = $this->model_teacher->teacher_round_class($param);
    		if($res) foreach ($res as $val){
    			if($val['parent_id']>0){
    				$val['teach_status'] = $teach_status[$val['status']];
    				$sortArr[$val['parent_id']]['jList'][] = $val;
    			}else{
    				$sortArr[$val['id']] = $val;
    			}
    		}
    	}
    	unset($res);
   		return $sortArr ? $sortArr : array();
    }
    
    /**
     * 学员课堂评价
     **/
    public function class_comment($param){
    	if(empty($param['class_id'])){exit('缺少必要课堂参数');}
    	$comment = $this->model_teacher->student_comment($param);
    	
    	return $comment;
    }
     
    /**
     * 课堂练习题
     **/
    public function class_question($param){
    	#1. 统计的情况
    	if(empty($param['class_id']) && empty($param['classroom_id'])){exit('缺少必要课堂参数');}
    	$question = $this->model_teacher->question_seacher($param);
    	if(!empty($param['counter'])){return isset($param['counter']) ? $question[0]['total'] : 0;}
    	#2. 列表的情况
    	$sequence_question = array();
    	if($question) foreach($question as &$val){
    		/**					1. 统计该题答对状态与比例					**/
    		#该课该题总作答数
    		$param = array(
    			'class_id' => isset($param['class_id']) ? $param['class_id'] : '',
    			'classroom_id' => isset($param['classroom_id']) ? $param['classroom_id'] : '',
    			'question_id' => $val['id'],
    			'counter' => 2,//统计作答学生数
    		);
	    	$total = $this->model_teacher->practise_counter($param);//选择人数
    		$val['answer_status']['total'] = isset($total[0]['total']) ? $total[0]['total'] : 0;
    		
    		#该题答对人数
    		$param = array(
    			'class_id' => isset($param['class_id']) ? $param['class_id'] : '',
    			'classroom_id' => isset($param['classroom_id']) ? $param['classroom_id'] : '',
    			'question_id' => $val['id'],
    			'is_correct' => 1,
    			'counter' => 2,//统计作答学生数中答对人数
    		);
	    	$total = $this->model_teacher->practise_counter($param);
    		$val['answer_status']['right_total'] = isset($total[0]['total']) ? $total[0]['total'] : 0;
    		$val['answer_status']['right_persent'] = $val['answer_status']['total'] ? 100*(round($val['answer_status']['right_total']/$val['answer_status']['total'],3)).'%' : 0;
    		#开始统计选项比率
    		$val['options'] = json_decode($val['options'],true);
    		$option_total = 0;
    		/**					2. 统计该题各个选项作答状态与比例					**/
    		if($val['options']) foreach($val['options'] as $k=>&$v){
    			#该题作答时【包含该选项】的人总数
	    		$param = array(
	    			'class_id' => isset($param['class_id']) ? $param['class_id'] : '',
	    			'classroom_id' => isset($param['classroom_id']) ? $param['classroom_id'] : '',
	    			'question_id' => $val['id'],
	    			'answer' => $k,
	    			'counter' => 2,//统计作答学生数中答对人数
	    		);
	    		$total = $this->model_teacher->practise_counter($param);
    			$choose = isset($total[0]['total']) ? $total[0]['total'] : 0;
    			$option_total += $choose;//总选项包含数
    			$v = array(
    				'value' => urldecode($v),
    				'total' => $choose,
    				'is_correct' => (strpos('-'.$val['answer'],$k) ? 1 : 0),
    				//注意此比率表示多少人的选择包含该选项，总比率不一定为100%，若要100%请使用方案2代码
//    				'persent' => $val['answer_status']['right_total'] ? (100*(round($choose/$val['answer_status']['right_total'],3))).'%' : "0%",
    				'persent' => $val['answer_status']['total'] ? (100*(round($choose/$val['answer_status']['total'],3))).'%' : "0%",
				);
    		}
    		/**
    		 * 此乃方案2
    		 **/
    		/*
    		 foreach($val['options'] as $kk=>&$vv){
    			$vv['persent'] = (100*(round($choose/$option_total,3))).'%';
    		}
    		*/
			$sequence_question[$val['sequence']][] = $val;
    	}
    	return isset($param['sort_sequence']) ? $sequence_question : $question;
    }
     
    /**
     * 统计课的答题用户数，可按批次查看
     **/
     public function answer_user_num($param){
     	if(empty($param['class_id']) && empty($param['classroom_id'])){exit('缺少必要课堂参数');}
     	$num = $this->model_teacher->practise_counter($param);
     	return isset($num[0]['total']) ? $num[0]['total'] : 0;
     }
    
     /**
      * 获取课堂出题批次
      **/
     public function get_sequence($param){
     	if(empty($param['class_id']) && empty($param['classroom_id'])){exit('缺少必要课堂参数');}
     	$num = $this->model_teacher->get_sequence($param);
     	return isset($num[0]['total']) ? $num[0]['total'] : 0;
     } 
     
     /**
      * 教师课堂出题
      * param = ['question_id','sequence']
      * 注意question_id 格式逗号分开：12,23,34
      **/
     public function teacher_publish_question($param){
     	$param['sequence'] = !empty($param['sequence']) ? $param['sequence'] : 1;
     	$param['question_id'] = trim($param['question_id'],',');
     	if(!$param['question_id']){exit('缺少必要出题题目id');}
     	return $this->model_teacher->set_question($param);
     } 
     
    /**
     * 全国省市地区
     **/ 
    public function get_area($param){
    	if(!empty($param['level']) || !empty($param['parentid'])){
    		$areaArr = $this->model_teacher->get_area($param);
    	}
    	return $areaArr;
    }
    
    /**
     * 获取课程类型
     **/ 
    public function get_course_type(){
    	return $this->model_teacher->get_course_type();
    }
    
    /**
     * 获取学科列表
     **/ 
    public function get_subject(){
    	return $this->model_teacher->get_subject();
    }
    
    /**
     * 教师课酬结算列表
     **/
    public function pay_list($param){
    	if(empty($param['teacher_id'])){exit('请检查您的登录状态');}
    	$list = $this->model_teacher->pay_list($param);
    	return $list;
    }
    
    /**
     * 教师已/未结算课时总收入
     **/
    public function count_pay_status($list){
    	$count = array(
    		'already' => 0,
    		'none' => 0,
    	);
    	if($list) foreach ($list as $val){
    		if($val['status']==1){
    			$count['already'] += $val['net_income'];
    		}elseif($val['status']==0){
    			$count['none'] += $val['net_income'];
    		}
    	}
    	return $count;
    }
    
    /**
     * 月结算详情
     **/
    public function pay_detail($param){
    	if(empty($param['teacher_id'])){exit('请检查您的登录状态');}
    	if(empty($param['pay_id'])){exit('缺少必要参数');}
    	$detail = array();
		$pay_info = $this->model_teacher->pay_list($param);
		$checkout_status = config_item('class_checkout_status');
		if(isset($pay_info[0])){
			#查询月上课详情
			$param = array(
				'teacher_id' => $param['teacher_id'],
//				'begin_time' => strtotime(date('Y-m',$pay_info[0]['create_time'])),
//				'end_time' => $pay_info[0]['create_time'],
				'parent_id' => -2,
//				'status' => 3,
				'id' => $param['class_ids'],
			);
			$detail = $this->model_teacher->class_seacher($param);
			if($detail) foreach ($detail as &$val){
				$val['checkout_status_name'] = isset($val['checkout_status']) ? $checkout_status[$val['checkout_status']] : '未知';
			}
		}
		return $detail;
    }
     
    /**
     * 分页
     **/ 
    public function load_pageBar($param){
    	$param['curPage'] = !empty($param['curPage']) ? $param['curPage'] : 1;
    	$param['total'] = !empty($param['total']) ? $param['total'] : 0;
    	$param['num'] = !empty($param['num']) ? $param['num'] : 10;
    	$param['pages'] = ceil($param['total']/10);
    	$pageBar = '<div class="pagination fr"><ul>';
    	$pageBar .= '<li><a onclick="ajaxPage(1);return false;" href="javascript:void(0);">首页</a></li>';
    	for ($i=1;$i<=$param['pages'];$i++){
    		$li = '';
    		if($i==($param['curPage']-2) || $i==($param['curPage']-1) || $i==($param['curPage']+2) || $i==($param['curPage']+1)){
    			$li = '<li><a onclick="ajaxData('.$i.');return false;" href="javascript:void(0);">'.$i.'</a></li>';
    		}elseif($i==$param['curPage']){
    			$li = '<li class="active"><a href="javascript:void(0);">'.$i.'</a></li>';
    		}
    		$pageBar .=$li;
    	}
    	$pageBar .= '<li><a onclick="ajaxData('.$param['pages'].');return false;" href="javascript:void(0);">尾页['.$param['pages'].']</a></li>';
    	$pageBar .= '</ul></div>';
    	return $pageBar;
    }
    
    /**
	 * 教室学生动作统计
	 **/ 
    public function class_student_action($param){
    	if(empty($param['class_id']) && empty($param['classroom_id'])){exit('缺少必要课参数');}
    	$count = $this->model_teacher->count_classroom_action($param);
    	$result = array(
    			'please_total_count' => 0,
    			'faster_total_count' => 0,
    			'slower_total_count' => 0,
    		);
    	if($count) foreach ($count as $val){
    		if($val['action']==1){
    			$result['please_total_count'] = $val['total'];
    		}elseif($val['action']==2){
    			$result['faster_total_count'] = $val['total'];
    		}elseif($val['action']==3){
    			$result['slower_total_count'] = $val['total'];
    		}
    	}
    	return $result;
    }
    
    /**
	 * 教室页面，生成老师查看统计html
	 **/ 
    public function build_question_count_html($data,$cur_sequence,$answer_user_num){
    	$html = $html_head = $total_html = '';
    	$cur_sequence = !empty($cur_sequence) ? $cur_sequence : 1;
    	$persent = '';
    	$num = 0;//总题数
    	if(count($data) > 0) foreach ($data as $sequence => $list){
    		//1. 统计头
			$head_class = $sequence==$cur_sequence ? 'redBtn' : 'countBtn';
			$html_head .= '<a href="#" class="cbutton '.$head_class.'" rel='.$sequence.'>第'.$sequence.' 次答题统计</a>';
			//2.统计内容
			$style =  $sequence==$cur_sequence ? 'style="display:block"' : 'style="display:none"';
    		if($list) foreach ($list as $key => $val){
    			$html .= '<li class="CitemList sequence-'.$sequence.'" '.$style.'>
						<div>'.($key+1).'.'.$val['question'].'</div>
						<div class="ansList">';
    			$i = 1;
    			$option_html = $count_html = '';
    			foreach ($val['options'] as $k => $v){
    				if($v['value']){
	    				$class = $i ==1 ? 'firstList' : '';
	    				$rightclass = $v['is_correct'] == 1 ? 'rightSpan' : '';
	    				$option_html .= '<span><em>'.$k.'.</em>'.$v['value'].'</span>';
	    				$count_html .= '<li class="fl '.$class.'">
									<span class="fl listHead '.$rightclass.'">'.$k.'</span>
									<span class="fl">'.$v['total'].'</span>
									<span class="fl">'.$v['persent'].'</span>
								</li>';
	    				$i++;
    				}
    			}
    			$html .= $option_html;
				$html .= '</div>
						<div class="rightAns">
							<span class="pName">答案：</span>
							<strong>'.$val['answer'].'</strong>
						</div>
						<div class="cf">
							<span class="pName fl">解析：</span>
							<p class="fl">'.$val['analysis'].'</p>
						</div>
						<ol class="ansTable cf">';
				$html .= $count_html;
				$html .= '
						</ol>
					</li>';
				//对题百分比总和
				$persent += str_replace('%','',$val['answer_status']['right_persent']);
				//总题数
				$num++;
	    	}
	    	
    	}
    	$average = round($persent/$num,3).'%';
    	$total_html = '<span>本次练习共<em class="redText ">'.$answer_user_num.'</em>人作答</span>
				<span>平均正确率<em class="redText">'.$average.'</em></span>';
    	return array('total_html' => $total_html, 'html_head' => $html_head, 'html' => $html);
    }
    
}