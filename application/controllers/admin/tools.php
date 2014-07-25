<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 题目管理
 * Class Round
 */
class Tools extends NH_Admin_Controller {

    private $arr_response = array(
        'status' => 'error',
        'msg' => '操作失败',
    );

    function __construct(){
        parent::__construct();
        $this->load->model('business/admin/tools');
        header('Content-Type:text/html;CHARSET=utf-8');
    }

    /**
     * 课节题目列表
     */
    public function index(){
    	#设置过代理服务器的老师
    	$teacher_arr = $this->tools->get_proxy_teacher();
    	$data = array(
    			'active' => '',
    			'proxys' => config_item('McuAddr'),
    			'proxy_teacher' => !empty($teacher_arr) ? $teacher_arr : array()
    		);
    	$this->smarty->assign('view', 'tools');
    	$this->smarty->assign('data', $data);
        $this->smarty->display('admin/layout.html');
    }
    
    /**
     * 提交随到随买
     */
    public function sub_student_order(){
    	$user_id = $this->input->get('user_id');
		$round_id = $this->input->get('round_id');
		if(empty($user_id) || empty($round_id)){exit('学生id与轮id都不能为空');}
		$param = array(
			'user_id'	=>	$user_id,
			'round_id'	=>	$round_id
			);
		$data = $this->tools->student_order_APP($param);
		echo json_encode($data);
		exit;
    }
    
    /**
     * ajax查找，轮与学生信息
     */
    public function ajax_search_info(){
    	$nickname = $this->input->get('nickname');
    	$round_name = $this->input->get('round_name');
    	$round_id = $this->input->get('round_id');
    	$user_id = $this->input->get('user_id');
    	if(!$nickname && !$round_name && !$round_id && !$user_id){
    		$data = array(
    			'status' => 'error',
        		'msg' => '操作失败,缺少必要轮和昵称参数',
        		'data' => array(
        			'studentInfo' 	=> array(),
        			'roundInfo' 	=> array(),
    			)
    		);
    	}else{
    		$studentInfo = $roundInfo = array();
    		if($nickname){
    			$studentInfo = $this->tools->search_student(array('nickname' => $nickname));
    		}elseif($user_id){
    			$studentInfo = $this->tools->search_student(array('user_id' => $user_id));
    		}
    		if($round_name){
	    		$roundInfo = $this->tools->search_round(array('round_name' => $round_name));
    		}elseif($round_id){
    			$roundInfo = $this->tools->search_round(array('round_id' => $round_id));
    		}
	    	$data = array(
    			'status' => 'ok',
        		'msg' => '操作成功',
        		'data' => array(
        			'studentInfo' 	=> $studentInfo,
        			'roundInfo' 	=> $roundInfo,
    			)
	    	);
    	}
    	echo json_encode($data);
    	exit;
    }
    
    /**
     * 设置老师进教师代理服务器
     */
    public function set_proxy(){
    	$proxy_id = $this->input->post('proxy_id');
    	$teacher_id = $this->input->post('teacher_id');
    	if(empty($proxy_id) || empty($teacher_id)){
    		echo '<script>alert("代理服务器和老师id都不能为空");window.location.href="/tools/";</script>';
    		exit;
    	}
    	$param = array(
    		'proxy_id' => $proxy_id,
    		'teacher_id' => $teacher_id
    		);
    	$res = $this->tools->set_proxy($param);
    	if($res){
    		echo '<script>alert("为老师'.$teacher_id.'设置代理服务器'.$proxy_id.'成功");window.location.href="/tools/";</script>';
    	}else{
    		echo '<script>alert("为老师'.$teacher_id.'设置代理服务器'.$proxy_id.'失败，【或者已经设置过了】");window.location.href="/tools/";</script>';
    	}
    	exit;
    }
    
    /**
     * 取消老师代理服务器
     */
    public function unset_proxy(){
    	$teacher_id = $this->input->get('teacher_id');
    	if(empty($teacher_id)){
    		echo '<script>alert("老师id不能为空");window.location.href="/tools/";</script>';
    		exit;
    	}
    	$param = array(
    		'teacher_id' => $teacher_id
    		);
    	$res = $this->tools->unset_proxy($param);
    	if($res){
    		echo '<script>alert("为老师'.$teacher_id.'取消设置代理服务器成功");window.location.href="/tools/";</script>';
    	}else{
    		echo '<script>alert("为老师'.$teacher_id.'取消代理服务器失败，【或者已经取消过了】");window.location.href="/tools/";</script>';
    	}
    	exit;
    }
    
    /**
     * ajax写，学生购买0元轮【又名批量预约,不走订单】
     */
    public function ajax_buy_free_round(){
    	$nickname = $this->input->post('user_id');
    	$round_name = $this->input->post('round_id');
    	$param = array(
    		'nickname' 		=> $nickname,
    		'round_name' 	=> $round_name,
    	);
    	$res = $this->tools->buy_free_round($param);
    	if($res){
    		$data = array(
    			'status' => 'ok',
        		'msg' => '预约试听课成功',
        	);
    	}else{
    		$data = array(
    			'status' => 'error',
        		'msg' => '预约试听课失败',
        	);
    	}
    	echo json_encode($data);
    	exit;
    }
    
    /**
     * 给用户id发短信
     */
    public function send_msg()
    {
    	$this->load->library('sms');
    	$user_id = $this->input->get('user_id');
    	$round_id = $this->input->get('round_id');
    	$roundInfo = $this->tools->search_round(array('round_id' => $round_id));
    	#手机号，内容
    	$phone = get_pnum_phone_server($user_id);
    	$content = "【那好网】您已经成功报名班次：".$roundInfo[0]['title']."的学习";
    	$this->sms->setPhoneNums($phone);
		$this->sms->setContent($content);
		#发送
		$send_ret = $this->sms->send();
		if($send_ret['error'] != 'Ok'){
			echo 1;
		}else{
			echo 0;
		}
		exit;
    }
}