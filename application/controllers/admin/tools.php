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
    	
    	$data = array(
    			'active' => '',
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
}