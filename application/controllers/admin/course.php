<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 课程管理
 * Class Course
 * @author yanrui@tizi.com
 */
class Course extends NH_Admin_Controller
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
     * course index
     * @author yanrui@tizi.com
     */
    public function index()
    {
        $int_start = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        $int_status = $this->input->get('status') ? intval($this->input->get('status')) : 0;
        $int_subject = $this->input->get('subject') ? intval($this->input->get('subject')) : 0;
        $str_course_type = $this->input->get('course_type') ? intval($this->input->get('course_type')) : 0;
        $str_search_type = $this->input->get('search_type') ? intval($this->input->get('search_type')) : 0;
        $str_search_value = $this->input->get('search_value') ? trim($this->input->get('search_value')) : '';

        $arr_where = array();
        if ($int_status > 0) {
            $arr_where['status'] = --$int_status;
        }
        if ($int_subject > 0) {
            $arr_where['subject'] = $int_subject;
        }
        if ($str_course_type) {
            $arr_where['course_type'] = $str_course_type;
        }
        if ($str_search_type > 0 AND $str_search_value != '') {
            if ($str_search_type == 1) {
                $arr_where['title'] = $str_search_value;
            } elseif ($str_search_type == 2) {
                $int_teacher_id = intval($str_search_value);
                $arr_where['teacher_id'] = $int_teacher_id;
            } elseif ($str_search_type == 3) {
                $int_course_id = intval($str_search_value);
                $arr_where['id'] = $int_course_id;
            }
        }

        $int_count = $this->course->get_course_count($arr_where);
        $arr_list = $this->course->get_course_list($arr_where, $int_start, PER_PAGE_NO);

//        $this->load->model('business/common/business_subject','subject');
//        $arr_subjects = $this->subject->get_subjects_like_kv();
        $arr_subjects = config_item('cate_subject');
        $arr_qualities = config_item('cate_quality');
        $arr_course_types = config_item('course_type');
        $arr_stages = config_item('stage');
        $arr_education_types = config_item('education_type');
        $arr_material_versions = config_item('material_version');
        $arr_education_subjects = config_item('education_subject');
//        $this->load->model('business/common/business_course_type', 'course_type');
//        $arr_course_types = $this->course_type->get_course_types_like_kv();
//        o($arr_subjects);
        $this->load->library('pagination');
        $config = config_item('page_admin');
        $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
        $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
        $config['total_rows'] = $int_count;
        $config['per_page'] = PER_PAGE_NO;
        $this->pagination->initialize($config);
        parse_str($this->input->server('QUERY_STRING'), $arr_query_param);

//        o($arr_list,true);
        $this->smarty->assign('page', $this->pagination->create_links());
        $this->smarty->assign('count', $int_count);
        $this->smarty->assign('list', $arr_list);
        $this->smarty->assign('course_status', config_item('course_status'));
        $this->smarty->assign('course_list_search_type', config_item('admin_course_list_search_type'));
        $this->smarty->assign('subjects', $arr_subjects);
        $this->smarty->assign('course_types', $arr_course_types);
        $this->smarty->assign('education_types', $arr_education_types);
        $this->smarty->assign('qualities', $arr_qualities);
        $this->smarty->assign('stages', $arr_stages);
        $this->smarty->assign('material_version', $arr_material_versions);
        $this->smarty->assign('education_subjects', $arr_education_subjects);
        $this->smarty->assign('query_param', $arr_query_param);

        $this->smarty->assign('view', 'course_list');
        $this->smarty->display('admin/layout.html');
    }

    /**
     * create/update course
     * @author yanrui@tizi.com
     */
    public function submit()
    {
        if ($this->is_ajax() AND $this->is_post()) {
            header("Content-type: text/html; charset=utf-8");
            $int_course_id = $this->input->post('id') ? intval($this->input->post('id')) : 0;
            $str_title = $this->input->post('title') ? trim($this->input->post('title')) : '';
            $int_education_type = $this->input->post('education_type') ? intval($this->input->post('education_type')) : 0;
            $int_material_version = $this->input->post('material_version') ? intval($this->input->post('material_version')) : 0;
            $int_subject = $this->input->post('subject') ? intval($this->input->post('subject')) : 0;
            $int_quality = $this->input->post('quality') ? intval($this->input->post('quality')) : 0;
            $int_course_type = $this->input->post('course_type') ? intval($this->input->post('course_type')) : 0;
            $arr_teachers = $this->input->post('teachers') ? $this->input->post('teachers') : array();
            $int_stage = $this->input->post('stage') ? intval($this->input->post('stage')) : 0;
            $int_grade_from = $this->input->post('grade_from') ? intval($this->input->post('grade_from')) : 0;
            $int_grade_to = $this->input->post('grade_to') ? intval($this->input->post('grade_to')) : 0;
            $int_reward = $this->input->post('reward') ? (filter_var($this->input->post('reward'), FILTER_VALIDATE_FLOAT) ? $this->input->post('reward') : 0) : 0;
            $int_price = $this->input->post('price') ? (filter_var($this->input->post('price'), FILTER_VALIDATE_FLOAT) ? $this->input->post('price') : 0) : 0;
            $str_subtitle = $this->input->post('subtitle') ? trim($this->input->post('subtitle')) : '';
            $str_intro = $this->input->post('intro') ? trim($this->input->post('intro')) : '';
            $str_students = $this->input->post('students') ? trim($this->input->post('students')) : '';
            $str_description = $_REQUEST['description'] ? trim($_REQUEST['description']) : '';
            $str_img = $this->input->post('img') ? trim($this->input->post('img')) : '';
            $str_video = $this->input->post('video') ? trim($this->input->post('video')) : '';

            $bool_validate_flag = true;
            if (empty($str_title)) {
                $bool_validate_flag = false;
                $this->arr_response['msg'] = '没有标题';
            } elseif ($int_education_type < 0) {
                $bool_validate_flag = false;
                $this->arr_response['msg'] = '没有教育类型';
            }  elseif ($int_material_version < 0) {
                $bool_validate_flag = false;
                $this->arr_response['msg'] = '没有教材版本';
            } elseif ($int_subject < 0) {
                $bool_validate_flag = false;
                $this->arr_response['msg'] = '没有科目';
            } elseif ($int_quality < 0) {
                $bool_validate_flag = false;
                $this->arr_response['msg'] = '没有素质教育科目';
            }elseif ($int_course_type < 0) {
                $bool_validate_flag = false;
                $this->arr_response['msg'] = '没有课程类型';
            } elseif (empty($arr_teachers)) {
                $bool_validate_flag = false;
                $this->arr_response['msg'] = '没有老师';
            } elseif ($int_stage < 0) {
                $bool_validate_flag = false;
                $this->arr_response['msg'] = '没有学段';
            } elseif ($int_grade_from < 0) {
                $bool_validate_flag = false;
                $this->arr_response['msg'] = '没有开始年级';
            } elseif ($int_grade_to < 0) {
                $bool_validate_flag = false;
                $this->arr_response['msg'] = '没有结束年级';
            }elseif ($int_reward < 0) {
                $bool_validate_flag = false;
                $this->arr_response['msg'] = '没有课酬';
            } elseif ($int_price < 0) {
                $bool_validate_flag = false;
                $this->arr_response['msg'] = '没有价格';
            }elseif (empty($str_subtitle)) {
                $bool_validate_flag = false;
                $this->arr_response['msg'] = '没有一句话简介';
            } elseif (empty($str_intro)) {
                $bool_validate_flag = false;
                $this->arr_response['msg'] = '没有简介';
            } elseif (empty($str_students)) {
                $bool_validate_flag = false;
                $this->arr_response['msg'] = '没有适合人群';
            }elseif (empty($str_description)) {
                $bool_validate_flag = false;
                $this->arr_response['msg'] = '没有授课提要';
            }
            if ($bool_validate_flag == true) {
                $arr_param['title'] = $str_title;
                $arr_param['education_type'] = $int_education_type;
                $arr_param['material_version'] = $int_material_version;
                $arr_param['subject'] = $int_subject;
                $arr_param['quality'] = $int_quality;
                $arr_param['course_type'] = $int_course_type;
                $arr_param['stage'] = $int_stage;
                $arr_param['grade_from'] = $int_grade_from;
                $arr_param['grade_to'] = $int_grade_to;
                $arr_param['reward'] = $int_reward;
                $arr_param['price'] = $int_price;
                $arr_param['subtitle'] = $str_subtitle;
                $arr_param['intro'] = $str_intro;
                $arr_param['students'] = $str_students;
                $arr_param['description'] = $str_description;
                $arr_param['img'] = $str_img;
                $arr_param['video'] = $str_video;
//                o($arr_param,true);
                if ($int_course_id > 0) {
                    //update
                    $str_action = '修改';
                    $arr_where = array(
                        'id' => $int_course_id
                    );
                    $bool_flag = $this->course->update_course($arr_param, $arr_where);
                } else {
                    //create
                    $str_action = '创建';
                    $int_course_id = $this->course->create_course($arr_param);
                    $bool_flag = $int_course_id > 0 ? true : false;
                }
                if ($bool_flag == true) {
                    //create或update都要先清除teachers再重新插入
                    $this->course->create_course_teacher_batch($int_course_id, $arr_teachers);
                    $this->arr_response['status'] = 'ok';
                    $this->arr_response['msg'] = $str_action . '成功';
                    $this->arr_response['redirect'] = '/course';
                }
            }
        }
        self::json_output($this->arr_response);
    }

    /**
     * edit course interface
     * @author yanrui@tizi.com
     */
    public function edit()
    {
        $int_course_id = $this->uri->rsegment(3) ? intval($this->uri->rsegment(3)) : 0;
        $arr_course = $arr_teachers = $arr_lessons = array();
        $str_action = 'create';
        //update
        if ($int_course_id) {
            $str_action = 'update';
            //course base info
            $arr_course = $this->course->get_course_by_id($int_course_id);
            //course teachers
            $arr_teachers = $this->course->get_teachers_by_course_id($int_course_id);
//            o($this->db->last_query());
//            o($arr_teachers,true);
//            $this->load->model('business/admin/business_lesson','lesson');
            //course lessons
//            $arr_lessons = $this->lesson->get_lessons_by_course_id($int_course_id);
        }

        //create
        //subjects
//        $this->load->model('business/common/business_subject','subject');
//        $arr_subjects = $this->subject->get_subjects_like_kv();
        //course_types
//        $this->load->model('business/common/business_course_type','course_type');
//        $arr_course_types = $this->course_type->get_course_types_like_kv();
        $arr_subjects = config_item('cate_subject');
        $arr_qualities = config_item('cate_quality');
        $arr_course_types = config_item('course_type');
        $arr_stages = config_item('stage');
        $arr_education_types = config_item('education_type');
        $arr_material_versions = config_item('material_version');
//        o($arr_material_versions);

        //generate param for uploading to qiniu
        require_once APPPATH . 'libraries/qiniu/rs.php';
        require_once APPPATH . 'libraries/qiniu/io.php';
        Qiniu_SetKeys(NH_QINIU_ACCESS_KEY, NH_QINIU_SECRET_KEY);
        $obj_putPolicy = new Qiniu_RS_PutPolicy (NH_QINIU_BUCKET);
        $str_upToken = $obj_putPolicy->Token(null);
        $obj_putPolicy = new Qiniu_RS_PutPolicy (NH_QINIU_VIDEO_BUCKET);
        $str_video_upToken = $obj_putPolicy->Token(null);

        $this->load->helper('string');
        $str_salt = random_string('alnum', 6);
        //course img file name
        $str_new_img_file_name = 'course_' . date('YmdHis', time()) . '_i' . $str_salt . '.png';
        $str_new_video_file_name = 'course_' . date('YmdHis', time()) . '_v' . $str_salt . '.mp4';

        $this->smarty->assign('action', $str_action);
        $this->smarty->assign('course', $arr_course);
        $this->smarty->assign('teachers', $arr_teachers);
        $this->smarty->assign('stages', $arr_stages);
        $this->smarty->assign('upload_token', $str_upToken);
        $this->smarty->assign('upload_video_token', $str_video_upToken);
        $this->smarty->assign('upload_img_key', $str_new_img_file_name);
        $this->smarty->assign('upload_video_key', $str_new_video_file_name);
        $this->smarty->assign('view', 'course_edit');
        $this->smarty->assign('subjects', $arr_subjects);
        $this->smarty->assign('qualities', $arr_qualities);
        $this->smarty->assign('education_types', $arr_education_types);
        $this->smarty->assign('material_versions', $arr_material_versions);
        $this->smarty->assign('course_types', $arr_course_types);
        $this->smarty->assign('grades', config_item('grade'));
        $this->smarty->display('admin/layout.html');
    }

    /**
     * ckeditor中传图
     * @author yanrui@tizi.com
     */
    public function upload()
    {
        //uploads是相对于index.php的 最后尝试页面直接ajax给七牛
        $config['upload_path'] = '../uploads/';
        $config['allowed_types'] = 'gif|jpg|png'; //最后这个选项可能会有安全隐患问题
//        $config['max_size'] = '2000';
//        $config['max_width']  = '1024';
//        $config['max_height']  = '768';

        $this->load->helper(array('form', 'url'));
        $this->load->library('upload', $config);


        if ($this->upload->do_upload('upload')) {
            $data = array('upload_data' => $this->upload->data());
            $str_img_url = $data['upload_data']['full_path'];
        } else {
            $error = array('error' => $this->upload->display_errors());
        }
//
        $str_file_ext = pathinfo($str_img_url, PATHINFO_EXTENSION);
        /***********七牛 start***********/
        require_once APPPATH . 'libraries/qiniu/rs.php';
        require_once APPPATH . 'libraries/qiniu/io.php';
        Qiniu_SetKeys(NH_QINIU_ACCESS_KEY, NH_QINIU_SECRET_KEY);
        $this->obj_qiniu = new Qiniu_MacHttpClient (null);
        $putPolicy = new Qiniu_RS_PutPolicy (NH_QINIU_BUCKET);
        $upToken = $putPolicy->Token(null);
        $putExtra = new Qiniu_PutExtra ();
        $putExtra->Crc32 = 1;
        $this->load->helper('string');
        $str_salt = random_string('alnum', 6);
        //course img file name
        $str_new_file_name = 'course_description_' . date('YmdHis', time()) . '_i' . $str_salt;
        list ($ret, $err) = Qiniu_PutFile($upToken, $str_new_file_name, $str_img_url, $putExtra);
        if ($err !== null) {
            log_message('record', 'qiniu do_upload $err' . var_export($err, true));
        } else {
            log_message('record', 'qiniu do_upload $ret' . var_export($ret, true));
        }
        $str_final_url = NH_QINIU_URL . $str_new_file_name;
        /***********七牛 end***********/

//        $str_img_url = 'http://n1a2h3a4o5.qiniudn.com/course_20140611112154_iANZ8Sy.png?imageView/1/w/290/h/216';
        $str_img_url = str_replace('1',nahao_hash($str_new_file_name,4),NH_QINIU_URL) . $str_new_file_name . '/c.720.jpg';
        $str_html = '<html>
        <body>
        <script type="text/javascript">
        window.parent.CKEDITOR.tools.callFunction(' . trim($this->input->get('CKEditorFuncNum')) . ', "' . $str_img_url . '","");
        </script>
        </body>
        </html>';
        echo $str_html;
        exit;
    }


    /**
     * 修改课程状态
     * @author yanrui@tizi.com
     */
    public function update()
    {
        $int_course_id = $this->input->post('course_id') ? intval($this->input->post('course_id')) : 0;
        $int_status = $this->input->post('status') ? intval($this->input->post('status')) : 0;
        if ($int_course_id > 0 AND $int_status >= 0) {
            $arr_param = array(
                'status' => $int_status
            );
            $arr_where = array(
                'id' => $int_course_id
            );
            $bool_return = $this->course->update_course($arr_param, $arr_where);
            if ($bool_return == true) {
                $this->arr_response['status'] = 'ok';
                $this->arr_response['msg'] = '操作成功';
            }
        }
        self::json_output($this->arr_response);
    }

    /**
     * get token for reupload
     * @author yanrui@tizi.com
     */
    public function get_token()
    {

        //generate param for uploading to qiniu
        require_once APPPATH . 'libraries/qiniu/rs.php';
        require_once APPPATH . 'libraries/qiniu/io.php';
        Qiniu_SetKeys(NH_QINIU_ACCESS_KEY, NH_QINIU_SECRET_KEY);
        $obj_putPolicy = new Qiniu_RS_PutPolicy (NH_QINIU_BUCKET);
        $str_upToken = $obj_putPolicy->Token(null);
        $this->load->helper('string');
        $str_salt = random_string('alnum', 6);
        //course img file name
        $str_new_img_file_name = 'course_' . date('YmdHis', time()) . '_i' . $str_salt . '.png';
//        $str_new_video_file_name = 'course_'.date('YmdHis',time()).'_v'.$str_salt.'.png';

        $arr_response = array(
            'status' => 'ok',
            'data' => array(
                'token' => $str_upToken,
                'img_file_name' => $str_new_img_file_name
            )
        );
        self::json_output($arr_response);
    }

}