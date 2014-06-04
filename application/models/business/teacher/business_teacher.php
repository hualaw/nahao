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
    	$pos = '<div class="row col-md-10" style="margin-left: 1px;">
    				<ol class="breadcrumb" style="margin-bottom: 5px;">
        				<li><a href="'.$_SERVER['host'].'">首页</a></li>
        				<li class="active">'.$actName.'</li>
        			</ol>
        		</div>';
    	return $pos;
    }
    
    /**
     * 今日上课数据
     **/
     public function today_class($param){
     	if(!$param['teacher_id']){exit('请检查您的登录状态');}
     	$course_type = config_item('course_type');
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
     		$val['course_type_name'] = $course_type[$val['course_type']];
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
     	if(!$param['teacher_id']){exit('请检查您的登录状态');}
     	#数据字典
     	$round_teach_status = config_item('round_teach_status');
     	#搜索
     	$res = $this->model_teacher->round_seacher($param);
     	if($res) foreach($res as &$val){
     		$val['status_name'] = $round_teach_status[$val['teach_status']];
     		#全部
     		$total_param_item = array(
     			'teacher_id' => $param['teacher_id'],
     			'parent_id' => -2,
     			'status' => "1,2,3",
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
     	}
     	return $res;
     }
     
     /**
      * 必须单独存在，老师轮状态统计
      **/
     public function round_status_count($param){
     	$param['teacher_id'] = isset($param['teacher_id']) ? $param['teacher_id'] : '';
     	if(!$param['teacher_id']){exit('请检查您的登录状态');}
     	$res = array();
     	#即将上课
     	$param['teach_status'] = -1;
     	$total = $this->model_teacher->round_status_counter($param);
     	$res['teach_status_0_total'] = $total[0]['total'] ? $total[0]['total'] : 0;
     	#授课中
     	$param['teach_status'] = 1;
     	$total = $this->model_teacher->round_status_counter($param);
     	$res['teach_status_1_total'] = $total[0]['total'] ? $total[0]['total'] : 0;
     	#停课
     	$param['teach_status'] = 2;
     	$total = $this->model_teacher->round_status_counter($param);
     	$res['teach_status_2_total'] = $total[0]['total'] ? $total[0]['total'] : 0;
     	#结课
     	$param['teach_status'] = 3;
     	$total = $this->model_teacher->round_status_counter($param);
     	$res['teach_status_3_total'] = $total[0]['total'] ? $total[0]['total'] : 0;
     	#过期
     	$param['teach_status'] = 4;
     	$total = $this->model_teacher->round_status_counter($param);
     	$res['teach_status_4_total'] = $total[0]['total'] ? $total[0]['total'] : 0;
     	#全部
     	$param['teach_status'] = "0,1,2,3,4";
     	$total = $this->model_teacher->round_status_counter($param);
     	$res['teach_status_all_total'] = $total[0]['total'] ? $total[0]['total'] : 0;
     	return $res;
     } 
     
     /**
      * 统计数据中章节数据状态次数
      **/ 
     public function count_zj_status($data){
     	$count = array(
     		'z_count' => 0,
     		'j_count' => 0,
     		'j_status_5' => 0,//禁用
     		'j_status_4' => 0,//缺课
     		'j_status_3' => 0,//已结课
     		'j_status_2' => 0,//正在上课
     		'j_status_1' => 0,//即将上课
     		'j_status_0' => 0,//初始化
     	);
     	if(count($data)>0){
     		$count['z_count'] = count($data);
     		foreach ($data as $val){
     			$count['j_count'] += count($val['jArr']);
     			foreach ($val['jArr'] as $v){
     				$count['j_status_5'] += $val['status'] == 5 ? 1 : 0;
	     			$count['j_status_4'] += $val['status'] == 4 ? 1 : 0;
	     			$count['j_status_3'] += $val['status'] == 3 ? 1 : 0;
	     			$count['j_status_2'] += $val['status'] == 2 ? 1 : 0;
	     			$count['j_status_1'] += $val['status'] == 1 ? 1 : 0;
	     			$count['j_status_0'] += $val['status'] == 0 ? 1 : 0;
     			}
     		}
     	}
     	return $count;
     }
     
     /**
     * 所有课数据
     **/
     public function class_list($param){
     	if(!$param['teacher_id']){exit('请检查您的登录状态');}
     	$round_teach_status = config_item('round_teach_status');
     	$res = $this->model_teacher->class_seacher($param);
     	$zjArr = array();
     	if($res) foreach($res as &$val){
     		$val['status_name'] = $round_teach_status[$val['status']];
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
     			$zjArr[$val['id']] = $val;
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
		if(!$param['class_id']){exit('缺少课堂参数');}
		$total = $this->model_teacher->class_attendance(array('class_id'=>$val['id'],'status'=>'2'));
		$study_num = $total[0]['total'] ? $total[0]['total'] : 0;
		if($study_num){
			$total = $this->model_teacher->class_attendance(array('class_id'=>$val['id']));
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
	public static function apply_teach($param){
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
    	$param['order'] = 2;
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
     * 全国省市地区
     **/ 
    public function get_area($param){
    	if($param['level'] || $param['parentid']){
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
    	if(!$param['teacher_id']){exit('请检查您的登录状态');}
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
    	if(!$param['teacher_id']){exit('请检查您的登录状态');}
    	if(!$param['pay_id']){exit('缺少必要参数');}
    	$detail = array();
		$pay_info = $this->model_teacher->pay_list($param);
		if(isset($pay_info[0])){
			#查询月上课详情
			$param = array(
				'teacher_id' => $param['teacher_id'],
				'begin_time' => strtotime(date('Y-m',$pay_info[0]['create_time'])),
				'end_time' => $pay_info[0]['create_time'],
				'parent_id' => -2,
				'status' => 3,
			);
			$detail = $this->model_teacher->class_seacher($param);
		}
		return $detail;
    }
     
    /**
     * 分页
     **/ 
    public function load_pageBar($param){
    	$param['page'] = isset($param['page']) ? $param['page'] : 1;
    	$param['total'] = isset($param['total']) ? $param['total'] : 0;
    	$param['num'] = isset($param['num']) ? $param['num'] : 10;
    	$param['pages'] = ceil($param['total']/10);
    	$pageBar = '<div class="pagination fr"><ul>';
    	$pageBar .= '<li><a onclick="ajaxPage(1);return false;" href="javascript:void(0);">首页</a></li>';
    	for ($i=1;$i<=$config['pages'];$i++){
    		$li = '';
    		if($i==($config['curPage']-2) || $i==($config['curPage']-1) || $i==($config['curPage']+2) || $i==($config['curPage']+1)){
    			$li = '<li><a onclick="ajaxData('.$i.');return false;" href="javascript:void(0);">'.$i.'</a></li>';
    		}elseif($i==$config['curPage']){
    			$li = '<li class="active"><a href="javascript:void(0);">'.$i.'</a></li>';
    		}
    		$pageBar .=$li;
    	}
    	$pageBar .= '<li><a onclick="ajaxData('.$config['pages'].');return false;" href="javascript:void(0);">尾页['.$config['pages'].']</a></li>';
    	$pageBar .= '</ul></div>';
    	return $pageBar;
    }
}