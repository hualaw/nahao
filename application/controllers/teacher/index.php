<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends NH_User_Controller {

	public function __construct(){
		/**
		 * 使用：
		 * 1. 读config：   config_item("subject");
		 * 2. 调redis：	  加载model/redis_model.php
		 * 3. 
		 */
        parent::__construct();
        header("Content-type: text/html; charset=utf-8");
        $this->load->model('business/teacher/business_teacher','teacher_b');
        $this->load->model('model/teacher/model_teacher','teacher_m');
        if(!$this->is_login)
        {
            redirect(student_url().'login');
        }
        if(!($this->session->userdata('user_type')==1))
        {
        	exit('<script>alert("您还不是那好课堂的老师！");window.location.href="'.student_url().'";</script>');
        }
        $this->teacher_id = $this->session->userdata('user_id');
    }
    
	/**
	 * 老师端口首页
	 * 当天即将开课提醒
	 */
	public function index()
	{
		$page = $this->uri->segment(3,1);
		#1.分页
     	$this->load->library('pagination');
        $config = config_item('page_teacher');
        $config['base_url'] = '/index/index';
        $config['use_page_numbers'] = TRUE;
        $param = array(
     			'teacher_id' 	=> $this->teacher_id,
     			'begin_time' 	=> strtotime(date("Y-m-d")),
	     		'end_time' 		=> strtotime(date("Y-m-d",strtotime("+1 day"))),
	     		'sale_status'	=> '2,3,4,5,6',//排除过审核的
	     		'parent_id' 	=> -2,
     			'counter' 		=> 1,
     		);
     	
     	$int_count = $this->teacher_b->class_list($param);
        $config['total_rows'] = $int_count;
        $config['per_page'] = 10;
        $this->pagination->initialize($config);
        $pageBar = $this->pagination->create_links();
        #2.今日列表
		$listArr = $this->teacher_b->class_list(array(
				'teacher_id'	=> $this->teacher_id,
				'begin_time' 	=> strtotime(date("Y-m-d")),
	     		'end_time' 		=> strtotime(date("Y-m-d",strtotime("+1 day"))),
	     		'sale_status'	=> '2,3,4,5,6',//排除过审核的
	     		'parent_id' 	=> -2,
	     		'order' 		=> 2,
	     		'limit'			=> (($page-1)*$config['per_page']).','.$config['per_page'],
	     		'no_sort'		=> 1,//特殊处理，不排序章节
			));
		$weekarray = array("日","一","二","三","四","五","六");
		#3.页面数据
		$data = array(
			'listArr' 			=> $listArr,
			'active' 			=> 'index_index',
			'title' 			=> '今日上课',
			'host' 				=> 'http://'.$_SERVER ['HTTP_HOST'],
			'today_total' 		=> $int_count,
			'date' 				=> date('Y年m月d日',time()),
			'week' 				=> $weekarray[date('w')],
			'enter_classroom'	=> array(CLASS_STATUS_ENTER_ROOM,CLASS_STATUS_CLASSING),
			'pageBar'			=> $pageBar,
		);
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/teacherHomePage/index.html');
	}
	
//	/**
//	 * 我要开课
//	 */
//	public function apply_teach()
//	{
//		$param['stage'] = config_item('stage');
//		$param['teacher_title'] = config_item('teacher_title');
//		$param['teacher_type'] = config_item('teacher_type');
//		$param['subject'] = $this->subject->get_subjects();
//		$data = array(
//			'data' => $param,
//		);
//		$this->smarty->assign('data',$data);
//	    $this->smarty->display('www/studentStartClass/writeInfo.html');
//	}
//	
//	/**
//	 * 我要开课,提交
//	 */
//	public function apply_teach_doAdd()
//	{
//		
//		$param['question'] = $this->input->post('question');
//		
//	}
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