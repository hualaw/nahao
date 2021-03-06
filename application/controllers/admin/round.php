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
        $str_sale_status = $this->input->get('sale_status')!='' ? trim($this->input->get('sale_status')) : '' ;
        $int_teach_status = $this->input->get('teach_status') ? intval($this->input->get('teach_status')) : 0 ;
        $int_subject = $this->input->get('subject') ? intval($this->input->get('subject')) : 0 ;
        $str_course_type = $this->input->get('course_type') ? intval($this->input->get('course_type')) : 0 ;
        $str_search_type = $this->input->get('search_type') ? intval($this->input->get('search_type')) : 0 ;
        $str_search_value = $this->input->get('search_value') ? trim($this->input->get('search_value')) : '' ;
//o($this->input->get('sale_status'));
        $arr_where = array();
        if($str_sale_status!==''){
            $int_sale_status = intval($str_sale_status);
            $arr_where['sale_status'] = $int_sale_status;
        }
        if($int_teach_status > 0){
            $arr_where['teach_status'] = $int_teach_status;
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
//                $int_teacher_id = intval($str_search_value);
                $str_teacher_name = trim($str_search_value);
                $arr_where['teacher_name'] = $str_teacher_name;
            }elseif($str_search_type==3){
                $int_round_id = intval($str_search_value);
                $arr_where['id'] = $int_round_id;
            }
        }

        $int_count = $this->round->get_round_count($arr_where);
        $arr_list = $this->round->get_round_list($arr_where, $int_start,PER_PAGE_NO);
//        o($arr_list,true);

//        $this->load->model('business/common/business_subject','subject');
//        $arr_subjects = $this->subject->get_subjects_like_kv();
//        $this->load->model('business/common/business_course_type','course_type');
//        $arr_course_types = $this->course_type->get_course_types_like_kv();

        $arr_education_types = config_item('education_type');
        $arr_subjects = config_item('cate_subject');
        $arr_qualities = config_item('cate_quality');
        $arr_material_versions = config_item('material_version');
        $arr_course_types = config_item('course_type');
        $arr_stages = config_item('stage');
        $arr_education_subjects = config_item('education_subject');
//        o($arr_education_subjects);

        $this->load->library('pagination');
        $config = config_item('page_admin');
        $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
        $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
        $config['total_rows'] = $int_count;
        $config['per_page'] = PER_PAGE_NO;
        $this->pagination->initialize($config);
        parse_str($this->input->server('QUERY_STRING'),$arr_query_param);

