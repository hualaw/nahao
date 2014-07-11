<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group extends NH_Admin_Controller {

    private $arr_response = array(
        'status' => 'error',
        'msg' => '操作失败',
    );

    function __construct(){
        parent::__construct();
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

        $arr_list = $this->group->get_group_list($arr_where, $int_start,PER_PAGE_NO);

        $this->smarty->assign('page',$this->pagination->create_links());
        $this->smarty->assign('count',$int_count);
        $this->smarty->assign('list',$arr_list);
        $this->smarty->assign('arr_query_param', $arr_query_param);
        $this->smarty->assign('view', 'group_list');
        $this->smarty->display('admin/layout.html');
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

    public function group(){
        $int_group_id = $this->input->get('group_id') ? intval($this->input->get('group_id')) : 0;
        $arr_response = array(
            'status' => 'error',
            'data' => array()
        );
        if($int_group_id > 0){
            $arr_group = $this->group->get_group_by_id($int_group_id);
            if($arr_group){
                $arr_response['status'] = 'ok';
                $arr_response['data'] = $arr_group;
            }
        }
        self::json_output($arr_response);
    }

}