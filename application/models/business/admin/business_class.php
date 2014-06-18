<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class相关逻辑
 * Class Business_Class
 * @author yanrui@tizi.com
 */
class Business_Class extends NH_Model
{
    function __construct(){
        parent::__construct();
        $this->load->model('model/admin/model_class');
    }

    /**
     * 创建classes
     * @param $int_course_id
     * @param $int_round_id
     * @param $arr_classes
     * @return bool
     * @author yanrui@tizi.com
     */
    public function create_classes($int_course_id, $int_round_id,$arr_classes)
    {
        $bool_return = false;
        if($int_round_id > 0 AND is_array($arr_classes) AND $arr_classes){
//            o($arr_classes);
            $arr_class_tree = self::get_class_tree($arr_classes);
//            var_dump($arr_class_tree);exit;
            if($arr_class_tree){
                //先清除该班次以前的课节，再插入新的课节
                self::delete_classes_by_round_id($int_round_id);

                $int_chapter_flag = 0;
                foreach($arr_class_tree as $k => $v){
                    $arr_chapter = array(
                        'course_id' => $int_course_id,
                        'round_id' => $int_round_id,
                        'lesson_id' => $v['lesson_id'],
                        'title' => $v['title'],
//                        'courseware_id' => $v['courseware_id'],
//                        'classroom_id' => general_classroom_id(array('name' => $v['title'],'start_at' => $v['begin_time'],'end_at' => $v['end_time'])),
//                        'begin_time' => strtotime($v['begin_time']),
//                        'end_time' => strtotime($v['end_time']),
                        'sequence' => $int_chapter_flag++
                    );
//                    o($arr_chapter);
                    //插入章
                    $int_parent_id = $this->model_class->create_class($arr_chapter);
                    if($int_parent_id > 0){
                        $int_section_flag = 0;
                        foreach($v['classes'] as $kk => $vv){
                            $int_classroom_id = general_classroom_id(array('name' => $vv['title'],'start_at' => $vv['begin_time'],'end_at' => $vv['end_time']));
                            $int_courseware_id = $vv['courseware_id'];
                            $arr_section[] = array(
                                'course_id' => $int_course_id,
                                'round_id' => $int_round_id,
                                'lesson_id' => $vv['lesson_id'],
                                'title' => $vv['title'],
                                'courseware_id' => $int_courseware_id,
                                'classroom_id' => $int_classroom_id,
                                'begin_time' => strtotime($vv['begin_time']),
                                'end_time' => strtotime($vv['end_time']),
                                'parent_id' => $int_parent_id,
                                'sequence' => $int_section_flag++
                            );
//                            o($arr_section);
                            $bool_add_courseware = set_courseware_to_classroom($int_classroom_id,$int_courseware_id);
//                            o($bool_add_courseware,true);
                            if($bool_add_courseware == false){
                                //add again
                                $bool_add_courseware = set_courseware_to_classroom($int_classroom_id,$int_courseware_id);
                                if($bool_add_courseware==false){
                                    break;
                                }
                            }
                        }
//                        o($arr_section,true);
                        if($bool_add_courseware==true){
//                            o($arr_section);
                            //插入节
                            $int_last_id = $this->model_class->create_class_batch($arr_section);
//                        o($int_last_id,true);
                            if($int_last_id > 0){
//                                echo $k.'-'.count($arr_class_tree);
                                if($k == count($arr_class_tree)-1){
                                    //last one return true
                                    $bool_return = true;
                                }
                            }else{
                                //stop adding section
                                break;
                            }
                            unset($arr_section);
                        }else{
                            //stop adding section
                            break;
                        }
                    }else{
                        //stop adding chapter
                        break;
                    }
                    if($bool_add_courseware==false){
                        //stop adding chapter
                        break;
                    }
                }
            }
        }
        return $bool_return;
    }

