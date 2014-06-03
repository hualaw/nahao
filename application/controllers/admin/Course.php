<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 课程管理
 * Class Course
 * @author yanrui@tizi.com
 */
class Course extends NH_Admin_Controller {

    private $arr_response = array(
        'status' => 'error',
        'msg' => '操作失败',
    );

    function __construct(){
        parent::__construct();
    }

    /**
     * course index
     * @author yanrui@tizi.com
     */
    public function index(){
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
                $int_course_id = intval($str_search_value);
                $arr_where['id'] = $int_course_id;
            }
        }

        $int_count = $this->course->get_course_count($arr_where);
        $arr_list = $this->course->get_course_list($arr_where, $int_start,PER_PAGE_NO);

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
        $this->smarty->assign('subjects',$arr_subjects);
        $this->smarty->assign('course_types',$arr_course_types);
        $this->smarty->assign('arr_query_param', $arr_query_param);
        $this->smarty->assign('view', 'course_list');
        $this->smarty->display('admin/layout.html');
    }

    /**
     * create course
     * @author yanrui@tizi.com
     */
    public function create(){
        if($this->is_ajax() AND $this->is_post()){
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
            $arr_lessons = $this->input->post('lessons') ? $this->input->post('lessons') : array();
            $arr_teachers = $this->input->post('teachers') ? $this->input->post('teachers') : array();
            o($this->input->post(),true);

            if($str_title AND $str_subtitle AND $str_intro AND $str_description AND $str_students AND $int_subject AND $int_course_type AND $int_reward AND $int_price AND $str_video AND $str_img AND $int_grade_from AND $int_grade_to AND $arr_lessons and $arr_teachers){
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
                $int_course_id = $this->course->create_course($arr_param);
                if($int_course_id > 0){

                    $this->load->model('business/common/business_lesson','lesson');
                    $int_lesson_id = $this->lesson->create_lesson();


                    $int_lesson_id = $this->lesson->create_lesson_teacher_realtion();

                    $this->arr_response['status'] = 'ok';
                    $this->arr_response['msg'] = '创建成功';
                }
            }
        }
        self::json_output($this->arr_response);
    }


    /**
     * update course
     * @author yanrui@tizi.com
     */
    public function update(){
        if($this->is_ajax() AND $this->is_post()){
            $int_course_id = $this->input->post('id') ? intval($this->input->post('id')) : 0;
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

            $arr_teachers = $this->input->post('teachers') ? $this->input->post('teachers') : 0;

            if($int_course_id AND $str_title AND $str_subtitle AND $str_intro AND $str_description AND $str_students AND $int_subject AND $int_course_type AND $int_reward AND $int_price AND $str_video AND $str_img AND $int_grade_from AND $int_grade_to){
                $arr_where['id'] = $int_course_id;
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
                $bool_flag = $this->course->update_course($arr_param,$arr_where);
                if($bool_flag==true){
                    $this->arr_response['status'] = 'ok';
                    $this->arr_response['msg'] = '创建成功';
                }
            }
        }
        self::json_output($this->arr_response);
    }


    /**
     * edit course
     * @author yanrui@tizi.com
     */
    public function edit(){
        $int_course_id = $this->input->get('id') ? intval($this->input->get('id')) : 0;
        if($int_course_id){

        }

        $this->load->model('business/common/business_subject','subject');
        $arr_subjects = $this->subject->get_subjects_like_kv();
        $this->load->model('business/common/business_course_type','course_type');
        $arr_course_types = $this->course_type->get_course_types_like_kv();

        $this->smarty->assign('view', 'course_edit');
        $this->smarty->assign('subjects',$arr_subjects);
        $this->smarty->assign('course_types',$arr_course_types);
        $this->smarty->assign('grades',config_item('grade'));
        $this->smarty->display('admin/layout.html');
    }

    /**
     * 添加课程时选取教师列表
     * @author yanrui@tizi.com
     */
    public function teachers(){
        $arr_return = array();
        if($this->is_ajax()){
            $this->load->model('business/admin/business_teacher','teacher');
            $arr_return = $this->teacher->get_teacher_list(array(),0,20);
        }
        self::json_output($arr_return);
    }

    public function upload(){
        self::json_output(array('id'=>1,'status'=>999));
    }
}