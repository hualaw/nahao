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
        $this->load->model('model/teacher/model_teacher','teacher_m');
    }
    
    /**
     * 到课开始时间前30分钟 将课的状态从“即将上课”改为“可进教室”
     * type=1 where条件为begin_time
     * type=2 where条件为end_time
     */
    public function Class_Change_SoonClass_To_ClassEnterable($time = 0)
    {
    	if($time > 0)
    	{
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
        		$bool_flag = $this->business_crontab->update_class_status(array('status'=>CLASS_STATUS_ENTER_ROOM),array('id'=>$v['id']));
        		if ($bool_flag)
        		{
        			echo "Class_Change_SoonClass_To_ClassEnterable方法--课id为：".$v['id']."状态更新为“可进教室”成功".'\r\n';
        		} else {
        			echo "Class_Change_SoonClass_To_ClassEnterable方法--课id为：".$v['id']."状态更新为“可进教室”失败".'\r\n';
        		}
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
        $ex_time=mktime($hour,$min,0,$mon-1,$day,$year);

        $this->business_crontab->round_change_status($begin_time,$end_time,$advance_time,$time,$ex_time);
    }
    
    /**
     * 到课开始时间 将课的状态从“可进教室”改为“正在上课”
     * type=1 where条件为begin_time
     * type=2 where条件为end_time
     */
    public function Class_Change_ClassEnterable_To_InClass($time = 0)
    {
    	$type = 1;
    	if($time > 0)
    	{
    		$int_time = $time;
    	} else {
    		$int_time = '1404287100';//$this->get_time();
    	}
    	$array_class = $this->business_crontab->get_class_data($int_time,$type);
    	if($array_class)
    	{
    		foreach ($array_class as $k=>$v)
    		{
    			$bool_flag = $this->business_crontab->update_class_status(array('status'=>CLASS_STATUS_CLASSING),array('id'=>$v['id']));
    			if ($bool_flag)
    			{
    				echo "Class_Change_ClassEnterable_To_InClass方法--课id为：".$v['id']."状态更新为“正在上课”成功".'\r\n';
    			} else {
    				echo "Class_Change_ClassEnterable_To_InClass方法--课id为：".$v['id']."状态更新为“正在上课”失败".'\r\n';
    			}
    		}
    	}
    }
    
    
    /**
     * 到结束上课时间 本节课修改为“上完课或者缺课”
     * type=1 where条件为begin_time
     * type=2 where条件为end_time
     */
    public function Class_Change_To_SoonClass_Or_OverClass($time = 0)
    {
    	if($time > 0)
    	{
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
    				$bool_nflag = $this->business_crontab->update_class_status(array('status'=>CLASS_STATUS_MISS_CLASS),array('id'=>$v['id']));
    				if ($bool_nflag)
    				{
    					echo "Class_Change_To_SoonClass_Or_OverClass方法--课id为：".$v['id']."状态更新为“缺课”成功".'\r\n';
    				} else {
    					echo "Class_Change_To_SoonClass_Or_OverClass方法--课id为：".$v['id']."状态更新为“缺课”失败".'\r\n';
    				}
    			} else{
    				$bool_mflag = $this->business_crontab->update_class_status(array('status'=>CLASS_STATUS_CLASS_OVER),array('id'=>$v['id']));
    				if ($bool_mflag)
    				{
    					echo "Class_Change_To_SoonClass_Or_OverClass方法--课id为：".$v['id']."状态更新为“上完课”成功".'\r\n';
    				} else {
    					echo "Class_Change_To_SoonClass_Or_OverClass方法--课id为：".$v['id']."状态更新为“上完课”失败".'\r\n';
    				}
    			}
    		}
    	}
    }
    
    /**
     * 到结束上课时间 本节课的下一节课的状态修改为“即将上课”
     * type=1 where条件为begin_time
     * type=2 where条件为end_time
     */
    public function Class_Change_Beginning_To_SoonClass($time = 0)
    {
    	if($time > 0)
    	{
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
    				$bool_flag = $this->business_crontab->update_class_status(array('status'=>CLASS_STATUS_SOON_CLASS),array('id'=>$int_class_id));
    			    if ($bool_flag)
	    			{
	    				echo "Class_Change_Beginning_To_SoonClass方法--课id为：".$v['id']."状态更新为“即将上课”成功".'\r\n';
	    			} else {
	    				echo "Class_Change_Beginning_To_SoonClass方法--课id为：".$v['id']."状态更新为“即将上课”失败".'\r\n';
	    			}
    			}
    		}
    	}
    }
    
    /**
     * 到结束上课时间 将学生课堂表的状态从“初始化”改为“缺席或者进过教室”
     * type=1 where条件为begin_time
     * type=2 where条件为end_time
     */
    public function Student_Class_Change_Beginning_To_Absent($time = 0)
    {
    	if($time > 0)
    	{
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
    						$bool_nflag = $this->business_crontab->update_student_class_status(array('status'=>STUDENT_CLASS_ENTER),
    						array('student_id'=>$vv['student_id'],'class_id'=>$v['id']));
    					    if ($bool_nflag)
		    				{
		    					echo "Student_Class_Change_Beginning_To_Absent方法--用户id为：".$vv['student_id'].",课id为：".$v['id']."状态更新为“进过教室”成功".'\r\n';
		    				} else {
		    					echo "Student_Class_Change_Beginning_To_Absent方法--用户id为：".$vv['student_id'].",课id为：".$v['id']."状态更新为“进过教室”失败".'\r\n';
		    				}
    					} else {
    						#将学生课堂表的状态从“初始化”改为“缺席”
    						$bool_mflag = $this->business_crontab->update_student_class_status(array('status'=>STUDENT_CLASS_LOST),
    						array('student_id'=>$vv['student_id'],'class_id'=>$v['id']));
    					   	if ($bool_mflag)
		    				{
		    					echo "Student_Class_Change_Beginning_To_Absent方法--用户id为：".$vv['student_id'].",课id为：".$v['id']."状态更新为“缺席”成功".'\r\n';
		    				} else {
		    					echo "Student_Class_Change_Beginning_To_Absent方法--用户id为：".$vv['student_id'].",课id为：".$v['id']."状态更新为“缺席”失败".'\r\n';
		    				}
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
    public function Class_Count_Attendance($time = 0)
    {
    	if($time > 0)
    	{
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
				$bool_flag = $this->model_crontab->update_class_attendance(array('attendance'=>$array_count['num']),array('id'=>$v['id']));
    		    if ($bool_flag)
    			{
    				echo "Class_Count_Attendance方法--课id为：".$v['id']."课的出席人数更新为:".$array_count['num']."成功".'\r\n';
    			} else {
    				echo "Class_Count_Attendance方法--课id为：".$v['id']."课的出席人数更新为:".$array_count['num']."失败".'\r\n';
    			}
    		}
    	}
    }
    
    /**
     * 到结束上课时间 计算每个课上做题的正确率
     * type=1 where条件为begin_time
     * type=2 where条件为end_time
     */
    public function Class_Count_CorrectRate($time = 0)
    {
    	if($time > 0)
    	{
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
    			$bool_flag = $this->model_crontab->update_class_correct_rate(array('correct_rate'=>$float_count),array('id'=>$v['id']));
    		    if ($bool_flag)
    			{
    				echo "Class_Count_CorrectRate方法--课id为：".$v['id']."课上做题的正确率更新为:".$float_count."成功".'\r\n';
    			} else {
    				echo "Class_Count_CorrectRate方法--课id为：".$v['id']."课上做题的正确率更新为:".$float_count."失败".'\r\n';
    			}
    		}
    	}
    }
    
    /**
     * 获取当前时间的正分
     */
    protected function get_time()
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
    
    
    /**
     * 课，轮，课程的平均分
     * jason
     * 跑法：
     * 	1. 取到从当前时间前一天到当前时间内有新评论的课id,轮id,课程id
     * 	   array('counter'=>1,'create_time_from'=>14000000,'create_time_to'=>15000000,'counter');
     * 	   $this->teacher_m->student_comment();
     * 	2. 
     * 	   a. 循环取课的评论次数和总分，将平均分写入课
     * 	   b. 循环取轮的评论次数和总分，将
     */
    public function score_counter()
    {	
    	#1. 初始条件
        $start_time = strtotime("-1 day");
        $end_time = time();
        #2. 获取课id,轮id,课程id
        $param = array(
        	'create_time_from'	=> $start_time,
       		'create_time_to'	=> $end_time,
        );
        $list = $this->teacher_m->student_comment($param);
        $classArr = $roundArr = $courseArr = array();
        #3. 求平均值，课，轮，课程三表联合更改
        if($list) foreach ($list as $val){
        	$param =array();
        	#3.1 第一次出现课，不限时取课平均值
        	if(!in_array($val['class_id'],$classArr)){
        		$param = array();
        		$param['class_id'] = $val['class_id'];
        		$param['counter'] = 2;
        		$res = $this->teacher_m->student_comment($param);
        		$class_avg = isset($res[0]['score']) ? $res[0]['score'] : 0;
        		#修改平均分
        		$param = array(
	        		'class_id' 	=> $val['class_id'],
	        		'score' 	=> $class_avg,
	        		);
        		$bool = $this->teacher_m->set_class_score($param);
        		if($bool){
        			echo '修改课'.$val['class_id'].'平均分('.$class_avg.')成功<br>\r\n';
        		}else{
        			echo '修改课'.$val['class_id'].'平均分失败<br>\r\n';
        		}
        		$classArr[] = $val['class_id'];
        	}
        	#3.2 第一次出现轮，不限时取轮平均值
        	if(!in_array($val['round_id'],$roundArr)){
        		$param = array();
        		$param['round_id'] = $val['round_id'];
        		$param['counter'] = 2;
        		$res = $this->teacher_m->student_comment($param);
        		$round_avg = isset($res[0]['score']) ? $res[0]['score'] : 0;
        		#修改平均分
        		$param = array(
	        		'round_id' 	=> $val['round_id'],
	        		'score' 	=> $round_avg,
	        		);
	        	$bool = $this->teacher_m->set_round_score($param);
	        	if($bool){
        			echo '修改轮'.$val['round_id'].'平均分('.$round_avg.')成功<br>\r\n';
        		}else{
        			echo '修改轮'.$val['round_id'].'平均分失败<br>\r\n';
        		}
        		$roundArr[] = $val['round_id'];
        	}
        	#3.3 第一次出现课程，不限时取课程平均值
        	if(!in_array($val['course_id'],$courseArr)){
        		$param = array();
        		$param['course_id'] = $val['course_id'];
        		$param['counter'] = 2;
        		$res = $this->teacher_m->student_comment($param);
        		$course_avg = isset($res[0]['score']) ? $res[0]['score'] : 0;
        		#修改平均分
        		$param = array(
	        		'course_id' 	=> $val['course_id'],
	        		'score' 	=> $course_avg,
	        		);
        		$bool = $this->teacher_m->set_course_score($param);
	        	if($bool){
        			echo '修改课程'.$val['course_id'].'平均分('.$course_avg.')成功<br>\r\n';
        		}else{
        			echo '修改课程'.$val['course_id'].'平均分失败<br>\r\n';
        		}
        		$courseArr[] = $val['course_id'];
        	}
        }
        exit;
    }
    
    /**
     * 学生订单从已取消到已关闭
     * jason
     * 跑法：
     * update
     * 1. 找到生成时间为大于当前时间减7天状态为0,1,4的订单,将状态改为5
     * 2. 找到生成时间为大于当前时间减7天轮id销售状态为4,5,6,将状态改为5
     */
    public function order_status_setter()
    {
        #1. 初始条件
        $start_time = strtotime("-7 day");
        //测试七天前时间  var_dump(date('Y-m-d',$start_time));
        $end_time = time();
        #2.1 按订单状态过期更改订单状态
        $param = array(
        	'statusFrom' 		=> '0,1,4',
        	'statusTo'	 		=> '5',
        	'create_time_from'	=> $start_time,
        	'create_time_to'	=> $end_time,
        	);
        $bool = $this->teacher_m->set_order_status($param);
        if($bool){
        	echo '修改过期（7天）订单状态成功<br>\r\n';
        }
        #2.2 按轮销售状态过期更改订单状态
        $param = array(
        	'sale_status' => '4,5,6',
        	'statusTo'	 		=> '5',
        	'create_time_from'	=> $start_time,
        	'create_time_to'	=> $end_time,
        	);
        $bool = $this->teacher_m->set_order_status($param);
        if($bool){
        	echo '修改过期（七天）轮销售状态的订单状态成功<br>\r\n';
        }
    }
    
    /**
     * 每月结算老师的课酬
     * jason
     * 跑法：
     * 1. 取到从当前时间所属月的上个月1日0点0分0秒到当前时间所属月上个月的最后一天23点59分59秒，所有上过的课记录(含老师id,轮中课时费)
     * 2. 循环处理数据为老师id和课的键值对。
     * 3. 按每节课的轮中课时费统计该老师的总课酬，并修改课的结算状态。生成月结算日志。
     */
    public function teacher_checkout()
    {
        #1. 初始条件
        $start_time = strtotime(date('Y-m-01', strtotime('-1 month')));
        //测试上个月第一天时间  var_dump(date('Y-m-d',$start_time));
        $end_time = strtotime(date('Y-m-t 23:59:59', strtotime('-1 month')));
        $status = '4';//课状态为4
        #2. 上课记录
        $param = array(
        	'start_time' => $start_time,
        	'end_time' => $end_time,
        	'status' => $status,
        	);
        $list = $this->teacher_m->month_class_seacher($param);
        $teacher_Arr = array();
        $hour = array();//总课时
        $gross_income = array();//总课时费
        $tax = array();//税费
		$deduct = array();//额外扣除
		$class_ids = '';//课程id串
        $tax_rate_config = config_item('tax_rate_config');
        if($list){ 
        	#排序
        	foreach ($list as $key => $val){
        		#追加每个老师总课时数
        		$hour[$val['teacher_id']] = '';//当前课时数
        		$hours[$val['teacher_id']] = isset($hours[$val['teacher_id']]) ? $hours[$val['teacher_id']] : '';//总课时数
        		$hours[$val['teacher_id']] += $val['school_hour'];
        		$tax[$val['teacher_id']] = isset($tax[$val['teacher_id']]) ? $tax[$val['teacher_id']] : '';
        		$deduct[$val['teacher_id']] = isset($deduct[$val['teacher_id']]) ? $deduct[$val['teacher_id']] : '';
        		$net_income[$val['teacher_id']] = isset($net_income[$val['teacher_id']]) ? $net_income[$val['teacher_id']] : '';
        		$gross_income[$val['teacher_id']] = isset($gross_income[$val['teacher_id']]) ? $gross_income[$val['teacher_id']] : '';
//        		echo '<br>初始收入：';var_dump($gross_income[$val['teacher_id']]);echo '<br>';
        		$class_ids[$val['teacher_id']] = isset($class_ids[$val['teacher_id']]) ? $class_ids[$val['teacher_id']] : '';
        		$hour[$val['teacher_id']] += $val['school_hour'];
        		$class_ids[$val['teacher_id']] .= ','.$val['id'];
        		$gross_income[$val['teacher_id']] += $hour[$val['teacher_id']]*$val['reward'];
        		$teacher_Arr[$val['teacher_id']][] = $val;
        		$bool = $this->teacher_m->set_class_checkout_status(array('class_id' => $val['id']));
        		if($bool){
        			echo '更改课id'.$val['id'].'的结算状态为可结算成功<br>\r\n';
        		}else{
        			echo '更改课id'.$val['id'].'的结算状态为可结算失败<br>\r\n';
        		}
        	}
        	foreach ($teacher_Arr as $key => $classes){
        		if($gross_income[$key] <= $tax_rate_config[1]['range_from']){
        			//1.不扣税
        			$tax[$key] = 0;
        			$deduct[$key] = 0;
        		}elseif($gross_income[$key] <= $tax_rate_config[2]['range_from']){
        			//2.按公式1扣税 税费=（收入-800）*税率
        			$tax[$key] = ($gross_income[$key]-800)*$tax_rate_config[2]['tax_rate']-$tax_rate_config[2]['quick_deduction'];
        		}else{
        			//3.按公式2扣税 收入*（1-20%）*级距税率-速算扣除数
        			$level_income = $gross_income[$key]*(1-0.2);
        			if($level_income <= $tax_rate_config[2]['range_from']){
        				$tax_rate = $tax_rate_config[1]['tax_rate'];
        				$quick_deduction = $tax_rate_config[1]['quick_deduction'];
        			}elseif($level_income <= $tax_rate_config[3]['range_from']){
        				$tax_rate = $tax_rate_config[2]['tax_rate'];
        				$quick_deduction = $tax_rate_config[2]['quick_deduction'];
        			}elseif($level_income <= $tax_rate_config[4]['range_from']){
        				$tax_rate = $tax_rate_config[3]['tax_rate'];
        				$quick_deduction = $tax_rate_config[3]['quick_deduction'];
        			}else{
        				$tax_rate = $tax_rate_config[4]['tax_rate'];
        				$quick_deduction = $tax_rate_config[4]['quick_deduction'];
        			}
        			$tax[$key] = $level_income*$tax_rate-$quick_deduction;
        		}
        		$deduct[$key] = 0;
				$net_income[$key] = $gross_income[$key]-$tax[$key]-$deduct[$key];
        		#3. 对老师id循环生成月课酬结算日志
        		$param = array(
        			'teacher_id' => $key,
        			'status' => 1,
        			'teach_times' => count($classes),
        			'class_times' => $hours[$key],
        			'gross_income' => $gross_income[$key],
        			'net_income' => $net_income[$key],
        			'deduct' => $deduct[$key],
        			'tax' => $tax[$key],
        			'class_ids' => trim($class_ids[$key],','),
        		);
        		$bool = $this->teacher_m->create_teacher_checkout_log($param);
        		if($bool){
		        	echo '老师id：'.$key.'的'.date('Y-m',time()).'月课酬结算日志生成成功<br>\r\n';
		        }else{
		        	echo '老师id：'.$key.'的'.date('Y-m',time()).'月课酬结算日志生成失败<br>\r\n';
		        }
        	}
        }else{
        	exit("该月没有老师上课记录<br>\r\n");
        }
        
    }
}