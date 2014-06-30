<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Course相关逻辑
 * Class Business_Course
 * @author yanrui@tizi.com
 */
class Business_Course extends NH_Model
{
    function __construct(){
        parent::__construct();
        $this->load->model('model/admin/model_course');
    }

    /**
     * 创建course
     * @param array $arr_param
     * @return int
     * @author yanrui@tizi.com
     */
    public function create_course($arr_param)
    {
        $int_return = 0;
        if($arr_param){
            $arr_param['create_time'] = TIME_STAMP;
            $arr_param['role'] = NH_MEETING_TYPE_ADMIN;
            $arr_param['user_id'] = $this->userinfo['id'];
            $int_return = $this->model_course->create_course($arr_param);
        }
        return $int_return;
    }

    /**
     * 修改course
     * @param array $arr_param
     * @param array $arr_where
     * @return bool
     * @author yanrui@tizi.com
     */
    public function update_course($arr_param,$arr_where){
        $bool_flag = false;
        if($arr_param AND $arr_where){
//            o($arr_param);
//            o($arr_where,true);
            $bool_flag = $this->model_course->update_course($arr_param,$arr_where);
        }
        return $bool_flag;
    }

    /**
     * 根据条件获取course count
     * @param $arr_where
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_course_count($arr_where){
        $int_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'course_info';
            $str_result_type = 'list';
            $str_fields = 'count(1) as count';
            if(array_key_exists('status',$arr_where)){
                $arr_where[TABLE_COURSE.'.status'] = $arr_where['status'];
                unset($arr_where['status']);
            }
            if(array_key_exists('subject',$arr_where)){
                $arr_where[TABLE_COURSE.'.subject'] = $arr_where['subject'];
                unset($arr_where['subject']);
            }
            if(array_key_exists('course_type',$arr_where)){
                $arr_where[TABLE_COURSE.'.course_type'] = $arr_where['course_type'];
                unset($arr_where['course_type']);
            }
            if(array_key_exists('teacher_id',$arr_where)){
                $arr_where[TABLE_COURSE.'.teacher_id'] = $arr_where['teacher_id'];
                unset($arr_where['teacher_id']);
            }
            if(array_key_exists('id',$arr_where)){
                $arr_where[TABLE_COURSE.'.id'] = $arr_where['id'];
                unset($arr_where['id']);
            }
            if(array_key_exists('title',$arr_where)){
                $arr_where['like'][TABLE_COURSE.'.title'] = $arr_where['title'];
                unset($arr_where['title']);
            }
            $arr_group_by = array(
                TABLE_COURSE.'.id'
            );
            $arr_return = $this->model_course->get_course_by_param($str_table_range, $str_result_type, $str_fields, $arr_where,$arr_group_by);
            $int_return = count($arr_return);
//            o($int_return,true);
        }
        return $int_return;
    }

    /**
     * 根据条件获取course list
     * @param $arr_where
     * @param $int_start
     * @param $int_limit
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_course_list($arr_where,$int_start,$int_limit){
        $arr_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'course_info';
            $str_result_type = 'list';
            $str_fields = TABLE_COURSE.'.id,title,subtitle,intro,description,students,subject,course_type,reward,price,'.TABLE_COURSE.'.status,create_time,'.TABLE_COURSE.'.role,user_id,score,bought_count,graduate_count,video,img,grade_from,grade_to,'.TABLE_SUBJECT.'.name as subject_name,'.TABLE_COURSE_TYPE.'.name as course_type_name,'.TABLE_USER.'.nickname,lesson_count';
//            $str_fields = '*';
            if(array_key_exists('status',$arr_where)){
                $arr_where[TABLE_COURSE.'.status'] = $arr_where['status'];
                unset($arr_where['status']);
            }
            if(array_key_exists('subject',$arr_where)){
                $arr_where[TABLE_COURSE.'.subject'] = $arr_where['subject'];
                unset($arr_where['subject']);
            }
            if(array_key_exists('course_type',$arr_where)){
                $arr_where[TABLE_COURSE.'.course_type'] = $arr_where['course_type'];
                unset($arr_where['course_type']);
            }
            if(array_key_exists('teacher_id',$arr_where)){
                $arr_where[TABLE_COURSE.'.teacher_id'] = $arr_where['teacher_id'];
                unset($arr_where['teacher_id']);
            }
            if(array_key_exists('id',$arr_where)){
                $arr_where[TABLE_COURSE.'.id'] = $arr_where['id'];
                unset($arr_where['id']);
            }
            if(array_key_exists('title',$arr_where)){
                $arr_where['like'][TABLE_COURSE.'.title'] = $arr_where['title'];
                unset($arr_where['title']);
            }
            $arr_group_by = array(
                TABLE_COURSE.'.id'
            );
            $arr_limit = array(
                'start'=>$int_start,
                'limit' => $int_limit
            );
            $arr_return = $this->model_course->get_course_by_param($str_table_range, $str_result_type, $str_fields, $arr_where, $arr_group_by, array(),$arr_limit);
        }
        return $arr_return;
    }

    /**
     * 根据id取course
     * @param $int_course_id
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_course_by_id($int_course_id)
    {
        $arr_return = array();
        if($int_course_id){
            $str_table_range = 'course';
            $str_result_type = 'one';
            $str_fields = '*';
            $arr_where = array(
                'id' => $int_course_id
            );
            $arr_return = $this->model_course->get_course_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }

    /**
     * 添加课程与老师关系
     * @param $int_course_id
     * @param $arr_teacher_ids
     * @return bool
     * @author yanrui@tizi.com
     */
    public function create_course_teacher_batch($int_course_id,$arr_teacher_ids){
        $int_return = 0;
        if($int_course_id > 0 AND is_array($arr_teacher_ids) AND $arr_teacher_ids){
            $arr_param = array();
            foreach($arr_teacher_ids as $k => $v){
                if($v > 0){
                    $arr_param[] = array(
                        'course_id' => $int_course_id,
                        'teacher_id' => $v,
                        'sequence' => $k
                    );
                }else{
                    break;
                }
            }
            if($arr_param){
                //先清除该课程以前的老师，再插入新的老师
                self::delete_teachers_by_course_id($int_course_id);
                $int_return = $this->model_course->create_course_teacher_batch($arr_param);
            }
        }
        return $int_return;
    }

