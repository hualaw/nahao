<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends NH_Student_Controller {

	/**
     * @author yanrui@91waijiao.com
	 */

	public function index()
	{
//        $this->load->database();
//        $arr = $this->db->query('select * from admin')->result_array();
//        var_dump($arr);exit;

        $this->load->model('business/student/Business_Student','student');
        $arr_return = $this->student->get_student();
        var_dump($arr_return);exit;
		echo 'This is index of nahaodev student index !';exit;
		$this->load->view('welcome_message');
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */