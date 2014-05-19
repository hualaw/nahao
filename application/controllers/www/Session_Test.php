<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Session_Test extends CI_Controller {


	public function test()
	{
		$session_id = $this->session->userdata('session_id');
		echo "session_id is: ".$session_id."<br>";
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
