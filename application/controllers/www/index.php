<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends NH_Student_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/student/student_index');
    }

    /**
     * 首页获取轮的列表信息
     */
	public function index()
	{
	    header('content-type: text/html; charset=utf-8');
        $data = $this->student_index->get_round_list();
        var_dump($data);die;
        //$this->load->view('www/signin');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */