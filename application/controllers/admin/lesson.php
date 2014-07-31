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

//            o($arr_lessons);
//            echo '======';
            $arr_lessons = $this->lesson->get_lessons_list_show($arr_lessons);
//            o($arr_lessons,true);
            $int_chapter_count = $int_section_count = 0 ;
            if($arr_lessons){
                foreach($arr_lessons as $lesson){
                    if($lesson['parent_id'] == 0){
                        ++$int_chapter_count;
                    }else{
                        ++$int_section_count;
                    }
                }
            }
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
        $int_create_time = $this->input->post('create_time') ? strtotime($this->input->post('create_time')) : 0;
        $str_filename = $this->input->post('filename') ? trim($this->input->post('filename')) : '';
        $int_filesize = $this->input->post('filesize') ? intval($this->input->post('filesize')) : 0;
        $int_filetype = $this->input->post('filetype') ? intval($this->input->post('filetype')) : 0;

        if($int_lesson_id > 0 AND $int_courseware_id > 0 AND $int_create_time > 0 AND $str_filename){
            $arr_courseware = array(
                'id' => $int_courseware_id,
                'create_time' => $int_create_time,
                'name' => $str_filename,
                'filesize' => $int_filesize,
                'filetype' => $int_filetype,
            );
            $bool_return = $this->lesson->add_courseware($int_lesson_id,$arr_courseware);
            if($bool_return==true){
                $this->arr_response['status'] = 'ok';
                $this->arr_response['msg'] = '添加成功';
            }
        }
        self::json_output($this->arr_response);
    }

    /**
     * create/update lesson
     * @author yanrui@tizi.com
     */
    public function submit(){
        $str_action = $this->input->post('action') ? trim($this->input->post('action')) : '';
        $arr_response = array(
            'status' => 'error',
            'msg' => '操作失败',
        );
        if(in_array($str_action,array('create','update'))){
            $int_course_id = $this->input->post('course_id') ? intval($this->input->post('course_id')) : 0;
            $int_lesson_id = $this->input->post('lesson_id') ? intval($this->input->post('lesson_id')) : 0;
            $str_lesson_name = $this->input->post('lesson_name') ? trim($this->input->post('lesson_name')) : '';
            $int_lesson_is_chapter = $this->input->post('lesson_is_chapter') ? intval($this->input->post('lesson_is_chapter')) : 0;
            if($str_lesson_name AND $int_lesson_is_chapter >= 0){
                $arr_param = array(
                    'title' => $str_lesson_name,
                );
                $arr_param['parent_id'] = $int_lesson_is_chapter==1 ? 0 : 1;
                if($int_lesson_id){
                    //update
                    $arr_where = array(
                        'id' => $int_lesson_id
                    );
//                    o($arr_param);
//                    o($arr_where,true);
                    $mix_return = $this->lesson->update_lesson($arr_param,$arr_where);
                }else{
                    //create
//                    o($arr_param,true);
                    $arr_param['course_id'] = $int_course_id;
                    $mix_return = $this->lesson->create_lesson($arr_param);
                }
                if($mix_return){
                    $arr_response = array(
                        'status' => 'ok',
                        'msg' => '操作成功',
                    );
                    if($str_action=='create'){
                        $arr_response['id'] = $mix_return;
                    }
                }
            }
        }
        self::json_output($arr_response);
    }

    /**
     * sort lessons
     * @author yanrui@tizi.com
     */
    public function sort(){
        $int_course_id = $this->input->post('course_id') ? $this->input->post('course_id') : 0;
        $arr_lessons = $this->input->post('lessons') ? $this->input->post('lessons') : array();
        $arr_response = array(
            'status' => 'error',
            'msg' => '操作失败',
        );
        if($int_course_id AND is_array($arr_lessons) AND $arr_lessons){
            $arr_update = array();
            $int_parent_id = 1;
            $int_chapter_sequence = $int_section_sequence = $int_section_count = 0;
//            o($arr_lessons,true);
            foreach($arr_lessons as $k => $v){
                if($v['is_chapter']==1){
                    $int_parent_id = $int_section_sequence = 0;
                    $arr_tmp = array(
                        'id' => $v['id'],
                        'parent_id' => $int_parent_id,
                        'sequence' => $int_chapter_sequence++
                    );
                    $int_parent_id = $v['id'];
                }else{
                    $arr_tmp = array(
                        'id' => $v['id'],
                        'parent_id' => $int_parent_id,
                        'sequence' => $int_section_sequence++
                    );
                    if($v['status']==1){
                        ++$int_section_count;
                    }
                }
                $arr_update[] = $arr_tmp;
            }

//            o($int_section_count,true);
//            $arr_lessons_tree = $this->lesson->get_lessons_list($arr_lessons);
//            o($arr_lessons_tree,true);
            $bool_return = $this->lesson->sort($arr_update);
            $arr_param = array(
                'lesson_count' => $int_section_count
            );
            $arr_where = array(
                'id' => $int_course_id
            );
            $this->load->model("business/admin/business_course",'course');
            $this->course->update_course($arr_param,$arr_where);
//            o($bool_return);
//            o($arr_lessons_tree,true);
            if($bool_return){
                $arr_response = array(
                    'status' => 'ok',
                    'msg' => '操作成功',
                );
            }
        }
        self::json_output($arr_response);
    }

    /**
     * get_chapters_by_course_id
     * @return array
     * @author yanrui@tizi.com
     */
