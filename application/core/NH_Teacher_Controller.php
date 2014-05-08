<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 12-6-4
 * Time: 下午3:49
 * To change this template use File | Settings | File Templates.
 */
class NH_Teacher_Controller extends NH_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->isLogin(USER_TYPE_TEACHER);
        $timezone = array('localtimezone'=>get_cookie('localtimezone'));
        $this->load->vars($timezone);
        $this->user && $this->load->vars('user', $this->user);
        if ($this->user) { // 如果是登录了，记录活动时间
        	$this->load->model('Model_Action_Time', 'action_time');
            $this->action_time->set_user_action_log($this->user['id'], USER_TYPE_TEACHER);
        }
        if($this->router->fetch_class() != 'passport' && !$this->user)
        {
            redirect('/passport');
        }
    }
}
