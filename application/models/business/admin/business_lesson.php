<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Lesson相关逻辑
 * Class Business_Lesson
 * @author yanrui@tizi.com
 */
class Business_Lesson extends NH_Model
{
    function __construct(){
        parent::__construct();
        $this->load->model('model/admin/model_lesson');
    }

    /**
     * 创建lessons
     * @param $int_course_id
     * @param $arr_lessons
     * @return bool
     * @author yanrui@tizi.com
     */
    public function create_lessons($int_course_id,$arr_lessons)
    {
        $bool_return = false;
        if($int_course_id > 0 AND is_array($arr_lessons) AND $arr_lessons){
//            o($arr_lessons,true);
            $arr_lesson_tree = self::get_lesson_tree($arr_lessons);
//            o($arr_lesson_tree,true);
            if($arr_lesson_tree){
                //先清除该课程以前的课节，再插入新的课节
                self::delete_lessons_by_course_id($int_course_id);

                $int_sequence_flag = $int_chapter_flag = $int_section_flag = 0;
                foreach($arr_lesson_tree as $k => $v){
                    $arr_chapter = array(
                        'course_id' => $int_course_id,
                        'title' => $v['title'],
                        'sequence' => $int_chapter_flag++
                    );
                    //插入章
                    $int_parent_id = $this->model_lesson->create_lesson($arr_chapter);
                    if($int_parent_id > 0){
                        foreach($v['lessons'] as $kk => $vv){
                            $arr_section[] = array(
                                'course_id' => $int_course_id,
                                'title' => $vv,
                                'parent_id' => $int_parent_id,
                                'sequence' => $int_section_flag++
                            );
                        }
                        //插入节
                        $int_last_id = $this->model_lesson->create_lesson_batch($arr_section);
                        if($int_last_id > 0){
                            if($k == count($arr_lesson_tree)-1){
//                                echo $k.'-'.count($arr_lesson_tree);
                                $bool_return = true;
                            }
                        }else{
                            break;
                        }
                        unset($arr_section);
                    }else{
                        break;
                    }
                }
            }
        }
        return $bool_return;
    }

    /**
     * 把提交过来的原始的lesson数据组合成章节树形结构
     * @param $arr_lessons
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_lesson_tree($arr_lessons){
        $arr_return = array();
        if($arr_lessons){
            $int_flag = 0 ;
            foreach($arr_lessons as $k => $v){
                if($v['is_chapter']==1){
                    $int_flag ++;
                    $arr_return[$int_flag]['title'] = $v['name'];
                }else{
                    $arr_return[$int_flag]['lessons'][] = $v['name'];
                }
            }
        }
        return $arr_return;
    }

    /**
     * 根据course_id删除lesson
     * @param $int_course_id
     * @author yanrui@tizi.com
     */
    public function delete_lessons_by_course_id($int_course_id){
        if($int_course_id > 0){
            $arr_where = array(
                'course_id' => $int_course_id
            );
            $this->model_lesson->delete_lesson_by_param($arr_where);
        }
    }

    /**
     * 修改lesson
     * @param array $arr_param
     * @param array $arr_where
     * @return bool
     * @author yanrui@tizi.com
     */
    public function update_lesson($arr_param,$arr_where){
        $bool_flag = false;
        if($arr_param AND $arr_where){
            $bool_flag = $this->model_lesson->update_lesson($arr_param,$arr_where);
        }
        return $bool_flag;
    }

    /**
     * 根据条件获取lesson count
     * @param $arr_where
     * @return array
     * @author yanrui@tizi.com
     */
//    public function get_lesson_count($arr_where){
//        $int_return = array();
//        if(is_array($arr_where)){
//            $str_table_range = 'lesson';
//            $str_result_type = 'count';
//            $str_fields = 'count(1) as count';
//            $int_return = $this->model_lesson->get_lesson_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
//        }
//        return $int_return;
//    }

    /**
     * 根据条件获取lesson count
     * @param $arr_where
     * @param $int_start
     * @param $int_limit
     * @return array
     * @author yanrui@tizi.com
     */
//    public function get_lesson_list($arr_where,$int_start,$int_limit){
//        $arr_return = array();
//        if(is_array($arr_where)){
//            $str_table_range = 'lesson';
//            $str_result_type = 'list';
//            $str_fields = 'id,course_id,title,courseware_id,status,parent_id,sequence';
//            $arr_limit = array(
//                'start'=>$int_start,
//                'limit' => $int_limit
//            );
//            $arr_return = $this->model_lesson->get_lesson_by_param($str_table_range, $str_result_type, $str_fields, $arr_where, array(), array(),$arr_limit);
//        }
//        return $arr_return;
//    }

    /**
     * 根据id取lesson
     * @param $int_lesson_id
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_lesson_by_id($int_lesson_id)
    {
        $arr_return = array();
        if($int_lesson_id){
            $str_table_range = 'lesson';
            $str_result_type = 'one';
            $str_fields = '*';
            $arr_where = array(
                'id' => $int_lesson_id
            );
            $arr_return = $this->model_lesson->get_lesson_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }

    /**
     * 根据course_id取lesson
     * @param int $int_course_id
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_lessons_by_course_id($int_course_id)
    {
        $arr_return = array();
        if($int_course_id){
            $str_table_range = 'lesson';
            $str_result_type = 'list';
            $str_fields = 'id,course_id,title,courseware_id,status,parent_id,sequence';
            $arr_where = array(
                'course_id' => $int_course_id
            );
            $arr_return = $this->model_lesson->get_lesson_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }

    /**
     * add courseware to lesson
     * @param $int_lesson_id
     * @param $int_courseware_id
     * @return bool
     * @author yanrui@tizi.com
     */
    public function add_courseware($int_lesson_id, $int_courseware_id){
        $bool_return = false;
        if($int_lesson_id > 0 AND $int_courseware_id > 0){
            $arr_param = array(
                'courseware_id' => $int_courseware_id,
            );
            $arr_where = array(
                'id' => $int_lesson_id
            );
            $bool_return = $this->model_lesson->update_lesson($arr_param, $arr_where);
        }
        return $bool_return;
    }

    /**
     * delete courseware by lesson_id
     * @param $int_lesson_id
     * @return bool
     */
//    public function delete_coruse_by_lesson_id($int_lesson_id){
//        $bool_return = false;
//        if($int_lesson_id > 0){
//            $arr_param = array(
//                'courseware_id' => 0,
//            );
//            $arr_where = array(
//                'id' => $int_lesson_id
//            );
//            $bool_return = $this->lesson->update_lesson($arr_param, $arr_where);
//        }
//        return $bool_return;
//    }
}