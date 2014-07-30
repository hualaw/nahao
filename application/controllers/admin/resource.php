<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 录制视频管理
 * Class Resource
 * @author yanrui@tizi.com
 */
class Resource extends NH_Admin_Controller {

    private $arr_response = array(
        'status' => 'error',
        'msg' => '操作失败',
    );

    /**
     * Resource list
     * @author yanrui@tizi.com
     */
    public function index () {
        $int_start = $this->uri->segment(3) ? $this->uri->segment(3) : 0;

        $arr_where = array();

        $int_count = $this->resource->get_resource_count($arr_where);
        $arr_list = $this->resource->get_resource_list($arr_where, $int_start,PER_PAGE_NO);
//        o($arr_list,true);

        $this->load->library('pagination');
        $config = config_item('page_admin');
        $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
        $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
        $config['total_rows'] = $int_count;
        $config['per_page'] = PER_PAGE_NO;
        $this->pagination->initialize($config);
        parse_str($this->input->server('QUERY_STRING'),$arr_query_param);

        $this->smarty->assign('page',$this->pagination->create_links());
        $this->smarty->assign('count',$int_count);
        $this->smarty->assign('resources',$arr_list);
        $this->smarty->assign('arr_query_param', $arr_query_param);
        $this->smarty->assign('view', 'resource_list');
        $this->smarty->display('admin/layout.html');

    }

    /**
     * get token
     * @author yanrui@tizi.com
     */
    public function token(){
        //generate param for uploading to qiniu
        require_once APPPATH . 'libraries/qiniu/rs.php';
        require_once APPPATH . 'libraries/qiniu/io.php';
        Qiniu_SetKeys ( NH_QINIU_ACCESS_KEY, NH_QINIU_SECRET_KEY );
        $obj_putPolicy = new Qiniu_RS_PutPolicy ( NH_QINIU_RECORD_BUCKET );
        $str_resource_upToken = $obj_putPolicy->Token ( null );

//        $this->load->helper('string');
//        $str_salt = random_string('alnum', 6);
        //course img file name
//        $str_new_img_file_name = 'course_'.date('YmdHis',time()).'_i'.$str_salt.'.png';
//        $str_new_video_file_name = 'course_'.date('YmdHis',time()).'_v'.$str_salt.'.mp4';
        $this->arr_response['status'] = 'ok';
        $this->arr_response['msg'] = 'success';
        $this->arr_response['token'] = $str_resource_upToken;
        self::json_output($this->arr_response);
    }

    /**
     * add resource
     * @author yanrui@tizi.com
     */
    public function add(){
        $str_uri = $this->input->post('uri') ? $this->input->post('uri') : '';
        if($str_uri){
            $arr_param = array(
                'uri' => $str_uri,
                'admin_id' => $this->userinfo['id'],
                'username' => $this->userinfo['username'],
                'create_time' => TIME_STAMP
            );
            $int_last_id = $this->resource->create_resource($arr_param);
            if($int_last_id){
                $this->arr_response['status'] = 'ok';
                $this->arr_response['msg'] = '创建成功';
            }
        }
        self::json_output($this->arr_response);
    }
}