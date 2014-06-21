<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_Course extends NH_Model{
    
    function __construct(){
        parent::__construct();
    }
    
    /**
     * 检查这个$int_round_id是否有效：在预售和销售中的轮
     * @param  $int_round_id
     * @return $bool_result
     */
    public function check_round_id($int_round_id)
    {
        $sql = "SELECT id FROM round WHERE id = ".$int_round_id." AND sale_status >=2 AND sale_status<=3";
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
        $sql = "SELECT id,title,img,video,subtitle,start_time,end_time,sell_begin_time,sell_end_time,score,
                price,sale_price,sale_status,bought_count,caps,intro,students,description,teach_status,reward
                FROM round WHERE id = ".$int_round_id;
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
        $sql = "SELECT id,title,sequence FROM class WHERE parent_id = 0  AND round_id = ".$int_round_id.
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
        $sql = "SELECT id,title,begin_time,end_time,status,classroom_id,sequence FROM class WHERE parent_id = ".$int_parent_id.
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
        $sql = "SELECT id,title,begin_time,end_time,status,classroom_id,sequence FROM class WHERE parent_id = 1".
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
        $sql = "SELECT student_id,nickname,content,create_time,score FROM class_feedback
                WHERE course_id = ".$int_course_id." AND is_show = 1 ORDER BY create_time DESC LIMIT 5";
        $array_result = $this->db->query($sql)->result_array();
        return  $array_result;
    }
    
    /**
     * 获取评价总数
     * @param  $int_course_id
     * @return $array_result
     */
    public function get_evaluate_count($int_course_id)
    {
        $array_result = array();
        $sql = "SELECT COUNT(id) AS num FROM class_feedback WHERE course_id = ".$int_course_id;
        $array_result = $this->db->query($sql)->row_array();
        return $array_result;
    }
    
    /**
     * 根据$int_round_id获取该轮的课程团队
     * @param  $int_round_id
     * @param  $int_type "-1为所有教师团队"
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
        //echo $sql.'-----------';
        $array_result = $this->db->query($sql)->result_array();
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
                AND parent_id > 0  ORDER BY sequence ASC";
        $array_result = $this->db->query($sql)->result_array();
        return  $array_result;
    }

    /**
     * 课堂同学
     * @param  $int_round_id
     * @return $array_result
     */
    public function get_classmate_data($int_round_id)
    {
        $array_result = array();
        $sql = "SELECT DISTINCT student_id FROM student_class WHERE round_id = ".$int_round_id;
        $array_result = $this->db->query($sql)->result_array();
        return  $array_result;
    }
    
    /**
     * 课程公告
     * @param  $int_round_id
     * @return $array_result
     */
    public function get_class_note_data($int_round_id)
    {
        $array_result = array();
        $sql = "SELECT author,author_role,content,create_time FROM round_note WHERE round_id = ".$int_round_id."
                AND status = 3 ORDER BY id DESC";
        $array_result = $this->db->query($sql)->result_array();
        return  $array_result;
    }
    
    /**
     * 购买后 -- 即将开始上课的信息
     * @param  $int_round_id
     * @return $array_result
     */
    public function get_soon_class_data($int_round_id)
    {
        $array_result = array();
        $sql = "SELECT title,begin_time,end_time,classroom_id,status FROM class WHERE round_id = ".$int_round_id." 
                AND status = 1 AND parent_id !=0";
        $array_result = $this->db->query($sql)->row_array();
        return  $array_result;
    }
    
    /**
     * 检查学生是否购买此轮
     * @param  $int_user_id
     * @param  $int_round_id
     * @return $bool_result
     */
    public function check_student_buy_round($int_user_id,$int_round_id)
    {
        
        $sql = "SELECT id FROM student_class WHERE round_id = ".$int_round_id."
                AND student_id = ".$int_user_id;
        $int_rows = $this->db->query($sql)->num_rows();
        return  $bool_result = $int_rows > 0 ? true : false;
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
    
    /**
     * 根据教室id查找云笔记
     * @param  $int_classroom_id
     * @param  $int_user_id
     * @return $array_result
     */
    public function get_user_cloud_notes($int_classroom_id,$int_user_id)
    {
        $array_result = array();
        $sql = "SELECT content,classroom_id FROM class_note WHERE classroom_id = ".$int_classroom_id." 
                AND student_id = ".$int_user_id." AND del = 0";
        $array_result = $this->db->query($sql)->row_array();
        return  $array_result;
    }
    
    
    /**
     * 获取云笔记对应的课的标题
     * @param  $int_classroom_id
     * @return $array_result 
     */
    public function get_user_cloud_notes_class($int_classroom_id)
    {
        $array_result = array();
        $sql = "SELECT title FROM class WHERE classroom_id = ".$int_classroom_id." AND parent_id>0";
        $array_result = $this->db->query($sql)->row_array();
        return  $array_result;
    }
    
    /**
     * 检查课是否被当前用户评论
     * @param  $int_class_id
     * @param  $int_user_id
     * @return boolean
     */
    public function check_class_comment($int_class_id,$int_user_id)
    {
        $sql = "SELECT id FROM class_feedback WHERE student_id = ".$int_user_id."
                AND class_id = ".$int_class_id;
        $int_row = $this->db->query($sql)->num_rows();
        return  $int_row > 0 ? true : false;
    }

    /**
     * 根据课的id获取课的信息
     * @param  $int_class_id
     * @param  $array_result
     */
    public function get_class_infor($int_class_id)
    {
        $array_result = array();
        $sql = "SELECT course_id,round_id,lesson_id,title,begin_time,end_time,sequence,status,classroom_id
                FROM class WHERE id = ".$int_class_id;
        $array_result = $this->db->query($sql)->row_array();
        return  $array_result;
    }
    
    /**
     * 检查学生是否购买了这堂课
     * @param  $int_user_id
     * @param  $int_class_id
     * @return boolean
     */
    public function check_user_buy_class($int_user_id,$int_class_id)
    {
        $sql = "SELECT id FROM ".TABLE_STUDENT_CLASS." WHERE student_id = ".$int_user_id." AND class_id = ".$int_class_id;
//        o($sql,true);
        $int_result = $this->db->query($sql)->num_rows();
        return  $int_result > 0 ? true : false;
    }
}