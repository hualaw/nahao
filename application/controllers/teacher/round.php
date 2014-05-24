<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Round extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->model('business/teacher/business_teacher','teacher_b');
		$this->load->model('model/teacher/model_teacher','teacher_m');
    }
    
	/**
	 * 老师课程列表
	 */
	public function index()
	{
		#1.列表
		$listArr = $this->teacher_b->get_round(array('user_id'=>1));
		$list_data = $this->process_str($listArr);
		
		#2.页面数据
		$data = array(
			'title' => '课程列表',
			'active' => 'round_index',
			'list' => $list_data,
		);
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/round.html');
	}

	/**
	 * 查看章节
	 */
	public function class_list(){
		$round_id = $this->uri->segment(3,0);
		if(!$round_id){exit('<script>alert("该班次不存在");history.go(-1);</script>');}
		$listArr = $this->teacher_b->get_zjList(array('round_id'=>$round_id));
		
		#2.页面数据
		$data = array(
			'title' => '章节列表',
			'active' => 'round_class_list',
		);
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/class_list.html');
	}
	
	/**
	 * 添加试题
	 */
	public function question_add(){
		
	}
	
	/**
	 * 修改试题
	 */
	public function question_edit(){
		
	}
	
	/**
	 * 具体课的答题统计
	 */
	public function answer_count(){
		
	}
	
	/**
	 * 查看评价
	 */
	public function check_comment(){
		
	}
	
	/**
	 * 课数据组合
	 */
	public function process_str($list){
		$listStr = '';
		$class_type = config_item('course_type');#课程类型
		$teach_status = config_item('round_teach_status');#授课状态subject
		$subject = config_item('subject');#科目
		
		$total = 0;
		if($list) foreach ($list as $val){
			if($val['zjArr']) foreach ($val['zjArr'] as $v){
				if($v['son_class']) foreach ($v['son_class'] as $lesson){
					$listStr .= '<tr>
						<td>'.$lesson['id'].'</td>
						<td><a href="/round/class_list/'.$lesson['round_id'].'">'.$lesson['parent_name'].'</a></td>
			            <td>'.$lesson['title'].'</td>
			            <td>'.date('Y-m-d',$lesson['begin_time']).'</td>
			            <td>'.date('H:i',$lesson['begin_time']).'-'.date('H:i',$lesson['end_time']).'</td>
			            <td>'.$val['rate'].' / '.$val['total_class'].'</td>
			            <td>'.$subject[$val['subject']].'</td>
			            <td>'.$class_type[($val['course_type'] ? $val['course_type'] : 0)].'</td>
			            <td>'.$teach_status[($val['teach_status'] ? $val['teach_status'] : 0)].'</td>
			            <td>'.$val['course_score'].'</td>
			            <td><a href="/round/class_list/'.$lesson['round_id'].'">查看班次章节</a></td>
			          </tr>';
				}
			}
			$total += ( $val['total_class'] > 0 ) ? $val['total_class'] : 0;
		}
		
		return array(
			'str' => $listStr,
			'date' => date('Y年m月d日 星期w',time()),
			'total' => $total,
		);
	}
}