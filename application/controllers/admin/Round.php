<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 轮管理
 * Class Round
 * @author yanrui@tizi.com
 */
class Round extends NH_Admin_Controller
{

    private $arr_response = array(
        'status' => 'error',
        'msg' => '操作失败',
    );

    function __construct()
    {
        parent::__construct();
    }

    /**
     * round index
     * @author yanrui@tizi.com
     */
    public function index()
    {
        $int_start = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        $int_status = $this->input->get('status') ? intval($this->input->get('status')) : 0 ;
        $int_subject = $this->input->get('subject') ? intval($this->input->get('subject')) : 0 ;
        $str_course_type = $this->input->get('course_type') ? intval($this->input->get('course_type')) : 0 ;
        $str_search_type = $this->input->get('search_type') ? intval($this->input->get('search_type')) : 0 ;
        $str_search_value = $this->input->get('search_value') ? trim($this->input->get('search_value')) : '' ;

        $arr_where = array();
        if($int_status > 0){
            $arr_where['status'] = --$int_status;
        }
        if($int_subject > 0){
            $arr_where['subject'] = $int_subject;
        }
        if($str_course_type){
            $arr_where['course_type'] = $str_course_type;
        }
        if($str_search_type > 0 AND $str_search_value !=''){
            if($str_search_type==1){
                $arr_where['title'] = $str_search_value;
            }elseif($str_search_type==2){
                $int_teacher_id = intval($str_search_value);
                $arr_where['teacher_id'] = $int_teacher_id;
            }elseif($str_search_type==3){
                $int_round_id = intval($str_search_value);
                $arr_where['id'] = $int_round_id;
            }
        }

        $int_count = $this->round->get_round_count($arr_where);
        $arr_list = $this->round->get_round_list($arr_where, $int_start,PER_PAGE_NO);
//        o($arr_list,true);

        $this->load->model('business/common/business_subject','subject');
        $arr_subjects = $this->subject->get_subjects_like_kv();
        $this->load->model('business/common/business_course_type','course_type');
        $arr_course_types = $this->course_type->get_course_types_like_kv();

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
        $this->smarty->assign('list',$arr_list);
        $this->smarty->assign('course_status',config_item('course_status'));
        $this->smarty->assign('round_sale_status',config_item('round_sale_status'));
        $this->smarty->assign('round_teach_status',config_item('round_teach_status'));
        $this->smarty->assign('round_list_search_type',config_item('admin_round_list_search_type'));
        $this->smarty->assign('subjects',$arr_subjects);
        $this->smarty->assign('course_types',$arr_course_types);
        $this->smarty->assign('query_param', $arr_query_param);
        $this->smarty->assign('view', 'round_list');
        $this->smarty->display('admin/layout.html');
    }

    /**
     * create/update round
     * @author yanrui@tizi.com
     */
    public function submit()
    {
        if ($this->is_ajax() AND $this->is_post()) {
            header("Content-type: text/html; charset=utf-8");
            $int_round_id = $this->input->post('id') ? intval($this->input->post('id')) : 0;
            $str_title = $this->input->post('title') ? trim($this->input->post('title')) : '';
            $str_subtitle = $this->input->post('subtitle') ? trim($this->input->post('subtitle')) : '';
            $str_intro = $this->input->post('intro') ? trim($this->input->post('intro')) : '';
            $str_description = $this->input->post('description') ? trim($this->input->post('description')) : '';
            $str_students = $this->input->post('students') ? trim($this->input->post('students')) : '';
            $int_subject = $this->input->post('subject') ? intval($this->input->post('subject')) : '';
            $int_course_type = $this->input->post('course_type') ? intval($this->input->post('course_type')) : 0;
            $int_reward = $this->input->post('reward') ? intval($this->input->post('reward')) : 0;
            $int_price = $this->input->post('price') ? intval($this->input->post('price')) : 0;
            $str_video = $this->input->post('video') ? trim($this->input->post('video')) : '';
            $str_img = $this->input->post('img') ? trim($this->input->post('img')) : '';
            $int_grade_from = $this->input->post('grade_from') ? intval($this->input->post('grade_from')) : 0;
            $int_grade_to = $this->input->post('grade_to') ? intval($this->input->post('grade_to')) : 0;
            $arr_classes = $this->input->post('classes') ? $this->input->post('classes') : array();
            $arr_teachers = $this->input->post('teachers') ? $this->input->post('teachers') : array();

            $int_course_id = $this->input->post('course_id') ? intval($this->input->post('course_id')) : '';
            $int_caps = $this->input->post('caps') ? intval($this->input->post('caps')) : '';
            $float_sale_price = $this->input->post('sale_price') ? trim($this->input->post('sale_price')) : '';
            $int_sell_begin_time = $this->input->post('sell_begin_time') ? strtotime(trim($this->input->post('sell_begin_time'))) : 0;
            $int_sell_end_time = $this->input->post('sell_end_time') ? strtotime(trim($this->input->post('sell_end_time'))) : '';
            $int_start_time = $this->input->post('start_time') ? strtotime(trim($this->input->post('start_time'))) : '';
            $int_end_time = $this->input->post('end_time') ? strtotime(trim($this->input->post('end_time'))) : '';

//            o($this->input->post(),true);

            if ($str_title AND $str_subtitle AND $str_intro AND $str_description AND $str_students AND $int_subject AND $int_course_type AND $int_reward AND $int_price /*AND $str_video AND $str_img*/ AND $int_grade_from AND $int_grade_to AND $arr_classes AND $arr_teachers AND $int_course_id AND $int_caps AND $float_sale_price AND $int_sell_begin_time AND $int_sell_end_time AND $int_start_time AND $int_end_time) {
                $arr_param['title'] = $str_title;
                $arr_param['subtitle'] = $str_subtitle;
                $arr_param['intro'] = $str_intro;
                $arr_param['description'] = $str_description;
                $arr_param['students'] = $str_students;
                $arr_param['subject'] = $int_subject;
                $arr_param['course_type'] = $int_course_type;
                $arr_param['reward'] = $int_reward;
                $arr_param['price'] = $int_price;
                $arr_param['video'] = $str_video;
                $arr_param['img'] = $str_img;
                $arr_param['grade_from'] = $int_grade_from;
                $arr_param['grade_to'] = $int_grade_to;

                $arr_param['course_id'] = $int_course_id;
                $arr_param['caps'] = $int_caps;
                $arr_param['sale_price'] = $float_sale_price;
                $arr_param['sell_begin_time'] = $int_sell_begin_time;
                $arr_param['sell_end_time'] = $int_sell_end_time;
                $arr_param['start_time'] = $int_start_time;
                $arr_param['end_time'] = $int_end_time;


//                o($arr_param,true);

                if ($int_round_id > 0) {
                    //update
                    $arr_where = array(
                        'id' => $int_round_id
                    );
                    $bool_flag = $this->round->update_round($arr_param, $arr_where);
                } else {
                    //create
                    $int_round_id = $this->round->create_round($arr_param);
                    $bool_flag = $int_round_id > 0 ? true : false;
                }

//                o($arr_param);
//                o($bool_flag,true);

                if ($bool_flag == true) {
                    //create或update都要先清除teachers和classes再重新插入
                    $this->round->create_round_teacher_batch($int_round_id, $arr_teachers);
                    $this->load->model('business/admin/business_class', 'class');
                    $bool_class = $this->class->create_classes($int_course_id, $int_round_id, $arr_classes);
//                o($bool_class,true);
                    $this->arr_response['status'] = 'ok';
                    $this->arr_response['msg'] = '创建成功';
                    $this->arr_response['redirect'] = '/round';
                }
            }
        }
        self::json_output($this->arr_response);
    }


