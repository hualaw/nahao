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

        $arr_param = array(
            'user_id' => '2211',
            'realname' => 'nahao',
        );
        $arr_result = $this->db->insert(TABLE_USER_INFO, $arr_param);
        var_dump($arr_result);
        $int_insert_id = $this->db->affected_rows();
        o($int_insert_id);

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


        $this->data['int_count'] = $int_count;
        $this->data['arr_list'] = $this->admin->get_admin_list($arr_where, $int_start,PER_PAGE_NO);

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
//        $this->data['list'] = $this->admin->get_admin($arr_condition, $start,$this->limit);
        $this->data['str_page'] = $this->pagination->create_links();
        $this->data['arr_query_param'] = $arr_query_param;
//        $this->layout->view('admin/admin_list',$this->data);

//        $this->smarty->assign('template', 'admin/admin_list.html');
//echo 123;exit;
        o($this->arr_smarty_js);
        $this->smarty->display('admin/layout.html');
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
}