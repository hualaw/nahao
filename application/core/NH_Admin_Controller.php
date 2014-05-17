<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 12-6-4
 * Time: 下午3:49
 * To change this template use File | Settings | File Templates.
 */
class NH_Admin_Controller extends NH_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->layout->set_layout('admin/layout');
//        $this->check_login(ROLE_ADMIN);

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
}

/**
 * 判断是否有权限造作
 * @param string $ctrl
 * @param string $act
 * @return bool
 */
function has_permission($ctrl='', $act='')
{
    static $permissions = null;
    if ($permissions === null) {
        $CI =& get_instance();
        if ($CI->user['id'] == 1 OR $CI->user['id'] == 31) {
            $permissions = true;
        } elseif (isset($CI->user['permission']) && $CI->user['permission']) {
            $permissions = $CI->user['permission'];
        } else {
            $permissions = false;
        }
    }
    if (is_bool($permissions)) {
        return $permissions;
    }
//    echo $ctrl.'--'.$act.'--'.isset($permissions[$ctrl][$act]);
    return isset($permissions[strtolower($ctrl)][strtolower($act)]);
}
