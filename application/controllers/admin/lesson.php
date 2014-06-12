<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 课节管理
 * Class Lesson
 * @author yanrui@tizi.com
 */
class Lesson extends NH_Admin_Controller {

    private $arr_response = array(
        'status' => 'error',
        'msg' => '操作失败',
        'redirect' => '/lesson'
    );

    /**
     * lesson list
     * @author yanrui@tizi.com
     */
    public function index () {
        $int_course_id = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        $arr_lesson = array();
        if($int_course_id > 0){
            $this->load->model('business/admin/business_course','course');
            $arr_course = $this->course->get_course_by_id($int_course_id);
            $arr_lessons = $this->lesson->get_lessons_by_course_id($int_course_id);
            $int_chapter_count = $int_section_count = 0 ;
            foreach($arr_lessons as $lesson){
                if($lesson['parent_id'] == 0){
                    ++$int_chapter_count;
                }else{
                    ++$int_section_count;
                }
            }
//            o($arr_course,true);
        }
        $this->smarty->assign('course',$arr_course);
        $this->smarty->assign('lessons',$arr_lessons);
        $this->smarty->assign('chapter_count',$int_chapter_count);
        $this->smarty->assign('section_count',$int_section_count);
        $this->smarty->assign('view', 'lesson_list');
        $this->smarty->display('admin/layout.html');
    }

//self::json_output($this->arr_response);

    public function token(){
//        $str_signature = get_meeting_signature();
//        $str = '?nonce='.TIME_STAMP.'&signature='.$str_signature.'&app_key='.NH_MEETING_ACCESS_KEY;
//        o($str,true);
        $arr_token = array(
            'token' => get_meeting_token()
        );
        self::json_output($arr_token);
    }

    /**
     * add / update courseware to lesson
     * @author yanrui@tizi.com
     */
    public function add_courseware(){
        $int_lesson_id = $this->input->post('lesson_id') ? intval($this->input->post('lesson_id')) : 0;
        $int_courseware_id = $this->input->post('courseware_id') ? intval($this->input->post('courseware_id')) : 0;
//        o($int_lesson_id);
//        o($int_courseware_id,true);
        if($int_lesson_id > 0 AND $int_courseware_id > 0){
            $bool_return = $this->lesson->add_courseware($int_lesson_id,$int_courseware_id);
            if($bool_return==true){
                $this->arr_response['status'] = 'ok';
                $this->arr_response['msg'] = '添加成功';
                $this->arr_response['redirect'] = '/lesson/index/'.$int_lesson_id;
            }
        }
        self::json_output($this->arr_response);
    }
}