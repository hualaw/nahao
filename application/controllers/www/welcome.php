<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends NH_Student_Controller {

	/**
     * @author yanrui@91waijiao.com
	 */
	public function index()
	{
        $this->load->database();
        $arr = $this->db->query('select * from admin')->result_array();
        var_dump($arr);exit;
		echo 'This is index of nahaodev student index !';exit;
		$this->load->view('welcome_message');
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */