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
        $this->load->model('business/common/business_user');
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
        $this->smarty->assign('qiniu_url', NH_QINIU_URL);
        $this->smarty->assign('qiniu_video_url', NH_QINIU_VIDEO_URL);

        $static_version = config_item('static_version');
        $this->smarty->assign('static_version', $static_version);
        $this->smarty->assign('is_login', $this->is_login);
        $this->smarty->assign('userdata', $this->session->all_userdata());
        $this->smarty->assign('last_refer_url', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "");
        $this->smarty->assign('perfect_url', student_url().'login/perfect');

        $log_msg = 'In NH_Controller, all_userdata: '.print_r($this->session->all_userdata(), 1);

        log_message('debug_nahao', $log_msg);
        $arr_user_id=$this->session->all_userdata();
        if(isset($arr_user_id['user_id']) && $arr_user_id['user_type']!=2)
        {
            $this->load->model('business/admin/business_lecture');
            $arr_user_lecture=$this->business_lecture->lecture_user_id($arr_user_id['user_id']);
            $this->smarty->assign('arr_user_lecture',$arr_user_lecture);
        }
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
        //log_message('debug_nahao', "In check_login(), ".print_r($this->session->all_userdata(),1));
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
    public function enter_classroom($int_classroom_id,$user_type,$array_data){
        /* open this code below will lead to not open  exercise and evaluating page in class */
        /*if (ENVIRONMENT == "production") $str_classroom_url = 'http://classroom.nahao.com/main.html?';
        else $str_classroom_url = '/classroom/main.html?';*/

        $str_classroom_url = '/classroom/main.html?';

        $arr_class_map = config_item('round_class_map');
        $int_classroom_id = isset($arr_class_map[$int_classroom_id]) ? $arr_class_map[$int_classroom_id] : $int_classroom_id ;
        $array_params = array(
            'UserDBID' => $this->session->userdata('user_id'),
            'ClassID'  => $int_classroom_id,
            'UserType' => $user_type,
        );
        //新增：如果是老师，并且有代理服务器，传mcu服务器地址
        $_user_detail = $this->business_user->get_user_detail($this->session->userdata('user_id'));
        $McuAddr_query_param = '';
        if(isset($_user_detail['teach_priv'])&&$_user_detail['teach_priv']==NH_MEETING_TYPE_TEACHER && $_user_detail['proxy']>0){
        	$mcu_arr = config_item('McuAddr');
        	if(isset($mcu_arr[$_user_detail['proxy']])){
        		$McuAddr_query_param = '&McuAddr='.$mcu_arr[$_user_detail['proxy']];
        	}
        }
        $className = !empty($array_data['class_title']) ? urlencode($array_data['class_title']) : '';
        $UserName = urlencode($this->session->userdata('nickname'));
        //新增：AES加密flash链接
        $uri = http_build_query($array_params);
        $aes_config = array(config_item('AES_key'));
        $this->load->library('AES', $aes_config, 'aes');
        $aes_encrypt_code = urlencode(base64_encode($this->aes->encrypt($uri)));
        log_message('debug_nahao', 'classroom uri is: '.$uri.' and the encrypt_code is:'.$aes_encrypt_code);
        $str_classroom_url .= 'p='.$aes_encrypt_code.'&UserName='.$UserName.'&ClassName='.$className.'&SwfVer='.(config_item('classroom_swf_version')).$McuAddr_query_param;
        return $str_iframe = '<iframe src="'.$str_classroom_url.'" width="100%" height="100%" frameborder="0" name="_blank" id="_blank" ></iframe>';
    }
    
    /**
     * send official email of nahao
     * @param string $email_address
     * @param string $subject
     * @param string $email_content
     * @param string $success_msg  renturn message when the mail is being send successfully
     * @return array
     */
    protected function _send_email($email_address, $subject, $email_content, $success_msg)
    {
        $arr_return = array();
        if(!($email_address && $subject && $email_content)) {
            $arr_return = array('status' => ERROR, 'info' => '参数错误');
            return $arr_return;
        }
        
        $this->load->library('mail');
        $ret = $this->mail->send($email_address, $subject, $email_content);
        if($ret['ret'] == 1) {
            //store this  emailinformation into redis
			$this->load->model('model/common/model_redis', 'redis');
			$this->redis->connect('login');
            $duration = 86400;
            $save_data = array(
                'email' => $email_address,
                'send_time' => time(),
                'subject' => $subject
            );
            $save_result = $this->cache->redis->set(md5($email_address), json_encode($save_data), $duration);
            if($save_result) {
                $arr_return['status'] = SUCCESS;
                $arr_return['info'] = $success_msg;
            } else {
                $arr_return['status'] = ERROR;
                $arr_return['info'] = '服务器繁忙,请稍后再试';
            }
        } else {
            $arr_return['status'] = ERROR;
            $arr_return['info'] = '发送失败,请稍后重试';
        }
        
        return $arr_return;
    }

    //ajax interface
    public function send_captcha()
    {
        $phone = trim($this->input->post('phone'));
        $type = trim($this->input->post('type')); //1,注册；2，订单绑定手机；3，找回密码

        //检查验证码发送频率
        $send_info = array();
        $result = $this->_check_captcha_times($phone);
        if ($result['errno'] == 1) {
            $send_info['status'] = ERROR;
            $send_info['data'] = array('phone' => $phone);
            $send_info['msg'] = '手机号格式不对';
        } else if ($result['errno'] == 2) {
            $send_info['status'] = ERROR;
            $send_info['data'] = array();
            $send_info['msg'] = '验证码发送太频繁，请稍后再试！';
        } else {
            $send_info = $this->_send_captcha($phone, $type);
        }
        //unset($send_info['data']);
        self::json_output($send_info);
    }

    protected function _check_captcha_times($phone)
    {
        /*
         * 1,同一个session_id，60s内只能发送一次验证码
         * 2，同一个手机号，60s内只能发送一次验证码
         * 3，TODO：同一个IP，60s内只能发送3次验证码
         */

        $result = array(
            'errno' => 0,
            'errmsg' => 'success',
        );

        $sid = trim($this->input->cookie('NHID'));
        if (!is_mobile($phone)) {
            $result['errno'] = 1;
            $result['errmsg'] = 'bad_phone_format';
            return $result;
        }

        $this->load->model('model/common/model_redis', 'redis');
        $this->redis->connect('captcha');

        if ($this->cache->redis->exists($phone))
            $this->cache->redis->incr($phone);
        else
            $this->cache->redis->set($phone, 1, REDIS_CAPTCHA_TIMES_EXPIRE_TIME);

        if ($this->cache->redis->exists($sid))
            $this->cache->redis->incr($sid);
        else
            $this->cache->redis->set($sid, 1, REDIS_CAPTCHA_TIMES_EXPIRE_TIME);

        $phone_times = $this->cache->redis->get($phone);
        $sid_times = $this->cache->redis->get($sid);
        if ($phone_times > 1 || $sid_times > 1) {
            $result['errno'] = 2;
            $result['errmsg'] = 'too_busy_captcha_send';
        }

        return $result;
    }

    protected function _send_captcha($phone, $type)
    {
        $this->load->library('sms');
        $this->sms->setPhoneNums($phone);

        $this->load->helper('string');
        $verify_code = random_string('nozero', 4);
        $msg = $verify_code.$this->lang->line('reg_verify_phone_msg');
        $this->sms->setContent($msg);
        $create_time = time();
        $send_ret = $this->sms->send();
        //$send_ret['error'] = 'Ok';

        $info = array(
            'phone' => $phone,
            'verify_code'=>$verify_code,
            'msg'=>$msg,
            'create_time'=>$create_time,
            'type' => $type,
        );

        /*正式环境屏蔽掉info数组*/
        if(ENVIRONMENT == 'production') $info = array();

        //log_message('debug_nahao', "In send captcha, ".print_r($send_ret, 1));
        if($send_ret['error'] == 'Ok')
        {
            //store the captcha into redis
            $this->load->model('model/common/model_redis', 'redis');
            $this->redis->connect('login');

            //store the phone-verify code list to list
            $this->cache->redis->rpush($phone, json_encode(array(
                't'=>$type,
                'vc'=>$verify_code,
                'et'=>$create_time + REDIS_VERIFY_CODE_EXPIRE_TIME
            )));

            $send_info = $this->_log_reg_info(SUCCESS, 'reg_send_verify_code_success', $info);
        }
        else
        {
            $tmp_array = array_merge($info, $send_ret);
            $send_info = $this->_log_reg_info(ERROR, 'reg_send_verify_code_failed', $tmp_array);
        }

        return $send_info;
    }


    function _log_reg_info($status, $msg_type, $info_arr=array(), $info_type='error')
    {
        $arr_return['status'] = $status;
        $arr_return['msg'] = $this->lang->line($msg_type);
        $arr_return['data'] = $info_arr;
        switch($info_type)
        {
            case 'error':
                log_message('ERROR_NAHAO', json_encode($arr_return));
                break;
            case 'info':
                log_message('INFO_NAHAO', json_encode($arr_return));
                break;
            case 'debug':
                log_message('DEBUG_NAHAO', json_encode($arr_return));
                break;
        }
        return $arr_return;
    }

}

require(APPPATH . 'core/NH_Admin_Controller.php');
require(APPPATH . 'core/NH_User_Controller.php');
require(APPPATH . 'core/NH_Auto_Controller.php');
require(APPPATH . 'core/NH_Api_Controller.php');
require(APPPATH . 'core/NH_Crm_Controller.php');
