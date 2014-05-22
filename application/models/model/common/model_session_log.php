<?php

class Model_Session_Log extends NH_Model
{
	public fnction __construct()
	{
		parent::__construct();
	}

	//return true or false
	//userdata must have phone_mask or email, user_type
	private function save_session_log($user_id)
	{
		$arr_insert = array();
		$userdata = $this->session->userdata();

		$nickname = '';
		if(isset($userdata['phone_mask'])) $nickname = $userdata['phone_mask'];
		else if(isset($userdata['email'])) $nickname = $userdata['email'];

		$user_data = array(
			'session_id' => $this->session->userdata("session_id"),
			'user_id' => $user_id,
			'nickname' => $nickname,
			'ip' => ip2long($this->CI->input->ip_address()),
			'generate_time' => time(),
			'expire_time' => time() + config_item('sess_expiration'),
			'user_type' => $userdata['user_type'],
			'exit_time' => '',
			)

		$this->db->insert(SESSION, $user_data);

		$bool_ret = false;
		if($this->db->affected_rows()) $bool_ret=true;
		return $bool_ret;
	}

	//return true or false
	private function update_session_log($info)
	{
		$session_id = isset($info['session_id']) ? $info['session_id'] : '';
		$bool_ret = false;
		if($session_id)
		{
			$bool_ret =  $this->db->update(SESSION, $info, 'session_id='.$seesion_id);
		}
		reutrn $bool_ret;
	}
}
?>