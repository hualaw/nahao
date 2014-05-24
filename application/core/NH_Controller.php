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

    function __construct()
    {
        parent::__construct();
        //加载cache
//        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
        $this->current['controller'] = $this->uri->rsegment(1);
        $this->current['action'] = $this->uri->rsegment(2);
        $this->load->vars($this->current);
        $this->load->library('layout');
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
     * @param int $int_user_type 0管理员 1学生 2老师
     * @return bool
     * @author yanrui@91waijiao.com
     */
    protected function check_login($int_user_type = 1)
    {
        $bool_return = true;
        if (empty($this->userinfo)){
            $this->load->model('business/business_passport','passport');
            $arr_user_cookie = $this->passport->get_user_from_cookie($int_user_type);
            if (isset($arr_user_cookie['user_id'])&&$arr_user_cookie['user_id']!=0) {
                $int_user_id = $arr_user_cookie['user_id'];
                $str_password = $arr_user_cookie['password'];
                $arr_user_cache = $this->passport->get_user_from_cache($int_user_type,$int_user_id);
                if($arr_user_cache){
                    if (isset($arr_user_info['password']) AND $arr_user_info['password'] === $str_password) {
                        $this->userinfo = $arr_user_cache;
                    }else{
                        $bool_return = false;
                    }
                }else{
                    $arr_user_db = $this->passport->get_user_from_db($int_user_type,$int_user_id);
                    if($arr_user_db){
                        if (isset($arr_user_db['password']) && $arr_user_db['password'] === $str_password) {
                            $this->userinfo = $arr_user_db;
                            $this->cache->save("{$int_user_type}-{$int_user_id}", json_encode($arr_user_db), 86400);
                        }else{
                            $bool_return = false;
                        }
                    }else{
                        $bool_return = false;
                    }
                }
            }else{
                $bool_return = false;
            }
        }
        return $bool_return;
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
