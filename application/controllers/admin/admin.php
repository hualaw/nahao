<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 管理员管理
 * Class Admin
 * @author yanrui@tizi.com
 */
class Admin extends NH_Admin_Controller {

    private $arr_response = array(
        'status' => 'error',
        'msg' => '操作失败',
    );

    function __construct(){
        parent::__construct();
    }


    /**
     * admin index
     * @author yanrui@tizi.com
     */
    public function index(){
        $int_start = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        $int_group_id = $this->input->get('group_id') ? intval($this->input->get('group')) : 0 ;
        $int_admin_id = $this->input->get('admin_id') ? intval($this->input->get('admin_id')) : 0 ;
        $str_username = $this->input->get('username') ? trim($this->input->get('username')) : '' ;

        $arr_where = array();
        if($int_group_id > 0){
            $arr_where['group_id'] = $int_group_id;
        }
        if($int_admin_id > 0){
            $arr_where['admin_id'] = $int_admin_id;
        }
        if($str_username){
            $arr_where['username'] = $str_username;
        }

        $int_count = $this->admin->get_admin_count($arr_where);

        $this->load->library('pagination');
        $config = config_item('page_admin');
        $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
        $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
        $config['total_rows'] = $int_count;
        $config['per_page'] = PER_PAGE_NO;
        $this->pagination->initialize($config);
        parse_str($this->input->server('QUERY_STRING'),$arr_query_param);

        $arr_list = $this->admin->get_admin_list($arr_where, $int_start,PER_PAGE_NO);

        $this->smarty->assign('page',$this->pagination->create_links());
        $this->smarty->assign('count',$int_count);
        $this->smarty->assign('list',$arr_list);
        $this->smarty->assign('arr_query_param', $arr_query_param);
        $this->smarty->assign('view', 'admin_list');
        $this->smarty->display('admin/layout.html');

//        $this->load->model('admin/model_group','group');
//        $all_group_permission = $this->group->get_all_group_permission();
//        $group_permission = array();
//        foreach($all_group_permission as $value){
//            if(!isset($group_permission[$value['group_id']]['group_name'])){
//                $group_permission[$value['group_id']]['group_name'] = $value['group_name'];
//            }
//            $group_permission[$value['group_id']]['list'][] = $value;
//        }
//        var_dump($group_permission);exit;
//        $this->data['all_group_permission'] = $group_permission;
    }

    /**
     * create admin
     * @author yanrui@tizi.com
     */
    public function create(){
        if($this->is_ajax() AND $this->is_post()){
            $str_username = trim($this->input->post('username'));
            $str_phone = trim($this->input->post('phone'));
            $str_email = trim($this->input->post('email'));
//            echo $str_username.'--'.$str_phone.'--'.$str_email;exit;
            if($str_username){
                $arr_param['username'] = $str_username;
                if(is_mobile($str_phone)){
                    $arr_param['phone'] = $str_phone;
                }
                if(is_email($str_email)){
                    $arr_param['email'] = $str_email;
                }
                $int_admin_id = $this->admin->create_admin($arr_param);
                if($int_admin_id > 0){
                    $this->arr_response['status'] = 'ok';
                    $this->arr_response['msg'] = '创建成功';
                }
            }
        }
        self::json_output($this->arr_response);
    }

    /**
     * admin账户禁用启用
     * @author yanrui@tizi.com
     */
    public function active(){
        if($this->is_ajax() AND $this->is_post()){
            $int_admin_id = intval($this->input->post('admin_id'));
            $int_status = intval($this->input->post('status'));
            if($int_admin_id > 1 AND in_array($int_status,array(0,1))){
                $arr_param = array(
                    'status' => $int_status
                );
                $arr_where = array(
                    'id' => $int_admin_id
                );
                $bool_return = $this->admin->update_admin($arr_param,$arr_where);
                if($bool_return > 0){
                    $this->arr_response['status'] = 'ok';
                    $this->arr_response['msg'] = '修改成功';
                }
            }
        }
        self::json_output($this->arr_response);
    }


    /**
     * password reset
     */
    public function password(){
        if(self::is_post()){
            $str_old_password = $this->input->post('old_password') ? trim($this->input->post('old_password')) : '';
            $str_new_password = $this->input->post('new_password') ? trim($this->input->post('new_password')) : '';
            $str_new_password_confirm = $this->input->post('new_password_confirm') ? trim($this->input->post('new_password_confirm')) : '';
            header("Content-type: text/html; charset=utf-8");
            if($str_old_password AND $str_new_password AND $str_new_password_confirm){
                $int_admin_id = $this->userinfo['id'];
                $arr_admin = $this->admin->get_admin_by_id($int_admin_id);
                $bool_result = check_password($arr_admin['salt'],$str_old_password,$arr_admin['password']);
                if($bool_result===true){
                    if($str_new_password==$str_new_password_confirm){
                        //update
                        $arr_param = array(
                            'password' => create_password($arr_admin['salt'],$str_new_password)
                        );
                        $arr_where = array(
                            'id' => $int_admin_id
                        );
                        $this->admin->update_admin($arr_param,$arr_where);
                        $this->load->model('business/admin/business_passport','passport');
                        $this->passport->logout();
                        redirect('/');
                    }else{
                        die('新密码不一致');
                    }
                }else{
                    die('旧密码不正确');
                }
            }
        }else{
            $this->smarty->assign('view', 'password_edit');
            $this->smarty->display('admin/layout.html');
        }
    }
}