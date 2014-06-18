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
        //var_dump($_SERVER);
        $this->smarty->assign('site_url', site_url());
        $this->smarty->assign('static_url', static_host_url());
        $this->smarty->assign('teacher_url', teacher_url());
        $this->smarty->assign('admin_url', admin_url());
        $this->smarty->assign('student_url', student_url());

        $static_version = config_item('static_version');
        $this->smarty->assign('static_version', $static_version);
        $this->smarty->assign('is_login', $this->is_login);
        $this->smarty->assign('userdata', $this->session->all_userdata());
        $this->smarty->assign('last_refer_url', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "");
        $this->smarty->assign('perfect_url', site_url().'login/perfect');
        
/*         echo "<pre>";
        print_r($this->session->all_userdata());
        echo "</pre>"; */
       
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
        if($this->is_login)
        {
            $show_nickname = $this->session->userdata('nickname');
            /*
            $show_nickname_len = get_name_length($show_nickname);
            if($show_nickname_len > MAX_NICKNAME_LEN)
                $show_nickname = substr($show_nickname, 0 , 3*MAX_NICKNAME_LEN)."...";
            */
            $this->smarty->assign('nickname', $show_nickname);
        }
    }
    /**
     * 以json格式输出
     * @param array $arr_param
     * @author yanrui@91waijiao.com
     */
    protected static function json_output($arr_param)
    {
        $arr_data = is_array($arr_param) ? $arr_param : array();
        header('Content-Type: application/json');
        die( (isset($_GET['callback'])) ? $_GET['callback'].'('.json_encode($arr_data).')' : json_encode($arr_data));
    }


    /**
     * enter classroom
     * @author yanrui@tizi.com
     */
    public function enter_classroom($int_classroom_id){
        $str_classroom_url = '/classroom/main.html?';
        $array_params = array(
            'UserDBID' => $this->session->userdata('user_id'),
            'ClassID'  => $int_classroom_id,
            'UserType' => $this->session->userdata('user_type'),
            'UserName' => $this->session->userdata('nickname'),
            'ClsSwfVer'   => config_item('classroom_swf_version'), //avoid browser cache
        );
        $str_classroom_url .= http_build_query($array_params);
        return $str_iframe = '<iframe src="'.$str_classroom_url.'" width="100%" height="100%" frameborder="0" name="_blank" id="_blank" ></iframe>';

//        $int_classroom_id = $this->uri->rsegment(3) ? $this->uri->rsegment(3) : 0;
//        if($int_classroom_id){
//            $str_classroom_url = enter_classroom($int_classroom_id,$int_user_type);
//        }
//        $str_classroom_url = 'http://'.__HOST__.'/nahao_classroom/main.html';

//        $str_classroom_url = 'http://admin.nahaotest.com/admin/test';
//o($str_classroom_url,true);
//        echo nh_curl($str_classroom_url,false);exit;
//        $str_classroom_url = 'http://admin.nahaodev.com/nahao_classroom/main.html';
//        $str_iframe = '<iframe src="'.$str_classroom_url.'" width="100%" height="100%" frameborder="0" name="_blank" id="_blank" ></iframe>';
//        $str_iframe .= '<script>function student_get_exercise_page(id){console.log("liubing!");}//student_get_exercise_page();</script>';
//        echo $str_iframe;
//        exit;
//        $this->smarty->assign('iframe',$str_iframe);
//        $this->smarty->assign('view', 'classroom');
//        $this->smarty->display('admin/layout.html');
    }

}

require(APPPATH . 'core/NH_Admin_Controller.php');
require(APPPATH . 'core/NH_User_Controller.php');
require(APPPATH . 'core/NH_Auto_Controller.php');
require(APPPATH . 'core/NH_Api_Controller.php');
