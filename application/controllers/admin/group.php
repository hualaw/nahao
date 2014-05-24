<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group extends NH_Admin_Controller {

    private $arr_response = array(
        'status' => 'error',
        'msg' => '操作失败',
    );

    function __construct(){
        parent::__construct();
        $this->load->model('business/admin/business_group','group');
    }

    /**
     * group index
     * @author yanrui@tizi.com
     */
    public function index(){
        $int_start = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        $str_group_name = $this->input->get('group_name') ? trim($this->input->get('group_name')) : '' ;

        $arr_where = array();
        if($str_group_name){
            $arr_where['name'] = $str_group_name;
        }

        $int_count = $this->group->get_group_count($arr_where);

        $this->load->library('pagination');
        $config = config_item('page_admin');
        $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
        $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
        $config['total_rows'] = $int_count;
        $config['per_page'] = PER_PAGE_NO;
        $this->pagination->initialize($config);
        parse_str($this->input->server('QUERY_STRING'),$arr_query_param);


        $this->data['int_count'] = $int_count;
        $this->data['arr_list'] = $this->group->get_group_list($arr_where, $int_start,PER_PAGE_NO);

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
        $this->layout->view('admin/group_list',$this->data);
    }

    /**
     * create group
     * @author yanrui@tizi.com
     */
    public function create(){
        if($this->is_ajax() AND $this->is_post()){
            $str_group_name = trim($this->input->post('name'));
//            echo $str_group_name;exit;
            if($str_group_name){
                $arr_param['name'] = $str_group_name;
                $int_group_id = $this->group->create_group($arr_param);
                if($int_group_id > 0){
                    $this->arr_response['status'] = 'ok';
                    $this->arr_response['msg'] = '创建成功';
                }
            }
        }
        self::json_output($this->arr_response);
    }

    /**
     * group禁用启用
     * @author yanrui@tizi.com
     */
    public function active(){
        if($this->is_ajax() AND $this->is_post()){
            $int_group_id = intval($this->input->post('group_id'));
            $int_status = intval($this->input->post('status'));
            if($int_group_id > 0 AND in_array($int_status,array(0,1))){
                $arr_param = array(
                    'status' => $int_status
                );
                $arr_where = array(
                    'id' => $int_group_id
                );
                $bool_return = $this->group->update_group($arr_param,$arr_where);
                if($bool_return > 0){
                    $this->arr_response['status'] = 'ok';
                    $this->arr_response['msg'] = '修改成功';
                }
            }
        }
        self::json_output($this->arr_response);
    }

}