<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * studnet相关逻辑
 * Class Model_Student
 * @author liubing@tizi.com
 */
class Model_Course extends NH_Model{
    
    function __construct(){
        parent::__construct();
    }
    
    /**
     * 检查这个$int_round_id是否有效
     * @param  $int_round_id
     * @return $bool_result
     */
    public function check_round_id($int_round_id)
    {
        $sql = "SELECT id FROM round WHERE id = ".$int_round_id;
        $int_num = $this->db->query($sql)->num_rows();
        $bool_result = $int_num > 0 ? true : false;
        return $bool_result;
    }
    
    /**
     * 根据$int_round_id获取该轮的信息
     * @param  $int_round_id
     * @return $array_result
     */
    public function get_round_info($int_round_id)
    {
        $array_result = array();
        $sql = "SELECT id,title,img,video,subtitle,start_time,end_time,price,sale_price,
                sale_status,bought_count,caps,intro,students,description FROM round 
                WHERE id = ".$int_round_id;
        $array_result = $this->db->query($sql)->row_array();
        return $array_result;
    }
    
    /**
     * 根据$int_round_id获取该轮下的课的所有章
     * @param  $int_round_id
     * @return $array_result
     */
    public function get_all_chapter($int_round_id)
    {
        $array_result = array();
        $sql = "SELECT id,title FROM class WHERE parent_id = 0 AND round_id = ".$int_round_id.
               " ORDER BY sequence ASC";
        $array_result = $this->db->query($sql)->result_array();
        return $array_result;
    }
    
    /**
     * 获取一章下的所有节
     * @param  $int_parent_id
     * @param  $int_round_id
     * @return $array_result
     */
    public function get_one_chapter_children($int_parent_id,$int_round_id)
    {
        $array_result = array();
        $sql = "SELECT id,title,begin_time,end_time FROM class WHERE parent_id = ".$int_parent_id.
               " AND round_id = ".$int_round_id." ORDER BY sequence ASC";
        $array_result = $this->db->query($sql)->result_array();
        return $array_result;
    }
    
    /**
     * 没有章，获取所有的节
     * @param  $int_round_id
     * @return $array_result
     */
    public function get_all_section($int_round_id)
    {
        $array_result = array();
        $sql = "SELECT id,title,begin_time,end_time FROM class WHERE parent_id = 1".
               " AND round_id = ".$int_round_id." ORDER BY sequence ASC";
        $array_result = $this->db->query($sql)->result_array();
        return $array_result;
    }
    
    /**
     * 根据$int_round_id 找course_id
     * @param  $int_course_id
     * @return $int_result
     */
    public function get_course_id($int_round_id)
    {
        $sql = "SELECT course_id FROM round WHERE id = ".$int_round_id;
        $array_result = $this->db->query($sql)->row_array();
        return $int_result = empty($array_result['course_id']) ? false : $array_result['course_id'];
    }
    
    /**
     * 获取该课程所有评价（取审核通过的5条）
     * @param  $int_course_id
     * @return $int_result
     */
    public function get_round_evaluate($int_course_id)
    {
        $array_result = array();
        $sql = "SELECT student_id,nickname,content,create_time FROM class_feedback
                WHERE course_id = ".$int_course_id." AND is_show = 1 ORDER BY id DESC LIMIT 5";
        $array_result = $this->db->query($sql)->result_array();
        return  $array_result;
    }
    
    /**
     * 根据$int_round_id获取该轮的课程团队
     * @param  $int_round_id
     * @return $array_result
     */
    public function get_round_team($int_round_id,$int_type = '-1')
    {
        $where = '';
        if ($int_type >= 0)
        {
            $where.= " AND role = ".$int_type;
        }
        $array_result = array();
        $sql = "SELECT teacher_id,role FROM round_teacher_relation 
                WHERE round_id = ".$int_round_id.$where." ORDER BY sequence ASC";
        $array_result = $this->db->query($sql)->result_array();
        return  $array_result;
    }
    
    /**
     * 获取老师的具体信息
     * @param  $int_teacher_id
     * @return $array_result
     */
    public function get_teacher_info($int_teacher_id)
    {
        $array_result = array();
        $sql = "SELECT u.nickname,ui.teacher_age,ui.work_auth,ui.teacher_auth,ui.titile_auth,
                ui.teacher_intro,ui.teacher_signature,ui.user_id FROM user u 
                LEFT JOIN user_info ui ON u.id = ui.user_id
                WHERE ui.user_id = ".$int_teacher_id;
        $array_result = $this->db->query($sql)->row_array();
        return  $array_result;
    }
    
    /**
     * 根据course_id获取该课程下的所有轮（在审核通过和销售中）
     * @param  $int_course_id
     * @return $array_result
     */
    public function get_all_round($int_course_id)
    {
        $array_result = array();
        $sql = "SELECT id,start_time,end_time,sell_begin_time,sell_end_time FROM round 
                WHERE course_id = ".$int_course_id." AND sale_status >= 2 AND 
                sale_status <= 3 ORDER BY course_id";
        $array_result = $this->db->query($sql)->result_array();
        return  $array_result;
    }
    
    /**
     * 获取轮下面的课
     * @param  $int_round_id
     * @return $array_result
     */
    public function get_class_under_round_id($int_round_id)
    {
        $array_result = array();
        $sql = "SELECT lesson_id,course_id,round_id FROM class WHERE round_id = ".$int_round_id." 
                AND parent_id > 0 AND parent_id !='' ORDER BY sequence ASC";
        $array_result = $this->db->query($sql)->result_array();
        return  $array_result;
    }

}