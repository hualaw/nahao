<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 那好Controller超级父类
 * @author yanrui@91waijiao.com
 */
class NH_Controller extends CI_Controller
{
    /**
     * 保存登录后的用户信息
     * @var array $userinfo
     * @author yanrui@tizi.com
     */
    public $userinfo = array();

    /**
     * 保存当前控制器和方法名
     * @var array $current
     * @author yanrui@91waijiao.com
     */
    public $current = array();
    public $is_login = false;

    function __construct()
    {
        parent::__construct();
        $this->is_login = $this->check_login();
        $this->load_smarty();
        $this->current['controller'] = $this->uri->rsegment(1);
        $this->current['action'] = $this->uri->rsegment(2);
        $this->load->vars($this->current);
        $this->assign_nickname();
    }

    protected function load_smarty()
    {
        $this->smarty->assign('site_url', site_url());
        $this->smarty->assign('static_url', static_host_url());
        $this->smarty->assign('teacher_url', teacher_url());
        $this->smarty->assign('admin_url', admin_url());

        $static_version = config_item('static_version');
        $this->smarty->assign('static_version', $static_version);
        $this->smarty->assign('is_login', $this->is_login);
        $this->smarty->assign('userdata', $this->session->all_userdata());
    }


    /**
     * 当前http请求是否为post
     * @return bool
     * @author yanrui@91waijiao.com
     */
    protected function is_post()
    {
        return $this->input->server('REQUEST_METHOD') === 'POST';
    }

    /**
     * 当前http请求是否为ajax
     * @return bool
     * @author yanrui@tizi.com
     */
    protected function is_ajax()
    {
        return $this->input->is_ajax_request();
    }

    /**
     * @return bool
     * @author yanrui@91waijiao.com
     */
    protected function check_login()
    {
        $bool_return = false;

        //$this->session->sess_read();
        log_message('debug_nahao', "In check_login(), ".print_r($this->session->all_userdata(),1));
        if($this->session->userdata('user_id') > 0)
            $bool_return = true;

        return $bool_return;
    }


    protected function assign_nickname()
    {
        log_message("debug_nahao", "IN ".__FUNCTION__." funtion, out of if()");
        if($this->is_login)
        {
            log_message("debug_nahao", "IN ".__FUNCTION__." funtion, in if()");
            $show_nickname = $this->session->userdata('nickname');
            $show_nickname_len = strlen($show_nickname);
            if($show_nickname_len > 3*MAX_NICKNAME_LEN)
                $show_nickname = substr($show_nickname, 0 , 3*MAX_NICKNAME_LEN)."...";
            $this->smarty->assign('nickname', $show_nickname);
        }
    }
    /**
     * 以json格式输出
     * @param array $arr_data
     * @author yanrui@91waijiao.com
     */
    protected static function json_output($arr_data)
    {
        $str_json = '';
        header('Content-Type: application/json');
        if($arr_data){
            $str_json =  (isset($_GET['callback'])) ? $_GET['callback'].'('.json_encode($arr_data).')' : json_encode($arr_data);
        }
        die($str_json);
    }
}

require(APPPATH . 'core/NH_Admin_Controller.php');
require(APPPATH . 'core/NH_User_Controller.php');
require(APPPATH . 'core/NH_Auto_Controller.php');
require(APPPATH . 'core/NH_Api_Controller.php');