//        o($arr_query_param);
//        var_dump(config_item('round_sale_status'));
        $this->smarty->assign('page',$this->pagination->create_links());
        $this->smarty->assign('count',$int_count);
        $this->smarty->assign('list',$arr_list);
        $this->smarty->assign('course_status',config_item('course_status'));
        $this->smarty->assign('round_sale_status',config_item('round_sale_status'));
        $this->smarty->assign('round_teach_status',config_item('round_teach_status'));
        $this->smarty->assign('round_list_search_type',config_item('admin_round_list_search_type'));
        $this->smarty->assign('subjects', $arr_subjects);
        $this->smarty->assign('stages', $arr_stages);
        $this->smarty->assign('qualities', $arr_qualities);
        $this->smarty->assign('education_types', $arr_education_types);
        $this->smarty->assign('material_versions', $arr_material_versions);
        $this->smarty->assign('course_types', $arr_course_types);
        $this->smarty->assign('grades', config_item('grade'));
        $this->smarty->assign('education_subjects', $arr_education_subjects);
        $this->smarty->assign('query_param', $arr_query_param);
        $this->smarty->assign('view', 'round_list');
        $this->smarty->display('admin/layout.html');
    }

    /**
     * TODO 创建轮之前的验证。时间，课节数什么的
     * create/update round
     * @author yanrui@tizi.com
     */
    public function submit()
    {
        if ($this->is_ajax() AND $this->is_post()) {
            header("Content-type: text/html; charset=utf-8");
            $int_round_id = $this->input->post('id') ? intval($this->input->post('id')) : 0;
            $int_course_id = $this->input->post('course_id') ? intval($this->input->post('course_id')) : 0;
            $str_title = $this->input->post('title') ? trim($this->input->post('title')) : '';
            $int_sequence = $this->input->post('sequence') ? intval($this->input->post('sequence')) : 0;
            $int_education_type = $this->input->post('education_type') ? intval($this->input->post('education_type')) : 0;
            $int_material_version = $this->input->post('material_version') ? intval($this->input->post('material_version')) : 0;
            $int_subject = $this->input->post('subject') ? intval($this->input->post('subject')) : 0;
            $int_quality = $this->input->post('quality') ? intval($this->input->post('quality')) : 0;
            $int_course_type = $this->input->post('course_type') ? intval($this->input->post('course_type')) : 0;
            $arr_teachers = $this->input->post('teachers') ? $this->input->post('teachers') : array();
            $int_stage = $this->input->post('stage') ? intval($this->input->post('stage')) : 0;
            $int_grade_from = $this->input->post('grade_from') ? intval($this->input->post('grade_from')) : 0;
            $int_grade_to = $this->input->post('grade_to') ? intval($this->input->post('grade_to')) : 0;
            $float_reward = $this->input->post('reward') ? (filter_var($this->input->post('reward'), FILTER_VALIDATE_FLOAT) ? $this->input->post('reward') : 0) : 0;
            $float_price = $this->input->post('price') ? (filter_var($this->input->post('price'), FILTER_VALIDATE_FLOAT) ? $this->input->post('price') : 0) : 0;
            $float_sale_price = $this->input->post('sale_price') ? (filter_var($this->input->post('sale_price'), FILTER_VALIDATE_FLOAT) ? $this->input->post('sale_price') : 0) : 0;
            $int_caps = $this->input->post('caps') ? intval($this->input->post('caps')) : 0;
            $int_extra_bought_count = $this->input->post('extra_bought_count') ? intval($this->input->post('extra_bought_count')) : 0;
            $str_subtitle = $this->input->post('subtitle') ? trim($this->input->post('subtitle')) : '';
            $str_intro = $this->input->post('intro') ? trim($this->input->post('intro')) : '';
            $str_students = $this->input->post('students') ? trim($this->input->post('students')) : '';
            $str_description = $_REQUEST['description'] ? trim($_REQUEST['description']) : '';
            $str_img = $this->input->post('img') ? trim($this->input->post('img')) : '';
            $int_sell_begin_time = $this->input->post('sell_begin_time') ? strtotime(trim($this->input->post('sell_begin_time'))) : 0;
//            $int_sell_end_time = $this->input->post('sell_end_time') ? strtotime(trim($this->input->post('sell_end_time'))) : 0;
            $str_video = $this->input->post('video') ? trim($this->input->post('video')) : '';
            $int_is_test = $this->input->post('is_test') ? intval($this->input->post('is_test')) : 0;
            $int_is_live = $this->input->post('is_live') ? intval($this->input->post('is_live')) : 0;

//            o($this->input->post(),true);

            if ($int_round_id >= 0
                AND $int_course_id >= 0
                AND $str_title
                AND $int_sequence >= 0
                AND $int_education_type >= 0
                AND $int_material_version >= 0
                AND $int_subject >= 0
                AND $int_quality >= 0
                AND $int_course_type >= 0
                AND $arr_teachers
                AND $int_stage >= 0
                AND $int_grade_from
                AND $int_grade_to /*AND $arr_classes*/
                AND $float_reward >= 0
                AND $float_price >= 0 /*AND $str_video AND $str_img*/
                AND $float_sale_price >= 0
                AND $int_caps
                AND $int_extra_bought_count >= 0
                AND $str_subtitle
                AND $str_intro
                AND $str_students
                AND $str_description
                AND $int_sell_begin_time
//                AND $int_sell_end_time
                AND $int_is_test >=0
                AND $int_is_live >=0 /*AND $int_start_time AND $int_end_time*/) {

                $arr_param['course_id'] = $int_course_id;
                $arr_param['title'] = $str_title;
                $arr_param['sequence'] = $int_sequence;
                $arr_param['education_type'] = $int_education_type;
                $arr_param['material_version'] = $int_material_version;
                $arr_param['subject'] = $int_subject;
                $arr_param['quality'] = $int_quality;
                $arr_param['course_type'] = $int_course_type;
                $arr_param['stage'] = $int_stage;
                $arr_param['grade_from'] = $int_grade_from;
                $arr_param['grade_to'] = $int_grade_to;
                $arr_param['reward'] = $float_reward;
                $arr_param['price'] = $float_price;
                $arr_param['sale_price'] = $float_sale_price;
                $arr_param['caps'] = $int_caps;
                $arr_param['extra_bought_count'] = $int_extra_bought_count;
                $arr_param['subtitle'] = $str_subtitle;
                $arr_param['intro'] = $str_intro;
                $arr_param['students'] = $str_students;
                $arr_param['description'] = $str_description;
                $arr_param['img'] = $str_img;
                $arr_param['video'] = $str_video;
;
                $arr_param['sell_begin_time'] = $int_sell_begin_time;
//                $arr_param['sell_end_time'] = $int_sell_end_time;
                $arr_param['is_test'] = $int_is_test;
                $arr_param['is_live'] = $int_is_live;
//                $arr_param['start_time'] = $int_start_time;
//                $arr_param['end_time'] = $int_end_time;
                $bool_flag = true;
                $str_config_name = ($int_is_test==0 AND in_array(ENVIRONMENT,array('production'))) ?  'production_round_time_config' : 'testing_round_time_config' ;
                $arr_time_config = config_item($str_config_name);
                if ($int_round_id < 1) {
                    if($int_sell_begin_time < TIME_STAMP + $arr_time_config['before_sell_begin_time_min']){
                        $this->arr_response['msg'] = '销售时间要晚于一天后';
                        $bool_flag = false;
                    }elseif($int_sell_begin_time > TIME_STAMP + $arr_time_config['before_sell_begin_time_max']){
                        $this->arr_response['msg'] = '销售时间不能晚于一个月后';
                        $bool_flag = false;
                    }/*elseif($int_sell_end_time < $int_sell_begin_time + $arr_time_config['before_sell_end_time_min']){
                        $this->arr_response['msg'] = '销售结束时间不能早于销售开始时间一天后';
                        $bool_flag = false;
                    }elseif($int_sell_end_time > $int_sell_begin_time + $arr_time_config['before_sell_end_time_max']){
                        $this->arr_response['msg'] = '销售结束时间不能晚于销售开始时间60天后';
                        $bool_flag = false;
                    }*/
                }
//                $arr_param['start_time'] = $int_sell_end_time + $arr_time_config['before_begin_time'];

//                o($arr_param);
//                o($bool_flag,true);

                if($bool_flag==true){
                    $str_action = 'create';
                    if ($int_round_id > 0) {
                        //update
                        $str_action = 'update';
                        $arr_where = array(
                            'id' => $int_round_id
                        );
                        $bool_flag = $this->round->update_round($arr_param, $arr_where);
                    } else {
                        //create
                        $bool_flag = false;
                        $this->load->model('business/admin/business_lesson', 'lesson');
                        $arr_classes = $arr_lessons = $this->lesson->get_lessons_by_course_id($int_course_id);
//                        o($arr_classes,true);
                        foreach($arr_classes as $k => $v){
                            if($v['status']==0){
                                unset($arr_classes[$k]);
                            }
                        }
                        $this->load->model('business/admin/business_course', 'course');
                        $int_class_count = $this->course->get_section_count($arr_classes);
                        if($int_class_count > 0){
                            $arr_param['class_count'] = $int_class_count;
                            $int_round_id = $this->round->create_round($arr_param);
                            $bool_flag = $int_round_id > 0 ? true : false;
                        }else{
                            $this->arr_response['msg'] = '没有启用的章节！';
                        }
                    }

//                o($arr_param);
//                o($bool_flag,true);

                    if ($bool_flag == true) {
                        //create或update都要先清除teachers和classes再重新插入
                        $this->round->create_round_teacher_batch($int_round_id, $arr_teachers);
                        $this->load->model('business/admin/business_class', 'class');
                        if($str_action=='create' AND $arr_classes){
                            $bool_class = $this->class->create_classes($int_course_id, $int_round_id, $arr_classes);
                        }
                        //update is_test
                        $arr_param = array(
                            'is_test' => $int_is_test
                        );
                        $arr_where = array(
                            'round_id' => $int_round_id
                        );
                        $this->class->update_class($arr_param,$arr_where);
                        $this->arr_response['status'] = 'ok';
                        $this->arr_response['msg'] = ($str_action=='create' ? '创建' : '修改').'成功';
                        $this->arr_response['redirect'] = '/round';
                    }
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
//        $int_round_id = $this->input->get('round_id') ? intval($this->input->get('round_id')) : 0;
        $int_round_id = $this->uri->segment(3) ? intval($this->uri->segment(3)) : 0;
        $int_course_id = $this->input->get('course_id') ? intval($this->input->get('course_id')) : 0;
        $arr_course = $arr_lessons = array();
        $str_action = 'create';

        $bool_round_flag = false;
        $str_error = 'param error';
        if ($int_round_id > 0) {
            //update round
            $str_action = 'update';
            $arr_round = $this->round->get_round_by_id($int_round_id);
            $arr_teachers = $this->round->get_teachers_by_round_id($int_round_id);
            $this->load->model('business/admin/business_class', 'class');
//            $arr_classes = $this->class->get_classes_by_round_id($int_round_id);
            $bool_round_flag = true;
        } else {
            //create round
            //course base info
            $this->load->model('business/admin/business_course', 'course');
            $arr_round = $arr_course = $this->course->get_course_by_id($int_course_id);
//            o($arr_round,true);
            //course can generate round while status==NAHAO_STATUS_COURSE_RUNNING
            if($arr_course){
                if($arr_course['status'] == NAHAO_STATUS_COURSE_RUNNING){
                    //course teachers
                    $arr_teachers = $this->course->get_teachers_by_course_id($int_course_id);
                    if($arr_teachers){
                        $bool_round_flag = true;//can generate round
                        $arr_round['is_test'] = ENVIRONMENT=='production' ? 0 : 1;
                        $arr_round['is_live'] = 0;
                    }else{
                        $str_error = '没有老师';
                    }
                }else{
                    $arr_course_status = config_item('course_status');
                    $str_error = '课程状态为'.$arr_course_status[$arr_course['status']].',不能生成轮';
                }
            }else{
                $str_error = '课程不存在';
            }
        }

        //can generate or edit round
        if($bool_round_flag==true){
            //subjects to display
//            $this->load->model('business/common/business_subject', 'subject');
//            $arr_subjects = $this->subject->get_subjects_like_kv();
            //course_types to display
//            $this->load->model('business/common/business_course_type', 'course_type');
//            $arr_course_types = $this->course_type->get_course_types_like_kv();

            $arr_subjects = config_item('cate_subject');
            $arr_qualities = config_item('cate_quality');
            $arr_course_types = config_item('course_type');
            $arr_stages = config_item('stage');
            $arr_education_types = config_item('education_type');
            $arr_material_versions = config_item('material_version');

            //generate param for uploading to qiniu
            require_once APPPATH . 'libraries/qiniu/rs.php';
            require_once APPPATH . 'libraries/qiniu/io.php';
            Qiniu_SetKeys(NH_QINIU_ACCESS_KEY, NH_QINIU_SECRET_KEY);
            $obj_putPolicy = new Qiniu_RS_PutPolicy (NH_QINIU_BUCKET);
            $str_upToken = $obj_putPolicy->Token(null);
            $this->load->helper('string');
            $str_salt = random_string('alnum', 6);
            //course img video file name
            $str_new_img_file_name = 'course_' . date('YmdHis', time()) . '_i' . $str_salt;
            $str_new_video_file_name = 'course_' . date('YmdHis', time()) . '_v' . $str_salt;

            $this->smarty->assign('action', $str_action);
            $this->smarty->assign('round', $arr_round);
            $this->smarty->assign('teachers', $arr_teachers);
//            $this->smarty->assign('classes', $arr_classes);
            $this->smarty->assign('upload_token', $str_upToken);
            $this->smarty->assign('upload_img_key', $str_new_img_file_name);
            $this->smarty->assign('upload_video_key', $str_new_video_file_name);
            $this->smarty->assign('view', 'round_edit');
            $this->smarty->assign('subjects', $arr_subjects);
            $this->smarty->assign('stages', $arr_stages);
            $this->smarty->assign('qualities', $arr_qualities);
            $this->smarty->assign('education_types', $arr_education_types);
            $this->smarty->assign('material_versions', $arr_material_versions);
            $this->smarty->assign('course_types', $arr_course_types);
            $this->smarty->assign('grades', config_item('grade'));
            $this->smarty->display('admin/layout.html');
        }else{
//            die('此课程不能创建轮');
            header("Content-type: text/html; charset=utf-8");
            die($str_error);
        }
    }

    /**
     * 修改sale_status和teach_status
     * @author yanrui@tizi.com
     */
    public function status(){
        $int_round_id = $this->input->post('round_id') ? intval($this->input->post('round_id')) : 0;
        $str_type = $this->input->post('type') ? trim($this->input->post('type')) : '';
        $int_status = $this->input->post('status') ? intval($this->input->post('status')) : 0;
//        o($int_round_id);
//        o($str_type);
//        o($int_status,true);
        if($int_round_id > 0 AND $int_status >= 0 AND $str_type){
            $bool_flag = true;
            //validate
            $this->load->model('business/admin/business_class', 'class');
            $this->load->model('business/admin/business_round', 'round');
            $arr_round = $this->round->get_round_by_id($int_round_id);
            if($str_type=='sale_status'){
                if($int_status==ROUND_SALE_STATUS_PASS){
                    //审核通过
                    $arr_classes = $this->class->get_classes_by_round_id($int_round_id);
//                var_dump($arr_classes);exit;
                    foreach($arr_classes as $class){
                        if($class["parent_id"]>0 AND ($class['begin_time']==0 OR $class['end_time']==0)){
                            $bool_flag = false;
                            $this->arr_response['msg'] = '《'.$class['title'].'》时间不正确，不能通过审核';
                            break;
                        }
                    }
                }else{

                }
            }
            if($bool_flag==true){
                if($str_type=='sale_status'){
                    $arr_param = array(
                        'sale_status' => $int_status
                    );
                }else{
                    $arr_param = array(
                        'teach_status' => $int_status
                    );
                }
                $arr_where = array(
                    'id' => $int_round_id
                );
                $bool_return = $this->round->update_round($arr_param,$arr_where);
                if($bool_return==true){
                    //update class
                    $str_config_name = ($arr_round['is_test']==0 AND in_array(ENVIRONMENT,array('production'))) ?  'production_round_time_config' : 'testing_round_time_config' ;
                    $arr_time_config = config_item($str_config_name);
                    if($str_type=='sale_status'){

                    }else{
                        if($int_status==ROUND_TEACH_STATUS_STOP){
                            $arr_param_class = array(
                                'status' =>  CLASS_STATUS_FORI_CLASS
                            );
                            $arr_where_class = array(
                                'begin_time >' => TIME_STAMP-$arr_time_config['enter_before_class'],
                                'round_id' => $int_round_id
                            );
                            $this->class->update_class($arr_param_class,$arr_where_class);
                        }
                    }
                    $this->arr_response['status'] = 'ok';
                    $this->arr_response['msg'] = '操作成功';
                }
            }
        }
        self::json_output($this->arr_response);
    }

}