    /**
     * 把提交过来的原始的class数据组合成章节树形结构
     * @param $arr_classes
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_class_tree($arr_classes){
        $arr_return = array();
        if($arr_classes){
            $int_flag = 0 ;
            foreach($arr_classes as $k => $v){
//                o($v);
                if($v['is_chapter']==1){
                    if($k != 0){
                        $int_flag ++ ;
                    }
                    $arr_return[$int_flag]['title'] = $v['name'];
                    $arr_return[$int_flag]['lesson_id'] = $v['lesson_id'];
                    $arr_return[$int_flag]['courseware_id'] = $v['courseware_id'];
                    $arr_return[$int_flag]['begin_time'] = $v['start_time'];
                    $arr_return[$int_flag]['end_time'] = $v['end_time'];
                }else{
                    $arr_return[$int_flag]['classes'][] = array(
                        'title' => $v['name'],
                        'lesson_id' => $v['lesson_id'],
                        'courseware_id' => $v['courseware_id'],
                        'begin_time' => $v['start_time'],
                        'end_time' => $v['end_time'],
                    );
                }
            }
//            var_dump($arr_return[0]['classes']);
        }
        return $arr_return;
    }

    /**
     * 根据round_id删除class
     * @param $int_round_id
     * @author yanrui@tizi.com
     */
    public function delete_classes_by_round_id($int_round_id){
        if($int_round_id > 0){
            $arr_where = array(
                'course_id' => $int_round_id
            );
            $this->model_class->delete_class_by_param($arr_where);
        }
    }

    /**
     * 修改class
     * @param array $arr_param
     * @param array $arr_where
     * @return bool
     * @author yanrui@tizi.com
     */
    public function update_class($arr_param,$arr_where){
        $bool_flag = false;
        if($arr_param AND $arr_where){
            $bool_flag = $this->model_class->update_class($arr_param,$arr_where);
        }
        return $bool_flag;
    }

    /**
     * 根据条件获取class count
     * @param $arr_where
     * @return array
     * @author yanrui@tizi.com
     */
//    public function get_class_count($arr_where){
//        $int_return = array();
//        if(is_array($arr_where)){
//            $str_table_range = 'class';
//            $str_result_type = 'count';
//            $str_fields = 'count(1) as count';
//            $int_return = $this->model_class->get_class_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
//        }
//        return $int_return;
//    }

    /**
     * 根据条件获取class count
     * @param $arr_where
     * @param $int_start
     * @param $int_limit
     * @return array
     * @author yanrui@tizi.com
     */
//    public function get_class_list($arr_where,$int_start,$int_limit){
//        $arr_return = array();
//        if(is_array($arr_where)){
//            $str_table_range = 'class';
//            $str_result_type = 'list';
//            $str_fields = 'id,course_id,title,courseware_id,status,parent_id,sequence';
//            $arr_limit = array(
//                'start'=>$int_start,
//                'limit' => $int_limit
//            );
//            $arr_return = $this->model_class->get_class_by_param($str_table_range, $str_result_type, $str_fields, $arr_where, array(), array(),$arr_limit);
//        }
//        return $arr_return;
//    }

    /**
     * 根据id取class
     * @param $int_class_id
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_class_by_id($int_class_id)
    {
        $arr_return = array();
        if($int_class_id){
            $str_table_range = 'class';
            $str_result_type = 'one';
            $str_fields = '*';
            $arr_where = array(
                'id' => $int_class_id
            );
            $arr_return = $this->model_class->get_class_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }

    /**
     * 根据course_id取class
     * @param int $int_round_id
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_classes_by_round_id($int_round_id)
    {
        $arr_return = array();
        if($int_round_id){
            $str_table_range = 'class';
            $str_result_type = 'list';
            $str_fields = 'id,course_id,round_id,lesson_id,title,begin_time,end_time,courseware_id,status,parent_id,sequence,classroom_id,checkout_status';
            $arr_where = array(
                'round_id' => $int_round_id
            );
            $arr_return = $this->model_class->get_class_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }

    /**
     * add courseware to class
     * @param $int_class_id
     * @param $int_courseware_id
     * @return bool
     * @author yanrui@tizi.com
     */
    public function add_courseware($int_class_id, $int_courseware_id){
        $bool_return = false;
        if($int_class_id > 0 AND $int_courseware_id > 0){
            $arr_param = array(
                'courseware_id' => $int_courseware_id,
            );
            $arr_where = array(
                'id' => $int_class_id
            );
            $bool_return = $this->model_class->update_class($arr_param, $arr_where);
        }
        return $bool_return;
    }

}