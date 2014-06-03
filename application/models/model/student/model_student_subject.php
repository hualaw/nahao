<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * student学生感兴趣的学科
 * Class Model_Student_Subject
 * @author liuhua@tizi.com
 */
class Model_student_subject extends NH_Model{

    function __construct(){
        parent::__construct();
    }

    public function add($student_id, $subject_id_arr)
    {
        foreach($subject_id_arr as $subject_id)
        {
            $insert_arr= array('student_id'=>$student_id, 'subject_id'=>$subject_id);
            $this->db->insert(TABLE_STUDENT_SUBJECT, $insert_arr);
            $row = $this->db->affected_rows();
            if($row != 1)
            {
                log_message('ERROR_NAHAO', 'insert student_subject failed', $insert_arr);
            }
        }
        return true;
    }

    public function delete($student_id, $subject_id_arr)
    {
        foreach($subject_id_arr as $subject_id)
        {
            $this->db->where('student_id', $student_id);
            $this->db->where('subject_id', $subject_id);
            $this->db->delete(TABLE_STUDENT_SUBJECT);
            $row = $this->db->affected_rows();
            if($row != 1)
            {
                log_message('ERROR_NAHAO', 'delete student_subject failed', array('student_id'=>$student_id, 'subject_id'=>$subject_id));
            }
        }
        return true;
    }
}
