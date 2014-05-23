<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class NH_Admin_Controller
 * @author yanrui@tizi.com
 */
class NH_Admin_Controller extends NH_Controller
{
    private $not_need_login_controller = array('passport');
    function __construct()
    {
        parent::__construct();

        $this->load->model('business/admin/business_admin','admin');
        $this->layout->set_layout('admin/layout');
        if(!in_array($this->current['controller'],$this->not_need_login_controller)){
            $bool_login_flag = self::check_login(ROLE_ADMIN);
            if($bool_login_flag===true){
                $this->load->vars('userinfo',$this->userinfo);
            }else{
                redirect('/passport');
            }
        }
        /*if($this->user){
            $this->load->vars('user',$this->user);
            $this->load->model('admin/model_permission','permission');
            $controller = $this->router->fetch_class();//$this->uri->segment(1);
            $action = $this->router->fetch_method();//$this->uri->segment(2)===false ? 'index' : $this->uri->segment(2);
            //$permission_id = $this->permission->get_permission_by_controller_action($controller,$action);
//            var_dump($this->user);
            if(! has_permission($controller, $action)){
//                redirect('/passport');
//                exit('no permission');
            }
        }else{
            if($this->router->fetch_class() != 'passport')
            {
                redirect('/passport');
            }
        }*/
    }


    /**
     * 校验管理员是否登录，未登录返回false，登录返回true，并且给$this->userinfo赋值
     * @return bool
     * @author yanrui@tizi.com
     */
    protected function check_login()
    {
        $bool_return = true;
        $this->load->model('business/admin/business_passport','passport');
        $arr_user_cookie = $this->passport->get_token_from_cookie();
        if (isset($arr_user_cookie['user_id'])&&$arr_user_cookie['user_id']!=0) {
            $int_user_id = $arr_user_cookie['user_id'];
            $str_password = $arr_user_cookie['password'];
            $arr_user_cache = false;//  $this->passport->get_user_from_cache(ROLE_ADMIN,$int_user_id);
            if($arr_user_cache){
                if (isset($arr_user_info['password']) AND $arr_user_info['password'] === $str_password) {
                    $this->userinfo = $arr_user_cache;
                }else{
                    $bool_return = false;
                }
            }else{
                $arr_user_db = $this->passport->get_user_from_db(ROLE_ADMIN,$int_user_id);
                if($arr_user_db){
                    if (isset($arr_user_db['password']) && $arr_user_db['password'] === $str_password) {
                        $this->userinfo = $arr_user_db;
//                        $this->cache->save("{ROLE_ADMIN}-{$int_user_id}", json_encode($arr_user_db), 86400);
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
        return $bool_return;
    }


    /**
     * 判断是否有权限造作
     * @param string $ctrl
     * @param string $act
     * @return bool
     * @author yanrui@tizi.com
     */
//    function has_permission($ctrl='', $act='')
//    {
//        static $permissions = null;
//        if ($permissions === null) {
//            $CI =& get_instance();
//            if ($CI->user['id'] == 1 OR $CI->user['id'] == 31) {
//                $permissions = true;
//            } elseif (isset($CI->user['permission']) && $CI->user['permission']) {
//                $permissions = $CI->user['permission'];
//            } else {
//                $permissions = false;
//            }
//        }
//        if (is_bool($permissions)) {
//            return $permissions;
//        }
////    echo $ctrl.'--'.$act.'--'.isset($permissions[$ctrl][$act]);
//        return isset($permissions[strtolower($ctrl)][strtolower($act)]);
//    }

}



