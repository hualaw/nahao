<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Selfinfo extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        // $this->load->model('business/student/student_index');
    }
	public function index(){
		$this->smarty->display('teacher/teacherSelfinfo/index.html');
	}
	public function openClass(){
		$this->smarty->display('teacher/teacherSelfinfo/openClass.html');
	}
	public function password(){
		$this->smarty->display('teacher/teacherSelfinfo/password.html');
	}
	public function photo(){
		$this->smarty->display('teacher/teacherSelfinfo/photo.html');
	}
	public function success(){
		$this->smarty->display('teacher/teacherSelfinfo/success.html');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */