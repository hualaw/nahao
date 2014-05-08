<?php
/**
 * 那好controller超级父类
 * @author yanrui@91waijiao.com
 */
class NH_Controller extends CI_Controller
{
    /**
     * 保存登录有的用户信息
     * @var array
     */
    public $user = array();

    /**
     * 保存当前控制器和方法名
     * @var array $current
     */
    public $current = array();

    function __construct()
    {
        parent::__construct();
        //加载cache
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
        $this->current['current_controller'] = $this->uri->rsegment(1);
        $this->current['current_action'] = $this->uri->rsegment(2);
        $this->load->vars($this->current);
    }

    /**
     * 当前http请求是否为post
     * @return bool
     */
    protected function isPOST()
    {
        return $this->input->server('REQUEST_METHOD') === 'POST';
    }

    /**
     * 当前http请求是否为ajax
     * @return mixed
     */
    protected function isAjax()
    {
        return $this->input->is_ajax_request() || isset($_GET['callback']);
    }

    /**
     * @param int $status 0管理员 1学生 2老师
     * @return bool
     */
    protected function isLogin($status = 1)
    {
        if ($this->user) {
            return true;
        }
        $token = get_cookie("token-{$status}");
        $token = authcode($token, 'DECODE');
        $userid = 0;
        $password = '';
        if ($token) {
            $data = explode("\t", $token);
            $userid = isset($data[0]) ? $data[0] : 0;
            $password = isset($data[1]) ? $data[1] : '';
        }

        if ($userid) {
            $userinfo = $this->cache->get("{$status}-{$userid}");

            if ($userinfo) {
                $userinfo = json_decode($userinfo, true);
                if ($userinfo['password'] === $password) {
                    $this->user = $userinfo;
                    return true;
                }
            }

            $userinfo = null;
            switch ($status) {
                case ROLE_ADMIN: //管理员
                    $this->load->model('admin/Model_Admin', 'admin');
                    $userinfo = $this->admin->get_admin_by_id($userid, 'id, name, salt, password, status');
                    $userinfo['permission'] = $this->admin->get_admin_permission($userid, 'format');
                    break;
                case ROLE_STUDENT: //学生
                    $this->load->model('student/Model_Student', 'student');
                    $userinfo = $this->student->get_stu_by_id($userid, 'id, phone, phone_unverified,phone_verified, email, salt, password, referral_site, device_ok, agent_id, used_card, mcu_addr');
                    if ($userinfo) {
                        $userinfo += $this->student->get_stu_info_by_id($userid, 'nickname, level, has_image, chinese_name, sex, age_range, industry, purpose, target, time_range, examination, source, up_class_level');
                        //yanrui  增加定級狀態 音視頻效果自測 sidebar_block中顯示用
                        $userinfo && $userinfo['score_status'] = $this->student->get_score_status($userid);
                        //verify security
                        $userinfo['account_security_status'] = $this->student->is_account_safe($userinfo);
                        //verify profile totally
                        $userinfo['account_totally_status'] = $this->student->is_account_totally($userinfo);
                    }
                    break;
                case ROLE_TEACHER: //教师
                    $this->load->model('teacher/Model_Teacher', 'teacher');
                    $userinfo = $this->teacher->get_teacher_by_param(array('id'=>$userid), 'id, teacher_name, email, encrypt, password, mcu_addr');//liuchunling 20131008
                    break;
                case ROLE_AGENT: //代理商
                    $this->load->model('agent/Model_Agent', 'agent');
                    $userinfo = $this->agent->get_agent_by_id($userid, 'id, mobile, kind, area, username, salt, password');
                    break;
            }

            if (isset($userinfo['password']) && $userinfo['password'] === $password) {
                $this->user = $userinfo;
                $this->cache->save("{$status}-{$userid}", json_encode($userinfo), 86400);
                return true;
            }
        }
        return false;
    }

    /**
     * ajax json输入
     * @param $data
     */
    static protected function json_output($data)
    {
        header('Content-Type: application/json');
        if(isset($_GET['callback']))
        {
            $callback = $_GET['callback'];
            echo "{$callback}(", json_encode($data), ")";
            die;
        }
        echo json_encode($data);
        die;
    }

    /**
     * 检查请求是否过期
     * @return mixed
     */
    protected function HTTPLastModified()
    {
        $IF_MODIFIED_SINCE = $this->input->server('HTTP_IF_MODIFIED_SINCE');
        if ($IF_MODIFIED_SINCE !== false
            && (TIMESTAMP - (strtotime($IF_MODIFIED_SINCE)) < config_item('http_expires'))
        ) //(当前时间减去最后修改时间) < 不满过期周期
        {
            $this->output->set_status_header(304);
            die;
        } else {
            $Last_Modified = gmdate('D, d M Y H:i:s', TIMESTAMP) . ' GMT'; //修改时间
            $Expires = gmdate('D, d M Y H:i:s', TIMESTAMP + config_item('http_expires')) . ' GMT'; //过期时间
            $this->output->set_header('Last-Modified: ' . $Last_Modified);
            $this->output->set_header('Expires: ' . $Expires);
        }
        return;
    }

}

require(APPPATH.'core/NH_Admin_Controller.php');
require(APPPATH.'core/NH_Student_Controller.php');
require(APPPATH.'core/NH_Teacher_Controller.php');
require(APPPATH.'core/NH_Auto_Controller.php');
require(APPPATH.'core/NH_Api_Controller.php');
