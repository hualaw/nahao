<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {


	public function ss_test()
	{
		var_dump($this->session);
		$session_id = $this->session->userdata('session_id');
		echo "session_id is: ".$session_id."<br>";
	}

	public function redis_test()
	{
		//load redis model
		$this->load->model('model/common/redis_model', 'redis');

		//choose redis database
		$this->redis->connect('session');

		//store the key-value pair
		$this->cache->save('cache_test_key', '234');

		echo $this->cache->get('cache_test_key');
	}

	public function log_test()
	{
		log_message('error', 'error_message');
		log_message('info', 'info_message');
		log_message('debug', 'debug_message');
		log_message('error_nahao', 'error_nahao_message');
		log_message('info_nahao', 'info_nahao_message');
		log_message('debug_nahao', 'debug_nahao_message');
	}

	public function config_test()
	{
		//$this->CI->config('config');
		//$this->CI->config('config');
		var_dump(config_item('sess_expiration'));
	}

	public function smarty_test()
	{
		$username = 'erichuahua';	
		$password = '123456';
		$this->smarty->assign('username', $username);
		$this->smarty->assign('password', $password);
		$this->smarty->display('www/test.html');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
