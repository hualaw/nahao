<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orderlist extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        // $this->load->model('business/student/student_index');
    }
	public function index(){
		$this->smarty->display('teacher/teacherOrderList/index.html');
	}
	public function order_appraise(){
		$this->smarty->display('teacher/teacherOrderList/order_appraise.html');
	}
	public function order_count(){
		$this->smarty->display('teacher/teacherOrderList/order_count.html');
	}
	public function order_detail(){
		$this->smarty->display('teacher/teacherOrderList/order_detail.html');
	}
	public function order_manage(){
		$this->smarty->display('teacher/teacherOrderList/order_manage.html');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */