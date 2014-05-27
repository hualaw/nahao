<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        // $this->load->model('business/student/student_index');
    }
    /**
	 * 老师首页
	 */
	public function index(){
	    $this->smarty->display('teacher/teacherHomePage/index.html');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */