<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pay extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        // $this->load->model('business/student/student_index');
    }
	public function index(){
		$this->smarty->display('www/teacherPay/index.html');
	}
	public function pay_detail(){
		$this->smarty->display('www/teacherPay/pay_detail.html');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */