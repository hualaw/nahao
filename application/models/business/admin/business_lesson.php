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
    public function create_lessons_OLD($int_course_id,$arr_lessons)
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
                    //插入章
                    $int_parent_id = 1;//默认没有章的
                    if(isset($v['title'])){
                        $arr_chapter = array(
                            'course_id' => $int_course_id,
                            'title' => $v['title'],
                            'sequence' => $int_chapter_flag++
                        );
                        $int_parent_id = $this->model_lesson->create_lesson($arr_chapter);
                    }
//                    o($int_parent_id,true);
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
                }
            }
        }
        return $bool_return;
    }

    /**
     * create lesson
     * @param $arr_param
     * @return int
     * @author yanrui@tizi.com
     */
    public function create_lesson($arr_param){
        $int_return = 0;
        if(is_array($arr_param) AND $arr_param){
            $int_return = $this->model_lesson->create_lesson($arr_param);
        }
        return $int_return;
    }

    /**
     * get_lessons_list_tree
     * @param $arr_lessons
     * @return array
     * @author yanrui@tizi.com
     */
//    public function get_lessons_list_tree($arr_lessons){
//        $arr_return = array();
//        if(is_array($arr_lessons) AND $arr_lessons){
//            foreach($arr_lessons as $k => $v){
//                if($v['parent_id']==0){
//                    $arr_return[$v['id']] = $v;
//                    $int_parent_id = $v['id'];
//                    $int_sequence = 0;
//                }else{
//                    $v['parent_id'] = $int_parent_id;
//                    $v['sequence'] = $int_sequence++;
//                    $arr_return[$int_parent_id]['sections'][] = $v;
//                }
//            }
//        }
//        return $arr_return;
//    }

    /**
     * get_lessons_list after sort
     * @param $arr_lessons
     * @return array
     * @author yanrui@tizi.com
     */
//    public function get_lessons_list($arr_lessons){
//        $arr_return = array();
//        if(is_array($arr_lessons) AND $arr_lessons){
//            $arr_tree = array();
//            $arr_tree = self::get_lessons_list_tree($arr_lessons);
//            foreach($arr_tree as $k => $v){
//                if(isset($v['sections'])){
//                    $arr_sections = $v['sections'];
//                    unset($v['sections']);
//                    $arr_return[] = $v;
//                    if($arr_sections){
////                        o($arr_sections,true);
//                        foreach($arr_sections as $kk => $vv){
//                            $arr_return[] = $vv;
//                        }
//                    }
//                }else{
//                    $arr_return[] = $v;
//                }
//            }
//        }
//        return $arr_return;
//    }


    /**
     * 把提交过来的原始的lesson数据组合成章节树形结构
     * @param $arr_lessons
     * @return array
     * @author yanrui@tizi.com
     */
