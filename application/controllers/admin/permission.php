<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permission extends NH_Admin_Controller {

    private $arr_response = array(
        'status' => 'error',
        'msg' => '操作失败',
    );

    function __construct(){
        parent::__construct();
    }

    /**
     * permission index
     * @author yanrui@tizi.com
     */
    public function index(){
        $int_start = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        $str_permission_name = $this->input->get('permission_name') ? trim($this->input->get('permission_name')) : '' ;
        $str_permission_controller = $this->input->get('permission_controller') ? trim($this->input->get('permission_controller')) : '' ;
        $str_permission_method = $this->input->get('permission_method') ? trim($this->input->get('permission_method')) : '' ;

        $arr_where = array();
        if($str_permission_name){
            $arr_where['name'] = $str_permission_name;
        }
        if($str_permission_controller){
            $arr_where['controller'] = $str_permission_controller;
        }
        if($str_permission_method){
            $arr_where['action'] = $str_permission_method;
        }

        $int_count = $this->permission->get_permission_count($arr_where);

        $this->load->library('pagination');
        $config = config_item('page_admin');
        $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
        $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
        $config['total_rows'] = $int_count;
        $config['per_page'] = PER_PAGE_NO;
        $this->pagination->initialize($config);
        parse_str($this->input->server('QUERY_STRING'),$arr_query_param);

        $arr_list = $this->permission->get_permission_list($arr_where, $int_start,PER_PAGE_NO);

        $this->smarty->assign('page',$this->pagination->create_links());
        $this->smarty->assign('count',$int_count);
        $this->smarty->assign('list',$arr_list);
        $this->smarty->assign('arr_query_param', $arr_query_param);
        $this->smarty->assign('view', 'permission_list');
        $this->smarty->display('admin/layout.html');
    }

    /**
     * create permission
     * @author yanrui@tizi.com
     */
    public function submit(){
//     	print_r();
        if(self::is_ajax() AND self::is_post()){
            $int_permission_id = $this->input->post('id') ? intval($this->input->post('id')) : 0;
            $str_permission_name = $this->input->post('name') ? trim($this->input->post('name')) : '';
            $str_permission_controller = $this->input->post('controller') ? trim($this->input->post('controller')) : '';
            $str_permission_action = $this->input->post('action') ? trim($this->input->post('action')) : '';
            $int_permission_status = $this->input->post('status') ? trim($this->input->post('status')) : '';
            $bool_flag = false;
            $str_action = '创建成功';
            $arr_param['name'] = $str_permission_name;
            $arr_param['controller'] = $str_permission_controller;
            $arr_param['action'] = $str_permission_action;
            $arr_param['status'] = $int_permission_status;
            $is_exist = T(TABLE_PERMISSION)->count("controller='".$str_permission_controller."' and action='".$str_permission_action."'");
            
            if (!empty($is_exist)){
            	$str_action = '不能重复添加';
            	$bool_flag = true;
            }else{
	            if($int_permission_id > 0){
	                $str_action = '修改成功';
	                $arr_where = array(
	                    'id' => $int_permission_id
	                );
	                $bool_flag = $this->permission->update_permission($arr_param, $arr_where);
	            }else{
	                $int_permission_id = $this->permission->create_permission($arr_param);
	            	$str_action = '添加成功';
	            	$int_permission_id = $int_permission_id;
	                $bool_flag = $int_permission_id ? true : false;
	            }
            }
            if($bool_flag){
                $this->arr_response['status'] = 'ok';
                $this->arr_response['msg'] = $str_action;
            }
        }
        self::json_output($this->arr_response);
    }

    /**
     * permission禁用启用
     * @author yanrui@tizi.com
     */
    public function active(){
        if($this->is_ajax() AND $this->is_post()){
            $int_permission_id = intval($this->input->post('permission_id'));
            $int_status = intval($this->input->post('status'));
            if($int_permission_id > 0 AND in_array($int_status,array(0,1))){
                $arr_param = array(
                    'status' => $int_status
                );
                $arr_where = array(
                    'id' => $int_permission_id
                );
                $bool_return = $this->permission->update_permission($arr_param,$arr_where);
                if($bool_return > 0){
                    $this->arr_response['status'] = 'ok';
                    $this->arr_response['msg'] = '修改成功';
                }
            }
        }
        self::json_output($this->arr_response);
    }

    public function permissions(){
        $int_permission_id = $this->input->get('permission_id') ? intval($this->input->get('permission_id')) : 0;
        $arr_response = array(
            'status' => 'error',
            'data' => array()
        );
        if($int_permission_id > 0){
            $arr_permission = $this->permission->get_permission_by_id($int_permission_id);
//             print_r($arr_permission);
//             exit();
            if($arr_permission){
                $arr_response['status'] = 'ok';
                $arr_response['data'] = $arr_permission;
            }
        }
        self::json_output($arr_response);
    }

}