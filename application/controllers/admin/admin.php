<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends NH_admin_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/admin/business_admin','admin');
    }
    public function index(){

        $this->load->model('model/admin/business_admin','admin');
        $int_return = $this->admin->create_admin($arr_param = array('nickname'=>'test'));
        var_dump($int_return);exit;
//        var_dump($this->current);exit;

        $start = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
//        $arr_condition = array();
//        if($this->input->get('group')!==false AND $this->input->get('group')!==''){
//            $arr_condition['group_id'] = intval($this->input->get('group'));
//        }
//
//        $this->load->model('admin/model_admin','admin');
//        $this->data['count'] = $count = $this->admin->get_admin_count($arr_condition);
//var_dump($this->db->queries);
        $count = 100;
        $this->load->library('pagination');
        $config = config_item('page_admin');
        $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
        $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
        $config['total_rows'] = 100;
        $config['per_page'] = 10;
        $this->pagination->initialize($config);
        parse_str($this->input->server('QUERY_STRING'),$query_array);

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
        $str_nickname = trim($this->input->post('nickname'));
        $arr_param['nickname'] = $str_nickname;
        $int_last_id = $this->admin->create_admin($arr_param);
    }
}