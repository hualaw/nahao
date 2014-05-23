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
}