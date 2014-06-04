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
     * 创建lesson
     * @param array $arr_param
     * @return int
     * @author yanrui@tizi.com
     */
    public function create_lesson($arr_param)
    {
        $int_return = 0;
        if($arr_param){
            $arr_param['create_time'] = TIME_STAMP;
            $arr_param['role'] = ROLE_ADMIN;
            $arr_param['user_id'] = $this->userinfo['id'];
            $int_return = $this->model_lesson->create_lesson($arr_param);
        }
        return $int_return;
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
    public function get_lesson_count($arr_where){
        $int_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'lesson_info';
            $str_result_type = 'count';
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
            $int_return = $this->model_lesson->get_lesson_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $int_return;
    }

    /**
     * 根据条件获取lesson count
     * @param $arr_where
     * @param $int_start
     * @param $int_limit
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_lesson_list($arr_where,$int_start,$int_limit){
        $arr_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'lesson_info';
            $str_result_type = 'list';
            $str_fields = TABLE_COURSE.'.id,title,subtitle,intro,description,students,subject,course_type,reward,price,'.TABLE_COURSE.'.status,create_time,'.TABLE_COURSE.'.role,user_id,score,bought_count,graduate_count,video,img,grade_from,grade_to,'.TABLE_SUBJECT.'.name as subject_name,'.TABLE_COURSE_TYPE.'.name as course_type_name,'.TABLE_USER.'.nickname';
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
            $arr_limit = array(
                'start'=>$int_start,
                'limit' => $int_limit
            );
            $arr_return = $this->model_lesson->get_lesson_by_param($str_table_range, $str_result_type, $str_fields, $arr_where, array(), array(),$arr_limit);
        }
        return $arr_return;
    }

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
     * 根据round_id取lesson
     * @param int $int_round_id
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_lesson_by_round_id($int_round_id)
    {
        $arr_return = array();
        if($int_round_id){
            $str_table_range = 'lesson';
            $str_result_type = 'one';
            $str_fields = 'id,username,phone,email,salt,password,realname,status';
            $arr_where = array(
                'round_id' => $int_round_id
            );
//            echo $str_table_range.'--'.$str_result_type.'--'.$str_fields."\n";echo "where : \n";var_dump($arr_where);;exit;
            $arr_return = $this->model_lesson->get_lesson_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }


}