    /**
     * edit round interface
     * @author yanrui@tizi.com
     */
    public function edit()
    {
        $int_round_id = $this->input->get('round_id') ? intval($this->input->get('round_id')) : 0;
        $int_course_id = $this->input->get('course_id') ? intval($this->input->get('course_id')) : 0;
        $arr_course = $arr_lessons = array();
        $str_action = 'create';

        //update
        if ($int_round_id > 0) {
            $str_action = 'update';
            $arr_round = $this->round->get_round_by_id($int_round_id);
            $arr_teachers = $this->round->get_teachers_by_round_id($int_round_id);
            $this->load->model('business/admin/business_class', 'class');
            $arr_classes = $this->round->get_classes_by_round_id($int_round_id);
        } else {
            //create
            //course base info
            $this->load->model('business/admin/business_course', 'course');
            $arr_round = $arr_course = $this->course->get_course_by_id($int_course_id);
            //course teachers
            $arr_teachers = $this->course->get_teachers_by_course_id($int_course_id);
//            o($this->db->last_query());
//            o($arr_teachers,true);
            $this->load->model('business/admin/business_lesson', 'lesson');
            //course lessons
            $arr_classes = $arr_lessons = $this->lesson->get_lessons_by_course_id($int_course_id);
        }

        //create
        //subjects
        $this->load->model('business/common/business_subject', 'subject');
        $arr_subjects = $this->subject->get_subjects_like_kv();
        //course_types
        $this->load->model('business/common/business_course_type', 'course_type');
        $arr_course_types = $this->course_type->get_course_types_like_kv();

        //generate param for uploading to qiniu
        require_once APPPATH . 'libraries/qiniu/rs.php';
        require_once APPPATH . 'libraries/qiniu/io.php';
        Qiniu_SetKeys(NH_QINIU_ACCESS_KEY, NH_QINIU_SECRET_KEY);
        $obj_putPolicy = new Qiniu_RS_PutPolicy (NH_QINIU_BUCKET);
        $str_upToken = $obj_putPolicy->Token(null);
        $this->load->helper('string');
        $str_salt = random_string('alnum', 6);
        //course img file name
        $str_new_file_name = 'course_' . date('YmdHis', time()) . '_i' . $str_salt . '.png';


        $this->smarty->assign('action', $str_action);
        $this->smarty->assign('round', $arr_round);
        $this->smarty->assign('teachers', $arr_teachers);
        $this->smarty->assign('classes', $arr_classes);
        $this->smarty->assign('upload_token', $str_upToken);
        $this->smarty->assign('upload_key', $str_new_file_name);
        $this->smarty->assign('view', 'round_edit');
        $this->smarty->assign('subjects', $arr_subjects);
        $this->smarty->assign('course_types', $arr_course_types);
        $this->smarty->assign('grades', config_item('grade'));
        $this->smarty->display('admin/layout.html');
    }

}