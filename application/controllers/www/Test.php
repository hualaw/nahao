<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {


	public function ss_test()
	{
        /*
		var_dump($this->session);
		$session_id = $this->session->userdata('session_id');
		echo "session_id is: ".$session_id."<br>";
        */
        $this->session->sess_update();
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

	public function sms_test()
	{
		$this->load->library('sms');
		$this->sms->setPhoneNums('18600364806');
		$this->sms->setContent('you code is 20140528');
		$ret = $this->sms->send();	
		var_dump($ret);
	}
	
	public function email_test()
	{
		$this->load->library('mail');
		//email_addr, subject, content
		$this->mail->send('liuhua@tizi.com', '那好邮件测试', 'Got it！');
	}

    public function student_subject_add_test()
    {
        $this->load->model('model/student/model_student_subject');
        $this->model_student_subject->add(123, array('123','234'));
    }

    public function student_subject_delete_test()
    {
        $this->load->model('model/student/model_student_subject');
        $this->model_student_subject->delete(123, array('123','234'));
    }

    public function login_test()
    {
        $this->smarty->display('www/login/login.html');
    }

    public function insert_class_log()
    {
        $this->load->model('model/student/model_student_class_log', 'stu_obj');
        $class_id = 1;
        //$this->stu_obj->set_class_id($class_id);

        //PRAISE
        $this->stu_obj->save_action($class_id, 1, CLASS_PRAISE_ACTION);
        $this->stu_obj->save_action($class_id, 2, CLASS_PRAISE_ACTION);
        $this->stu_obj->save_action($class_id, 3, CLASS_PRAISE_ACTION);

        //SLOWER
        $this->stu_obj->save_action($class_id, 1, CLASS_TEACH_SLOWER_ACTION);
        $this->stu_obj->save_action($class_id, 3, CLASS_TEACH_SLOWER_ACTION);

        //FASTER
        $this->stu_obj->save_action($class_id, 2, CLASS_TEACH_FASTER_ACTION);
    }

    public function get_class_log_stat()
    {
        $class_id = 1;
        $this->load->model('model/student/model_student_class_log', 'stu_obj');
        $this->stu_obj->get_action_stat($class_id);
    }

    public function test_class()
    {
        $ch = curl_init("http://classroom.oa.tizi.com/oa/enter?token=0316d2777ad14366bae3b2949001f736");
        //var_dump($ch);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        $output = curl_exec($ch);

        //echo "error:".curl_error($ch)."<br>";
        //echo "errno:".curl_errno($ch)."<br>";
        curl_close($ch);
        echo $output;
    }

    public function jsonp_test()
    {
        $this->smarty->display('www/test.html');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
