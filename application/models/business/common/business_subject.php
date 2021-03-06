<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Business_Subject
 * @author yanrui@tizi.com
 */
class Business_Subject extends NH_Model {

    function __construct(){
        parent::__construct();
        $this->load->model('model/common/model_subject');
    }

    /**
     * 获取全部学科
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_subjects(){
        $str_table_range = 'subject';
        $str_result_type = 'list';
        $str_fields = 'id,name';
        $arr_return = $this->model_subject->get_subject_by_param($str_table_range, $str_result_type, $str_fields);
        return $arr_return;
    }

    /**
     * 拿到id为键name为值的subjects数组
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_subjects_like_kv(){
        $arr_subjects = self::get_subjects();
        $arr_return = self::converse_array_to_kv($arr_subjects);
        return $arr_return;
    }

    /**
     * 把普通数组转换为id为键name为值的数组
     * @param $arr_param
     * @return array
     * @author yanrui@tizi.com
     */
    public function converse_array_to_kv($arr_param){
        $arr_return = array();
        if(is_array($arr_param) AND $arr_param){
            foreach($arr_param as $k => $v){
                $arr_return[$v['id']] = $v['name'];
            }
        }
        return $arr_return;
    }


    /**
     * 根据id取subject
     * @param $int_subject_id
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_subject_by_id($int_subject_id)
    {
        $arr_return = array();
        if($int_subject_id){
            $str_table_range = 'subject';
            $str_result_type = 'one';
            $str_fields = '*';
            $arr_where = array(
                'id' => $int_subject_id
            );
            $arr_return = $this->model_subject->get_subject_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }
    
    /**
     * 获取老师用户所教的科目
     * @param int $teacher_id 老师的用户ID
     * @return array  返回包含了科目ID的数组
     * @author yanhj
     */
    public function get_teacher_subject($teacher_id)
    {
        $arr_return = array();
        if($teacher_id) {
            $str_table_range = 'teacher_subject';
            $str_result_type = 'list';
            $str_fields = 'subject_id';
            $arr_where = array(
                'teacher_id' => $teacher_id
            );
            $result = $this->model_subject->get_subject_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
            if(is_array($result)) {
                foreach($result as $val) {
                    $arr_return[] = $val['subject_id'];
                }
            }
        }
        return $arr_return;
    }
    
    /**
     * 获取学生用户关注的科目
     * @param int $student_id 学生的用户ID
     * @return array  返回包含了科目ID的数组
     * @author yanhj
     */
    public function get_student_subject($teacher_id)
    {
        $arr_return = array();
        if($teacher_id) {
            $str_table_range = 'student_subject';
            $str_result_type = 'list';
            $str_fields = 'subject_id';
            $arr_where = array(
                'student_id' => $teacher_id
            );
            $result = $this->model_subject->get_subject_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
            if(is_array($result)) {
                foreach($result as $val) {
                    $arr_return[] = $val['subject_id'];
                }
            }
        }
        return $arr_return;
    }

    public function get_student_subject_by_where($student_id,$education_type)
    {
        $arr_return = array();
        $result = T(TABLE_STUDENT_SUBJECT)->getAll('student_id ='.$student_id.' AND education_type ='.$education_type);
        if(is_array($result)) {
            foreach($result as $val) {
                $arr_return[] = $val['subject_id'];
            }
        }

        return $arr_return;
    }
}