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

    /**
     * 教师今天即将开课列表
     * [教师id]
     */
    public function get_today_round($param)
    {
        $res = $this->model_teacher->teacher_today_round($param);
        
        if($res) foreach($res as &$val){
        	$res = $this->model_teacher->teacher_round_class(array('round_id'=>$val['round_id']));
        	$val['total_class'] = $res['total'];
        	$val['rate'] = $res['rate'];
        }
        return $res;
    }
    
	
}