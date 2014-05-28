<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 轮管理
 * Class Round
 * @author yanrui@tizi.com
 */
class Round extends NH_Admin_Controller {

    private $arr_response = array(
        'status' => 'error',
        'msg' => '操作失败',
    );

    function __construct(){
        parent::__construct();
    }

    /**
     * round index
     * @author yanrui@tizi.com
     */
    public function index(){
        $int_start = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        $int_group_id = $this->input->get('group_id') ? intval($this->input->get('group')) : 0 ;
        $int_round_id = $this->input->get('round_id') ? intval($this->input->get('round_id')) : 0 ;
        $str_username = $this->input->get('username') ? trim($this->input->get('username')) : '' ;

        $arr_where = array();
        if($int_group_id > 0){
            $arr_where['group_id'] = $int_group_id;
        }
        if($int_round_id > 0){
            $arr_where['round_id'] = $int_round_id;
        }
        if($str_username){
            $arr_where['username'] = $str_username;
        }

        $int_count = $this->round->get_round_count($arr_where);

        $this->load->library('pagination');
        $config = config_item('page_admin');
        $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
        $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
        $config['total_rows'] = $int_count;
        $config['per_page'] = PER_PAGE_NO;
        $this->pagination->initialize($config);
        parse_str($this->input->server('QUERY_STRING'),$arr_query_param);

        $arr_list = $this->round->get_round_list($arr_where, $int_start,PER_PAGE_NO);

        $this->smarty->assign('page',$this->pagination->create_links());
        $this->smarty->assign('count',$int_count);
        $this->smarty->assign('list',$arr_list);
        $this->smarty->assign('arr_query_param', $arr_query_param);
        $this->smarty->assign('view', 'round_list');
        $this->smarty->assign('js_module', 'adminRound');
        $this->smarty->display('admin/layout.html');
    }

    /**
     * create round
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
                $int_round_id = $this->round->create_round($arr_param);
                if($int_round_id > 0){
                    $this->arr_response['status'] = 'ok';
                    $this->arr_response['msg'] = '创建成功';
                }
            }
        }
        self::json_output($this->arr_response);
    }

}