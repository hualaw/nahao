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
            //生成树形结构的章节数据，二维数组
            $arr_class_tree = self::get_class_tree($arr_classes);
//            o($arr_classes);
//            o($arr_class_tree,true);
            if($arr_class_tree){
                //清除该班次以前的章和课节
                self::delete_classes_by_round_id($int_round_id);
                $arr_lesson_ids = array();
                $int_chapter_sequence = 0;
                //插入新的章和课节
                foreach($arr_class_tree as $k => $v){
                    $int_parent_id = 1;
                    if(isset($v['title'])){
                        //组织章数据
                        $arr_chapter = array(
                            'course_id' => $int_course_id,
                            'round_id' => $int_round_id,
                            'lesson_id' => $v['lesson_id'],
                            'title' => $v['title'],
                            'sequence' => $int_chapter_sequence++
                        );
                        //插入章
                        $int_parent_id = $this->model_class->create_class($arr_chapter);
                    }
//                    o($int_parent_id,true);
                    if($int_parent_id > 0){
                        $bool_section_flag = true;//每节课数组组成功的标记
                        $int_section_sequence = 0;//节的序列
                        $arr_section = array();
                        //为本章中每节课创建classroom，并且为该课堂添加courseware，组织一章中的多个课堂数据 一句sql插入多条class数据
                        foreach($v['classes'] as $kk => $vv){
                            if($vv['begin_time'] AND $vv['end_time']){
                                //生成classroom_id
                                $int_classroom_id = general_classroom_id(array('name' => $vv['title'],'start_at' => $vv['begin_time'],'end_at' => $vv['end_time']));
                                //组织class数据并且塞入数组中，本次循环完成后通过insert_batch一起入库
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
                                    'sequence' => $int_section_sequence++
                                );
                                $arr_lesson_ids[] = $vv['lesson_id'];
                                //为每个classroom添加courseware
                                $bool_add_courseware = set_courseware_to_classroom($int_classroom_id,$int_courseware_id);
//                                o($arr_lesson_ids);
//                                o($bool_add_courseware,true);
                                if($bool_add_courseware == false){
                                    //添加失败重试一次
                                    $bool_add_courseware = set_courseware_to_classroom($int_classroom_id,$int_courseware_id);
                                    if($bool_add_courseware==false){
                                        //只要有一个classroom添加课件失败了就终止本章课堂数据的组织
                                        $bool_section_flag = false;
                                        break;
                                    }
                                }
                            }else{
                                //本节课没有begin_time或end_time则终止组织本章的课堂数据
                                $bool_section_flag = false;
                                break;
                            }
                        }//组织本章所有节数据循环结束
                        if($bool_section_flag==true){
                            //本章中每个课堂数据正常，并且为每堂课添加courseware成功，则把组织好的class数据插入class表
//                                o($arr_section,true);
                            $int_last_id = $this->model_class->create_class_batch($arr_section);
                            if($int_last_id > 0){
                                if($k == count($arr_class_tree)-1){
                                    //完成最后一章的全部节插入class后             标记为本轮创建成功
                                    $bool_return = true;
                                }
                            }else{
                                //本章的节插入class失败，则终止插入章的循环
                                break;
                            }
                        }else{
                            //终止章的循环，本章中某一节课堂异常，可能是教材添加失败，也可能是课堂数据异常（begein_time和end_time是空）
                            break;
                        }

                    }else{
                        //插入章失败，终止全部循环
                        break;
                    }
                }
                //创建课堂完成后，为每堂课添加习题
                if($bool_return){
                    $this->load->model('business/admin/business_question', 'question');
                    $arr_param = array('lesson_id' => implode(',',$arr_lesson_ids));
                    $arr_questions = $this->question->lesson_question($arr_param,'generate_round');
//                    o($arr_section);
//                    o($arr_questions,true);
                    if($arr_questions){
                        $arr_question_ids = array();
                        foreach($arr_questions as $value){
                            if($value AND isset($value['question_id']) AND $value['question_id'] > 0 AND isset($value['lesson_id']) AND $value['lesson_id'] > 0){
                                $arr_question_ids[$value['lesson_id']][] = $value['question_id'];
                            }else{
                                $bool_return = false;
                                break;
                            }
                        }
                    }/*else{可以没有题
                        $bool_return = false;
                    }*/
                    if($bool_return==true){
                        $arr_classes = self::get_classes_by_round_id($int_round_id);
                        if($arr_classes){
                            $arr_delete_question_class_ids = $arr_questions_classes = array();
                            //产生要插入question_class_relation中的数据组
                            foreach($arr_classes as $value){
                                $arr_delete_question_class_ids[] = $value['id'];// for delete question_class_relation
                                if(isset($arr_question_ids[$value['lesson_id']])){
                                    foreach($arr_question_ids[$value['lesson_id']] as $k => $v){
                                        $arr_questions_classes[] = array(
                                            'class_id' => $value['id'],
                                            'question_id' => $v
                                        );
                                    }
                                }
                            }
//                            o($arr_questions_classes,true);
                            //根据class_id删除question_class_relation中的数据
                            if($arr_delete_question_class_ids){
                                $delete_arr_param = array(
                                    'do' => 'delete',
                                    'delete_class_question' => true,
                                    'class_id' => $arr_delete_question_class_ids
                                );
                                $this->question->class_question_delete($delete_arr_param);
                            }
                            $add_arr_param = array(
                                'do' => 'add_relation',
//                                'add_class_question' => true,
                                'no_check' => 1,
                                'class_id' => $arr_questions_classes
                            );
                            $bool_return = $this->question->class_question_doWrite($add_arr_param);
//                            o($bool_return,true);
                        }
                    }
                }
            }
        }
//        o($bool_return,true);
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