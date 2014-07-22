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
    public function submit(){
        if(self::is_ajax() AND self::is_post()){
            $int_group_id = $this->input->post('id') ? intval($this->input->post('id')) : 0;
            $str_group_name = $this->input->post('name') ? trim($this->input->post('name')) : '';
            $int_group_status = $this->input->post('status') ? trim($this->input->post('status')) : '';
            $bool_flag = false;
            $str_action = '创建';
            $arr_param['name'] = $str_group_name;
            $arr_param['status'] = $int_group_status;
            if($int_group_id > 0){
                $str_action = '修改';
                $arr_where = array(
                    'id' => $int_group_id
                );
                $bool_flag = $this->group->update_group($arr_param, $arr_where);
            }else{
                $int_group_id = $this->group->create_group($arr_param);
                $bool_flag = $int_group_id ? true : false;
            }
            if($bool_flag){
                $this->arr_response['status'] = 'ok';
                $this->arr_response['msg'] = $str_action.'成功';
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

    public function groups(){
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
    
    public function permission_group($gid = 0)
    {
    	$this->pass();
    	
    	
    	$group = T(TABLE_ADMIN_GROUP)->getById($gid);
    	if($group) {
    		$data['group'] = $group;
    		$data['list'] = T(TABLE_PERMISSION)->getAll('',0,0,'controller asc');
    		$data['count'] = count($data['list']);
    		$group_permissions = T(TABLE_GROUP_PERMISSION_RELATION)->getFields(array('permission_id'),'group_id='.$gid);
    		$group_permission = !empty($group_permissions)?array_column($group_permissions, 'permission_id'):array();
    		
    		foreach($data['list'] as &$item) {
    			if(in_array($item['id'], $group_permission)) {
    				$item['permission'] = true;
    			} else {
    				$item['permission'] = false;
    			}
    		}
    		
    		$this->smarty->assign('gid', $gid);
    		$this->smarty->assign('data', $data);
    		$this->smarty->assign('view', 'permission_group');
    		$this->smarty->display('admin/layout.html');
    	} else {
    		show_404();
    	}
    }
    
	//权限设置
    public function permission_group_set()
    {
        $input['permission_id'] = (int) $this->input->post('pid');
        $input['group_id'] = (int) $this->input->post('gid');

        $status = (int) $this->input->post('status');
        $http_response = array('status' => 0);
        if($status) {
            T(TABLE_GROUP_PERMISSION_RELATION)->add($input);
        } else {
            T(TABLE_GROUP_PERMISSION_RELATION)->deleteByWhere("permission_id = ".$input['permission_id']." and group_id=".$input['group_id']);
        }
        self::json_output($http_response);
    }

}