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

    public function get_token(){
        $arr_token = array(
            'token' => 'c3f3d35c19e844a88b7c1899ad1f3c22'
        );
//        $str_signature = get_meeting_signature();
//        $str = '?nonce='.TIME_STAMP.'&signature='.$str_signature.'&app_key='.NH_MEETING_ACCESS_KEY;
//        o($str,true);
        self::json_output($arr_token);
    }
}