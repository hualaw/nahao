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
}