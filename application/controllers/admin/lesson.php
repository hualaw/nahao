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
//        $this->load->model('business/admin/business_lesson','lesson');
        if($int_course_id > 0){
            $arr_lessons = $this->lesson->get_lessons_by_course_id($int_course_id);
            o($arr_lessons,true);
        }
        $this->smarty->assign('lessons',$arr_lessons);
        $this->smarty->assign('view', 'lesson_list');
        $this->smarty->display('admin/layout.html');
    }

//self::json_output($this->arr_response);
}