<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 15分钟定时统计(0,15,30,45整分开始跑计划任务)
 */
class Crontab extends NH_Controller
{
    /**
     * 加载数据库连接
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('business/auto/business_crontab');
    }

    /**
     * 到课开始时间前30分钟 将课的状态从“即将上课”改为“可进教室”
     * type=1 where条件为begin_time
     * type=2 where条件为end_time
     */
    private function Class_Change_SoonClass_To_ClassEnterable()
    {


    }

    /**
     *轮的状态 每15分钟一个周期
     * @author shangshikai@tizi.com
     */
    public function change_round_status()
    {
        $this->business_crontab->round_change_status();
    }
    
    /**
     * 到课开始时间 将课的状态从“可进教室”改为“正在上课”
     * type=1 where条件为begin_time
     * type=2 where条件为end_time
     */
    private function Class_Change_ClassEnterable_To_InClass()
    {
    	$int_time = time();
    	$type = 1;
    	$array_class = $this->student_crontab->get_class_data($int_time,$type);
    	if($array_class)
    	{
    		foreach ($array_class as $k=>$v)
    		{
    			$this->student_crontab->update_class_status(array('status'=>3),array('id'=>$v['id']));
    		}
    	}
    }
    
    
    /**
     * 到结束上课时间 本节课修改为“上完课或者缺课”
     * type=1 where条件为begin_time
     * type=2 where条件为end_time
     */
    private function Class_Change_To_SoonClass_Or_OverClass()
    {
    	$int_time = time();
    	$type = 2;
    	$array_class = $this->student_crontab->get_class_data($int_time,$type);
    	if($array_class)
    	{
    		foreach ($array_class as $k=>$v)
    		{
    			#寻找每一节课老师按开始上课按钮的时间
    			$array_return = $this->student_crontab->get_time_by_teacher_press_submit($v['classroom_id']);
    			#如果老师按开始上课按钮的时间-这节课的开始时间 大于等于5分钟，这这节课老师缺席。否则这节课是“上完课”
    			if($array_return['create_time']-$v['begin_time'] >= 5*60)
    			{
    				$this->student_crontab->update_class_status(array('status'=>5),array('id'=>$v['id']));
    			} else{
    				$this->student_crontab->update_class_status(array('status'=>4),array('id'=>$v['id']));
    			}
    		}
    	}
    }
    
    /**
     * 到结束上课时间 本节课的下一节课的状态修改为“即将上课”
     * type=1 where条件为begin_time
     * type=2 where条件为end_time
     */
    private function Class_Change_Beginning_To_SoonClass()
    {
    	$int_time = time();
    	$type = 2;
    	$array_class = $this->student_crontab->get_class_data($int_time,$type);
    	if($array_class)
    	{
    		foreach ($array_class as $k=>$v)
    		{
    			$int_class_id = $this->student_crontab->get_next_class_data($v['id'],$v['round_id']);
    			if(empty($int_class_id))
    			{
    				break;
    			} else {
    				$this->student_crontab->update_class_status(array('status'=>1),array('id'=>$int_class_id));
    			}
    		}
    	}
    }
    
    /**
     * 到结束上课时间 将学生课堂表的状态从“初始化”改为“缺席”
     * type=1 where条件为begin_time
     * type=2 where条件为end_time
     */
    private function Student_Class_Change_Beginning_To_Absent()
    {
    	$int_time = time();
    	$type = 2;
    	$array_class = $this->student_crontab->get_class_data($int_time,$type);
    	if($array_class)
    	{
    		foreach ($array_class as $k=>$v)
    		{
    			#根据课id在student_class表里面找到买过这节课的学生
    			$array_user = $this->student_crontab->get_buy_class_user($v['id']);
    			if ($array_user)
    			{
    				foreach ($array_user as $kk=>$vv)
    				{
    					#查找学生在entering_classroom里面是否有进入记录
    					$bool_falg = $this->student_crontab->check_entering_classroom_data($v['id']);
    					if ($bool_falg)
    					{
    						//$this->student_crontab->update_student_class_status($v['id']);
    					} else {
    						//$this->student_crontab->update_student_class_status("");
    					}
    				}
    			} else {
    				break;
    			}

    		}
    	}
    }
    
    
    
}