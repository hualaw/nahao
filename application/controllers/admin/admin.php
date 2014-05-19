<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends NH_Admin_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/admin/business_admin','admin');
    }

    /**
     * admin index
     * @author yanrui@tizi.com
     */
    public function index(){
        $int_start = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        $int_group_id = $this->input->post('group_id') ? intval($this->input->get('group')) : 0 ;
        $int_admin_id = $this->input->post('admin_id') ? intval($this->input->get('admin_id')) : 0 ;
        $str_username = $this->input->post('username') ? trim($this->input->get('username')) : '' ;

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
//        $int_count = $this->admin->get_admin_count($arr_where);
        $int_count = $this->admin->_get_from_db();
        var_dump($int_count);exit;

        $this->load->library('pagination');
        $config = config_item('page_admin');
        $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
        $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
        $config['total_rows'] = 100;
        $config['per_page'] = 10;
        $this->pagination->initialize($config);
        parse_str($this->input->server('QUERY_STRING'),$query_array);
        $this->data['count'] = $int_count;


//        $this->load->model('admin/model_group','group');
//
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
        $this->data['page'] = $this->pagination->create_links();
        $this->data['query_str'] = $query_array;
        $this->layout->view('admin/admin_list',$this->data);

//
//        $this->admin->get_admin_list();
//        $this->layout->view('admin/admin_list');
    }

    public function create_admin(){
        if($this->is_ajax() AND $this->is_post()){
            $str_username = trim($this->input->post('username'));
            $str_phone = trim($this->input->post('phone'));
            $str_email = trim($this->input->post('email'));
            if($str_username){
                $arr_param['nickname'] = $str_username;
                if(is_mobile($str_phone)){
                    $arr_param['phone'] = $str_phone;
                }
                if(is_email($str_phone)){
                    $arr_param['phone'] = $str_phone;
                }

                $arr_param['email'] = $str_email;
                $int_admin_id = $this->admin->create_admin($arr_param = array('nickname'=>'test'));
            }
        }
    }
}