    /**
     * 根据课程ID删除课程与老师的关系
     * @param $int_course_id
     * @author yanrui@tizi.com
     */
    public function delete_teachers_by_course_id($int_course_id){
        if($int_course_id > 0){
            $arr_where = array(
                'course_id' => $int_course_id
            );
            $this->model_course->delete_course_teacher_relation_by_param($arr_where);
        }
    }

    /**
     * get teachers by course_id
     * @param $int_course_id
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_teachers_by_course_id($int_course_id){
        $arr_return = array();
        if($int_course_id > 0){
            $str_table_range = 'course_teachers';
            $str_result_type = 'list';
            $str_fields = TABLE_USER.'.id,nickname';
            $arr_where = array(
                'course_id' => $int_course_id,
                TABLE_USER.'.teach_priv' => TABLE_USER_DIC_TEACH_PRIV_ON,
                TABLE_USER.'.status' => TABLE_USER_DIC_STATUS_ON,
            );
            $arr_return = $this->model_course->get_course_teacher_relation_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }


    /**
     * if_can_generate_round
     * @param $int_course_id
     * @return bool
     * @author yanrui@tizi.com
     */
    public function if_can_generate_round($int_course_id){
        $bool_return = false;
        if($int_course_id > 0){

        }
        return $bool_return;
    }

    /**
     * get_section_count
     * @param $arr_lessons
     * @return int
     * @author yanrui@tizi.com
     */
    public function get_section_count($arr_lessons){
        $int_return = 0;
        if(is_array($arr_lessons) AND $arr_lessons){
            foreach($arr_lessons as $k => $v){
                if($v['parent_id'] > 1){
                    $int_return++;
                }
            }
        }
        return $int_return;
    }
}