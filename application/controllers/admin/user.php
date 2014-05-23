<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 管理员管理
 * Class User
 * @author yanrui@tizi.com
 */
class User extends NH_Admin_Controller {

    private $arr_response = array(
        'status' => 'error',
        'msg' => '操作失败',
    );

    function __construct(){
        parent::__construct();
        $this->load->model('business/admin/business_user','user');
    }

    /**
     * user index
     * @author yanrui@tizi.com
     */
    public function index(){
        $int_start = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        $int_user_id = $this->input->get('user_id') ? intval($this->input->get('user_id')) : 0 ;
        $str_username = $this->input->get('nickname') ? trim($this->input->get('nickname')) : '' ;

        $arr_where = array();
        if($int_user_id > 0){
            $arr_where['user_id'] = $int_user_id;
        }
        if($str_username){
            $arr_where['nickname'] = $str_username;
        }

        $int_count = $this->user->get_user_count($arr_where);

        $this->load->library('pagination');
        $config = config_item('page_admin');
        $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
        $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
        $config['total_rows'] = $int_count;
        $config['per_page'] = PER_PAGE_NO;
        $this->pagination->initialize($config);
        parse_str($this->input->server('QUERY_STRING'),$arr_query_param);

        $this->data['int_count'] = $int_count;
        $this->data['arr_list'] = $this->user->get_user_list($arr_where, $int_start,PER_PAGE_NO);

        $this->data['str_page'] = $this->pagination->create_links();
        $this->data['arr_query_param'] = $arr_query_param;
        $this->layout->view('admin/user_list',$this->data);
    }

    /**
     * create user
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
                $int_user_id = $this->user->create_user($arr_param);
                if($int_user_id > 0){
                    $this->arr_response['status'] = 'ok';
                    $this->arr_response['msg'] = '创建成功';
                }
            }
        }
        self::json_output($this->arr_response);
    }

    /**
     * user账户禁用启用
     * @author yanrui@tizi.com
     */
    public function active(){
        if($this->is_ajax() AND $this->is_post()){
            $int_user_id = intval($this->input->post('user_id'));
            $int_status = intval($this->input->post('status'));
            if($int_user_id > 1 AND in_array($int_status,array(0,1))){
                $arr_param = array(
                    'status' => $int_status
                );
                $arr_where = array(
                    'id' => $int_user_id
                );
                $bool_return = $this->user->update_user($arr_param,$arr_where);
                if($bool_return > 0){
                    $this->arr_response['status'] = 'ok';
                    $this->arr_response['msg'] = '修改成功';
                }
            }
        }
        self::json_output($this->arr_response);
    }
}