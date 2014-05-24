<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends NH_User_Controller {

	public function __construct(){
		/**
		 * 使用：
		 * 1. 读config：   config_item("subject");
		 * 2. 调redis：	  加载model/redis_model.php
		 * 3. 
		 */
        parent::__construct();
        $this->load->model('business/teacher/business_teacher','teacher_b');
        $this->load->model('model/teacher/model_teacher','teacher_m');
    }
    
	/**
	 * 老师端口首页
	 * 当天即将开课提醒
	 */
	public function index()
	{
		#1.今日列表
		$listArr = $this->teacher_b->get_today_round(array('user_id'=>1));
		#2.模块化和页面相关
		$today_list_data = $this->process_str($listArr);
		#3.页面数据
		$data = array(
			'today_list_data' => $today_list_data,
			'active' => 'welcome_index',
			'title' => '今日上课',
			'host' => 'http://'.$_SERVER ['HTTP_HOST'],
		);
		
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/index.html');
	}
	
	/**
	 * 今日开课数据组合
	 */
	public function process_str($list){
		$listStr = '';
		$class_type = config_item('course_type');#课程类型
		$teach_status = config_item('round_teach_status');#授课状态
		$today_total = 0;
		if($list) foreach ($list as $val){
			if($val['zjArr']) foreach ($val['zjArr'] as $v){
				if($v['son_class']) foreach ($v['son_class'] as $lesson){
					$listStr .= '<tr>
			            <td>'.date('Y-m-d H:i',$lesson['begin_time']).'-'.date('Y-m-d H:i',$lesson['end_time']).'</td>
			            <td>'.$lesson['parent_name'].'</td>
			            <td>'.$lesson['title'].'</td>
			            <td>'.$val['rate'].' / '.$val['total_class'].'</td>
			            <td>'.$class_type[($val['course_type'] ? $val['course_type'] : 0)].'</td>
			            <td>'.$teach_status[($val['teach_status'] ? $val['teach_status'] : 0)].'</td>
			            <td>'.$lesson['coursewareName'].'</td>
			            <td><a href="">试题管理</a></td>
			            <td><a href="">进入教室</a></td>
			          </tr>';
				}
			}
			$today_total += ( $val['total_class'] > 0 ) ? $val['total_class'] : 0;
		}
		
		return array(
			'str' => $listStr,
			'date' => date('Y年m月d日 星期w',time()),
			'today_total' => $today_total,
		);
	}
}