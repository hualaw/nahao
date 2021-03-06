<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pay extends NH_User_Controller {
	public $teacher_id;
    function __construct(){
        parent::__construct();
        header("Content-type: text/html; charset=utf-8");
        $this->smarty->assign('site_url','http://'.__HOST__);
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
	public function index(){
		#1.月计算列表
		$param = array(
			'teacher_id' => $this->teacher_id,
		);
		$listArr = $this->teacher_b->pay_list($param);
		#3.页面数据
		$data = array(
			'listArr' => $listArr,
			'active' => 'pay_index',
			'title' => '课酬结算',
			'pay_status_count' => $this->teacher_b->count_pay_status($listArr),
			);
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/teacherPay/index.html');
	}
	
	public function detail(){
		#1.详情列表
		$pay_id = $this->uri->segment(3,0);
		$pay_info = $this->teacher_b->pay_list(array('teacher_id' => $this->teacher_id,'id'=>$pay_id));
		$param = array(
				'teacher_id' => $this->teacher_id,
				'pay_id' => $pay_id,
				'class_ids' => !empty($pay_info[0]['class_ids']) ? $pay_info[0]['class_ids'] : '',
     		);
	    $detail = $this->teacher_b->pay_detail($param);
     	#3.页面数据
		$data = array(
			'detail' => $detail,
			'active' => 'pay_detail',
			'title' => '当月授课详情',
			'pay_info' => $pay_info[0] ? $pay_info[0] : array(),
			);
		$this->smarty->assign('data',$data);
		$this->smarty->display('teacher/teacherPay/pay_detail.html');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */