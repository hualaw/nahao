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
     * 提交购买0元轮
     */
    public function sub_student_order(){
    	$student_id = $this->input->get('student_id');
		$round_id = $this->input->get('round_id');
		$student_arr = explode(',',$student_id);
		$round_arr = explode(',',$round_id);
		foreach ($student_arr as $val){
			
		}
		self::json_output('购买0元课程成功');
    }
    
    /**
     * 提交预约试听课
     */
    public function sub_student_Audition(){
    	$student_id = $this->input->get('student_id');
		$round_id = $this->input->get('round_id');
		$class_id = $this->input->get('class_id');
		self::json_output('预约试听课成功');
    }
    
    /**
     * ajax读取学生
     */
    public function ajax_search_student(){
    	$email = $this->input->get('email');
    	$phone = $this->input->get('phone');
    	$param = array(
    			'user' => $email,
    			'phone' => $phone,
    		);
    	$userInfo = $this->tools->search_student($param);
    	
    	$data = array(
    			'status' => 'error',
        		'msg' => '操作失败',
        		'data' => $userInfo,
    		);
    	echo json_encode($data);
    	exit;
    }
    
    /**
     * ajax读取轮
     */
    public function ajax_search_round(){
    	$round_name = $this->input->get('round_name');
    	$param = array(
    			'round_name' => $round_name,
    		);
    	$roundInfo = $this->tools->search_round($param);
    	$data = array(
    			'status' => 'ok',
        		'msg' => '操作成功',
        		'data' => $roundInfo,
    		);
    	echo json_encode($data);
    	exit;
    }
}