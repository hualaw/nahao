<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_Classroom extends NH_Model{
    
    function __construct(){
        parent::__construct();
    }
    
    /**
     * 查找最大批次
     * @param  $int_class_id
     * @return $array_result
     */
    public function get_max_sequence($int_class_id)
    {
        $array_result = array();
        $sql = "SELECT MAX(sequence) AS sequence FROM question_class_relation 
                WHERE class_id = ".$int_class_id;
        $array_result = $this->db->query($sql)->row_array();
        return $array_result;
    }
    
    /**
     * 获取练习题的题目id数组
     * @param  $int_class_id
     * @param  $int_max_sequence
     * @return $array_result
     */
    public function get_exercise_qid($int_class_id,$int_max_sequence)
    {
        $array_result = array();
        $sql = "SELECT question_id FROM question_class_relation 
                WHERE class_id = ".$int_class_id." AND status = 1 AND sequence = ".$int_max_sequence;
        $array_result = $this->db->query($sql)->result_array();
        return $array_result;
    }
    
    /**
     * 根据练习题的题目id，获取练习题的具体数据
     * @param  $int_qid
     * @return $array_result
     */
    public function get_question_infor($int_qid)
    {
        $array_result = array();
        $sql = "SELECT id,question,answer,options,type,analysis FROM question WHERE id = ".$int_qid;
        $array_result = $this->db->query($sql)->row_array();
        return $array_result;
    }
    
    /**
     * 保存学生提交的答题记录
     * @param  $array_data
     * @return boolean
     */
    public function save_sutdent_question($array_data)
    {
        $this->db->insert('sutdent_question', $array_data);
        $int_row = $this->db->affected_rows();
        return $int_row > 0 ? true : false;
    }
    
    /**
     * 获取学生做题记录
     * @param  $array_data['class_id']
     * @param  $array_data['student_id']
     * @param  $array_data['sequence']
     * @return $array_result
     */
    public function get_student_question_data($array_data)
    {
        $array_result = array();
        $sql ="SELECT question_id,answer AS selected,sequence,is_correct FROM sutdent_question 
               WHERE class_id = ".$array_data['class_id']." AND student_id = ".$array_data['student_id'].
               " AND sequence = ".$array_data['sequence'];
        $array_result = $this->db->query($sql)->result_array();
        return $array_result;
    }
    
    
    /**
     * 出题总数
     * @param  $array_data['class_id']
     * @param  $array_data['sequence']
     * @return $int_result
     */
    public function get_question_count_by_sequence($array_data)
    {
        $sql = "SELECT id FROM question_class_relation 
                WHERE class_id = ".$array_data['class_id']." AND status = 1 
                AND sequence = ".$array_data['sequence'];
        $int_result = $this->db->query($sql)->num_rows();
        return $int_result;
    }
    
    /**
     * 学生在教室做题情况（对题数、错题数）
     * @param  $array_data['class_id']
     * @param  $array_data['student_id']
     * @param  $array_data['sequence']
     * @param  $is_correct "1是对的 0是错的"
     * @return $int_result
     */
    public function get_student_statistics($array_data,$is_correct)
    {
        $sql ="SELECT id FROM sutdent_question 
               WHERE class_id = ".$array_data['class_id']." AND student_id = ".$array_data['student_id'].
               " AND sequence = ".$array_data['sequence']." AND is_correct = ".$is_correct;
        $int_result = $this->db->query($sql)->num_rows();
        return $int_result;
    }

    /**
     * 保存课堂笔记(课堂新增，学生后台修改)
     * @param $arr_data
     * @return bool
     * @author yanrui@tizi.com
     */
    public function save_class_note($arr_data)
    {
        $bool_return = false;
        if (is_array($arr_data) AND $arr_data) {
            $sql = 'INSERT INTO ' . TABLE_CLASS_NOTE . " (`class_id`,`student_id`,`content`,`create_time`,`update_time`) VALUES ('" . implode("','", $arr_data) . "') ON DUPLICATE KEY UPDATE content='" . $arr_data['content'] . "',update_time=" . $arr_data['update_time'];
            $bool_return = $this->db->query($sql);
        }
        return $bool_return;
    }
    
    /**
     * 去答题记录表中查看用户是否做过当前批次的题，将这些题的id出来
     */
    public function get_student_question_qid($int_class_id,$int_max_sequence,$int_user_id)
    {
        $array_result = array();
        $sql = "SELECT distinct question_id FROM sutdent_question WHERE class_id = ".$int_class_id." 
                AND sequence = " .$int_max_sequence." AND student_id = ".$int_user_id;
        $array_result = $this->db->query($sql)->result_array();
        return $array_result;
    }
    
    /**
     * 根据classroom_id获取课id
     */
    public function get_class_id_by_classroom_id($int_classroom_id)
    {
        $array_result = array();
        $sql = "SELECT id FROM class WHERE classroom_id = ".$int_classroom_id;
        $array_result = $this->db->query($sql)->row_array();
        return $array_result;
    }
}