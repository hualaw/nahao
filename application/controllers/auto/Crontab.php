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
        $this->load->model('model/auto/model_crontab');
    }

    function test()
    {
    	echo 44;
    }
    /**
     * 到课开始时间前30分钟 将课的状态从“即将上课”改为“可进教室”
     * type=1 where条件为begin_time
     * type=2 where条件为end_time
     */
    private function Class_Change_SoonClass_To_ClassEnterable($time = 0)
    {
    	if($time > 0){
    		$int_time = $time;
    	} else {
    		$int_time = $this->get_time()+1800;
    	}
       
        $type = 1;
        $array_class = $this->business_crontab->get_class_data($int_time,$type);
        if($array_class)
        {
        	foreach ($array_class as $k=>$v)
        	{
        		$this->business_crontab->update_class_status(array('status'=>CLASS_STATUS_ENTER_ROOM),array('id'=>$v['id']));
        	}
        }
    }

    /**
     *轮的状态 每15分钟一个周期
     * @author shangshikai@tizi.com
     */
    public function change_round_status()
    {
        $begin_time = $this->uri->rsegment(3);
        $end_time = $this->uri->rsegment(4);

        $time=time();
        $year=date('Y',$time);
        $mon=date('m',$time);
        $day=date('d',$time);
        $hour=date('H',$time);
        $min=date('i',$time);
        $advance_time=mktime($hour,$min,0,$mon,$day,$year);

        $this->business_crontab->round_change_status($begin_time,$end_time,$advance_time,$time);
    }
    
    /**
     * 到课开始时间 将课的状态从“可进教室”改为“正在上课”
     * type=1 where条件为begin_time
     * type=2 where条件为end_time
     */
    public function Class_Change_ClassEnterable_To_InClass($time = 0)
    {
    	$type = 1;
    	if($time > 0){
    		$int_time = $time;
    	} else {
    		$int_time = $this->get_time();
    	}
    	$array_class = $this->business_crontab->get_class_data($int_time,$type);
    	if($array_class)
    	{
    		foreach ($array_class as $k=>$v)
    		{
    			$this->business_crontab->update_class_status(array('status'=>CLASS_STATUS_CLASSING),array('id'=>$v['id']));
    		}
    	}
    }
    
    
    /**
     * 到结束上课时间 本节课修改为“上完课或者缺课”
     * type=1 where条件为begin_time
     * type=2 where条件为end_time
     */
    private function Class_Change_To_SoonClass_Or_OverClass($time = 0)
    {
    	if($time > 0){
    		$int_time = $time;
    	} else {
    		$int_time = $this->get_time();
    	}
    	$type = 2;
    	$array_class = $this->business_crontab->get_class_data($int_time,$type);
    	if($array_class)
    	{
    		foreach ($array_class as $k=>$v)
    		{
    			#寻找每一节课老师按开始上课按钮的时间
    			$array_return = $this->business_crontab->get_time_by_teacher_press_submit($v['classroom_id']);
    			#如果老师按开始上课按钮的时间-这节课的开始时间 大于等于5分钟，这这节课老师缺席。否则这节课是“上完课”
    			if($array_return['create_time']-$v['begin_time'] >= 5*60)
    			{
    				$this->business_crontab->update_class_status(array('status'=>CLASS_STATUS_MISS_CLASS),array('id'=>$v['id']));
    			} else{
    				$this->business_crontab->update_class_status(array('status'=>CLASS_STATUS_CLASS_OVER),array('id'=>$v['id']));
    			}
    		}
    	}
    }
    
    /**
     * 到结束上课时间 本节课的下一节课的状态修改为“即将上课”
     * type=1 where条件为begin_time
     * type=2 where条件为end_time
     */
    private function Class_Change_Beginning_To_SoonClass($time = 0)
    {
    	if($time > 0){
    		$int_time = $time;
    	} else {
    		$int_time = $this->get_time();
    	}
    	$type = 2;
    	$array_class = $this->business_crontab->get_class_data($int_time,$type);
    	if($array_class)
    	{
    		foreach ($array_class as $k=>$v)
    		{
    			$int_class_id = $this->business_crontab->get_next_class_data($v['id'],$v['round_id']);
    			if(empty($int_class_id))
    			{
    				break;
    			} else {
    				$this->business_crontab->update_class_status(array('status'=>CLASS_STATUS_SOON_CLASS),array('id'=>$int_class_id));
    			}
    		}
    	}
    }
    
    /**
     * 到结束上课时间 将学生课堂表的状态从“初始化”改为“缺席或者进过教室”
     * type=1 where条件为begin_time
     * type=2 where条件为end_time
     */
    private function Student_Class_Change_Beginning_To_Absent($time = 0)
    {
    	if($time > 0){
    		$int_time = $time;
    	} else {
    		$int_time = $this->get_time();
    	}
    	$type = 2;
    	$array_class = $this->business_crontab->get_class_data($int_time,$type);
    	if($array_class)
    	{
    		foreach ($array_class as $k=>$v)
    		{
    			#根据课id在student_class表里面找到买过这节课的学生
    			$array_return = $this->business_crontab->get_buy_class($v['id']);
    			if ($array_return)
    			{
    				foreach ($array_return as $kk=>$vv)
    				{
    					#查找学生在entering_classroom里面是否有进入记录
    					$bool_falg = $this->business_crontab->check_entering_classroom_data($vv['student_id'],$v['classroom_id']);
    					if ($bool_falg)
    					{
    						#将学生课堂表的状态从“初始化”改为“进过教室”
    						$this->business_crontab->update_student_class_status(array('status'=>STUDENT_CLASS_ENTER),
    						array('student_id'=>$vv['student_id'],'class_id'=>$v['id']));
    					} else {
    						#将学生课堂表的状态从“初始化”改为“缺席”
    						$this->business_crontab->update_student_class_status(array('status'=>STUDENT_CLASS_LOST),
    						array('student_id'=>$vv['student_id'],'class_id'=>$v['id']));
    					}
    				}
    			} else {
    				break;
    			}

    		}
    	}
    }
    
    
    /**
     * 到结束上课时间 计算每个课的出席人数
     * type=1 where条件为begin_time
     * type=2 where条件为end_time
     */
    private function Class_Count_Attendance($time = 0)
    {
    	if($time > 0){
    		$int_time = $time;
    	} else {
    		$int_time = $this->get_time();
    	}
    	$type = 2;
    	$array_class = $this->business_crontab->get_class_data($int_time,$type);
    	if($array_class)
    	{
    		foreach ($array_class as $k=>$v)
    		{
    			#获取每个课的出席人数
    			$array_count = $this->business_crontab->get_class_count_attendance($v['classroom_id']);
    			#更新每个课的出席人数
				$this->model_crontab->update_class_attendance(array('attendance'=>$array_count['num']),array('id'=>$v['id']));
    		}
    	}
    }
    
    /**
     * 到结束上课时间 计算每个课上做题的正确率
     * type=1 where条件为begin_time
     * type=2 where条件为end_time
     */
    private function Class_Count_CorrectRate($time = 0)
    {
    	if($time > 0){
    		$int_time = $time;
    	} else {
    		$int_time = $this->get_time();
    	}
    	$type = 2;
    	$array_class = $this->business_crontab->get_class_data($int_time,$type);
    	if($array_class)
    	{
    		foreach ($array_class as $k=>$v)
    		{
    			#获取每个课上做题的正确率
    			$float_count = $this->business_crontab->get_class_count_correctrate($v['id']);
    			#更新每个课上做题的正确率
    			$this->model_crontab->update_class_correct_rate(array('correct_rate'=>$float_count),array('id'=>$v['id']));
    		}
    	}
    }
    
    /**
     * 获取当前时间的正分
     */
    public function get_time()
    {
    	$time = date('Y-m-d H:i',time()).":00";
    	$time = strtotime($time);
    	return $time;
    }
    
    /**
     * 如果出现服务器故障一段时间，当故障修好了马上就有课要上。需要算出当前时间的前一个整分时刻，循环跑一边计划任务
     */
    public function save_fault()
    {
    	$minute = date('i',time());
    	if ($minute >= 0 && $minute<15) 
    	{
    		$minute = 0;
    	} else if($minute >= 15 && $minute<30){
    		$minute = 15;
    	} else if($minute >= 30 && $minute<45){
    		$minute = 30;
    	} else if($minute >= 45 && $minute<=59){
    		$minute = 45;
    	}
    	$time = date('Y-m-d H',time()).':'.$minute.":00";
    	$time = strtotime($time);
    	
    	$this->Class_Change_SoonClass_To_ClassEnterable($time);
    	$this->Class_Change_ClassEnterable_To_InClass($time);
    	$this->Class_Change_To_SoonClass_Or_OverClass($time);
    	$this->Class_Change_Beginning_To_SoonClass($time);
    	$this->Student_Class_Change_Beginning_To_Absent($time);
    	$this->Class_Count_Attendance($time);
    	$this->Class_Count_CorrectRate($time);
    }
}