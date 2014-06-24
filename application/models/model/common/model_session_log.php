<?php

class Model_Session_Log extends NH_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	//return true or false
	//userdata must have phone_mask or email, user_type, user_id
	public function save_session_log($session_log)
	{
		$arr_insert = array();

        $CI = &get_instance();
		$user_data = array(
			'session_id' => $session_log["session_id"],
			'user_id' => isset($session_log["user_id"]) ? $session_log["user_id"] : '',
			'nickname' => isset($session_log["nickname"]) ? $session_log["nickname"] : '',
			'ip' => ip2long($CI->input->ip_address()),
			'generate_time' => time(),
			'expire_time' => time() + config_item('sess_expiration'),
			'user_type' => isset($session_log["user_type"]) ? $session_log["user_type"] : 0,
			'exit_time' => '',
			);

        /*echo '<pre>';
        print_r($user_data);
        echo '</pre>';*/

		$this->db->insert(TABLE_SESSION_LOG, $user_data);

		$bool_ret = false;
		if($this->db->affected_rows()) $bool_ret=true;
		return $bool_ret;
	}

	//return true or false
	public function update_session_log($info)
	{
		$session_id = isset($info['session_id']) ? $info['session_id'] : '';
		$bool_ret = false;
		if($session_id)
		{
			$bool_ret =  $this->db->update(TABLE_SESSION_LOG, $info, 'session_id='.$session_id);
		}
		return $bool_ret;
	}
}
?>