//    public function get_lesson_tree($arr_lessons){
//        $arr_return = array();
//        if($arr_lessons){
//            $int_chapter_flag = $int_section_flag = $parent_id = 0 ;
//            foreach($arr_lessons as $k => $v){
//                $arr_tmp = array(
//                    'id' => $v['lesson_id'],
//                );
//                if($v['parent_id']==0){
//                    $arr_tmp['parent_id'] = 0;
//                    $arr_tmp['sequence'] = $int_chapter_flag++;
//                    $int_section_flag = 0 ;
//                    $parent_id = $v['lesson_id'];
//                }else{
//                    $arr_tmp['parent_id'] = $parent_id;
//                    $arr_tmp['sequence'] = $int_section_flag++;
//                }
//                $arr_return[] = $arr_tmp;
//            }
//        }
//        return $arr_return;
//    }

    //=========================================================
    /**
     * lesson_list for index show
     * @param $arr_lessons
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_lessons_list_show($arr_lessons){
        $arr_return = array();
        if(is_array($arr_lessons) AND $arr_lessons){
            $arr_tree = self::get_lessons_list_tree_show($arr_lessons);
//            o($arr_tree);
//            echo '======';
            foreach($arr_tree as $k => $v){
                if(isset($v['sections'])){
                    $arr_sections = $v['sections'];
                    unset($v['sections']);
                    if(isset($v['title'])){
                        $arr_return[] = $v;//非默认章
                    }
                    foreach($arr_sections as $kk => $vv){
                        $arr_return[] = $vv;
                    }
                }else{
                    $arr_return[] = $v;
                }
            }
        }
        return $arr_return;
    }

    /**
     * lesson_list_tree for index show
     * @param $arr_lessons
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_lessons_list_tree_show($arr_lessons){
        $arr_return = array();
        if(is_array($arr_lessons) AND $arr_lessons){
            foreach($arr_lessons as $k => $v){
                if($v['parent_id']==0){
                    $arr_return[$v['id']] = $v;
                }else{
                    $arr_return[$v['parent_id']]['sections'][] = $v;
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
//            o($arr_param);
//            o($arr_where,true);
            $bool_flag = $this->model_lesson->update_lesson($arr_param,$arr_where);
        }
        return $bool_flag;
    }

    /**
     * 批量修改lessons
     * @param array $arr_param
     * @param array $str_field
     * @return bool
     * @author yanrui@tizi.com
     */
    public function update_lesson_batch($arr_param,$str_field){
        $bool_flag = false;
        if($arr_param AND $str_field){
            $bool_flag = $this->model_lesson->update_lesson_batch($arr_param,$str_field);
        }
        return $bool_flag;
    }

    /**
     * lessons sort
     * @param $arr_param
     * @return bool
     * @author yanrui@tizi.com
     */
    public function sort($arr_param){
        $bool_return = false;
        if(is_array($arr_param) AND $arr_param){
            $str_field = 'id';
            $bool_return = self::update_lesson_batch($arr_param,$str_field);
        }
        return $bool_return;
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
            $str_fields = 'id,course_id,title,courseware_id,status,parent_id,sequence,school_hour';
            $arr_where = array(
                'course_id' => $int_course_id,
            );
            $arr_order = array(
                'parent_id' => 'asc',
                'sequence' => 'asc'
            );
            $arr_return = $this->model_lesson->get_lesson_by_param($str_table_range, $str_result_type, $str_fields, $arr_where,array(),$arr_order);
        }
        return $arr_return;
    }

    /**
     * add courseware to lesson
     * @param $int_lesson_id
     * @param $arr_courseware
     * @return bool
     * @author yanrui@tizi.com
     */
    public function add_courseware($int_lesson_id, $arr_courseware){
        $bool_return = false;
        if($int_lesson_id > 0 AND is_array($arr_courseware) AND $arr_courseware){

            $this->load->model('business/common/business_courseware','courseware');
            $this->courseware->create_courseware($arr_courseware);
            $arr_param = array(
                'courseware_id' => $arr_courseware['id'],
            );
            $arr_where = array(
                'id' => $int_lesson_id
            );
            $bool_return = $this->model_lesson->update_lesson($arr_param, $arr_where);
        }
        return $bool_return;
    }

     /**
      * get_chapters_by_course_id
      * @param $int_course_id
      * @return array
      * @author yanrui@tizi.com
      */
     public function get_chapters_by_course_id($int_course_id){
         $arr_return = array();
         if($int_course_id){
             $str_table_range = 'lesson';
             $str_result_type = 'list';
             $str_fields = 'id,title';
             $arr_where = array(
                 'course_id' => $int_course_id,
                 'parent_id' => 0
             );
             $arr_return = $this->model_lesson->get_lesson_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
         }
         return $arr_return;
     }

     /**
      * @param array $arr_where
      * @return bool
      * @author yanrui@tizi.com
      */
     public function delete_lesson($arr_where){
         $bool_return = true;
         if($arr_where){
             $this->model_lesson->delete_lesson_by_param($arr_where);
         }
         return $bool_return ;
     }
}