//    public function chapters(){
//        $arr_return['data'] = array(
//            array(
//                'id' => 0,
//                'title' => '是章，或者选择下面章'
//            ),
//        );
//        $int_course_id = $this->uri->rsegment(3) ? intval($this->uri->rsegment(3)) : 0;
//        if($int_course_id){
//            $arr_chapters = $this->lesson->get_chapters_by_course_id($int_course_id);
//            if(!$arr_chapters){
//                $arr_chapters[] = array(
//                    'id' => 1,
//                    'title' => '默认章'
//                );
//            }
//            $arr_return['data'] = array_merge($arr_return['data'],$arr_chapters);
//        }
//        self::json_output($arr_return);
//    }

    /**
     * 预览pdf
     * @author yanrui@tizi.com
     */
    public function preview(){
        $int_courseware_id = $this->uri->rsegment(3) ? $this->uri->rsegment(3) : 0;
        if($int_courseware_id){
            $arr_courseware_status = get_courseware_status($int_courseware_id);
//            o($arr_courseware_status);
            if($arr_courseware_status['status']==true){
//                o($arr_courseware,true);
                $arr_courseware = get_courseware_info($int_courseware_id);
                $this->smarty->assign('coruseware_id', $arr_courseware['id']);
                $this->smarty->assign('pagenum', $arr_courseware['pagenum']);
                $this->smarty->assign('swfpath', $arr_courseware['swfpath']);
                $this->smarty->assign('view', 'preview');
                $this->smarty->display('admin/layout.html');
            }else{
                header("Content-type: text/html; charset=utf-8");
                die("还没有转换完成，请稍后再试");
            }
        }
    }

    public function delete(){
        $int_lesson_id = $this->input->post('lesson_id') ? $this->input->post('lesson_id') : 0;
        $arr_response = array(
            'status' => 'error',
            'msg' => '删除失败',
        );
        if($int_lesson_id > 0){
            $arr_where = array(
                'id' => $int_lesson_id
            );
            $return = $this->lesson->delete_lesson($arr_where);
            if($return){
                $arr_response = array(
                    'status' => 'ok',
                    'msg' => '删除成功',
                );
            }
        }
        self::json_output($arr_response);
    }

    /**
     * 章节禁用启用
     * @author yanrui@tizi.com
     */
    public function active(){
        if($this->is_ajax() AND $this->is_post()){
            $int_lesson_id = intval($this->input->post('lesson_id'));
            $int_status = intval($this->input->post('status'));
//            o($int_lesson_id,true);
//            o($int_status,true);
            if($int_lesson_id > 1 AND in_array($int_status,array(0,1))){
                $arr_param = array(
                    'status' => $int_status
                );
                $arr_where = array(
                    'id' => $int_lesson_id
                );
                $bool_return = $this->lesson->update_lesson($arr_param,$arr_where);
                if($bool_return > 0){
                    $this->arr_response['status'] = 'ok';
                    $this->arr_response['msg'] = '修改成功';
                }
            }
        }
        self::json_output($this->arr_response);
    }
}