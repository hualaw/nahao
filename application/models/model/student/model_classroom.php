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
     * @param  $array_data
     * @return $array_result
     */
    public function get_student_question_data($array_data)
    {
        $array_result = array();
        $sql ="SELECT question_id,answer AS selected,sequence FROM sutdent_question 
               WHERE class_id = ".$array_data['class_id']." AND student_id = ".$array_data['student_id'].
               " AND sequence = ".$array_data['sequence'];
        $array_result = $this->db->query($sql)->result_array();
        return $array_result;
    }
    
    /**
     * 意见反馈
     * @param  $array_data
     * @return boolean
     */
    public function save_feedback($array_data)
    {
        $this->db->insert('feedback', $array_data);
        $int_row = $this->db->affected_rows();
        return $int_row > 0 ? true : false;
    }
    
    /**
     * 课程评价
     * @param  $array_data
     * @return boolean
     */
    public function save_class_feedback($array_data)
    {
        $this->db->insert('class_feedback', $array_data);
        $int_row = $this->db->affected_rows();
        return $int_row > 0 ? true : false;
    }
}