<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_Course extends NH_Model{
    
    function __construct(){
        parent::__construct();
    }
    
    /**
     * 检查这个$int_round_id是否有效：在销售中的轮
     * @param  $int_round_id
     * @return $bool_result
     */
    public function check_round_id($int_round_id)
    {
        $sql = "SELECT id FROM ".TABLE_ROUND." WHERE id = ".$int_round_id." AND (sale_status =".ROUND_SALE_STATUS_SALE." )";
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
        $sql = "SELECT id,title,img,video,subtitle,start_time,end_time,sell_begin_time,sell_end_time,score,price,sale_price,sale_status,bought_count,caps,intro,students,description,teach_status,reward,grade_to,grade_from,is_test,course_id,subject,stage,is_live,education_type,material_version,extra_bought_count,quality,sequence FROM ".TABLE_ROUND." WHERE id = ".$int_round_id;
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
        $sql = "SELECT id,title,sequence FROM ".TABLE_CLASS." WHERE parent_id = 0  AND round_id = ".$int_round_id." ORDER BY sequence ASC";
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
        $sql = "SELECT id,title,begin_time,end_time,status,classroom_id,sequence FROM ".TABLE_CLASS." WHERE parent_id = ".$int_parent_id." AND round_id = ".$int_round_id." ORDER BY sequence ASC";
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
        $sql = "SELECT id,title,begin_time,end_time,status,classroom_id,sequence FROM ".TABLE_CLASS." WHERE parent_id = 1 AND round_id = ".$int_round_id." ORDER BY sequence ASC";
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
        $sql = "SELECT course_id FROM ".TABLE_ROUND." WHERE id = ".$int_round_id;
        $array_result = $this->db->query($sql)->row_array();
        return $int_result = empty($array_result['course_id']) ? false : $array_result['course_id'];
    }
    
    /**
     * 获取该课程所有评价（取审核通过的5条）
     * @param  $int_course_id
     * @return $int_result
     */
    public function get_round_evaluate($int_course_id,$limit)
    {
        $array_result = array();
        $sql = "SELECT student_id,nickname,content,create_time,score FROM ".TABLE_CLASS_FEEDBACK." WHERE course_id = ".$int_course_id." AND is_show = 1 AND score >=4 ORDER BY create_time DESC LIMIT ".$limit;
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
        $sql = "SELECT COUNT(id) AS num FROM ".TABLE_CLASS_FEEDBACK." WHERE course_id = ".$int_course_id." AND is_show =1";
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
        $limit = '';
        if ($int_type >= TEACH_SPEAKER)
        {
            $where.= " AND role = ".$int_type;
        }
        #如果只取一个主讲（如首页课程列表）
        if ($int_type == TEACH_SPEAKER)
        {
        	$limit.= " limit 1";
        }
        $array_result = array();
        $sql = "SELECT teacher_id,role FROM ".TABLE_ROUND_TEACHER_RELATION." WHERE round_id = ".$int_round_id.$where." ORDER BY sequence ASC".$limit;
        $array_result = $this->db->query($sql)->result_array();
        return  $array_result;
    }
    
    /**
     * 根据course_id获取该课程下的所有轮（在销售中）
     * @param  $int_course_id
     * @return $array_result
     */
    public function get_all_round($int_course_id)
    {
        $array_result = array();
        $sql = "SELECT id,start_time,end_time,sell_begin_time,sell_end_time FROM ".TABLE_ROUND." WHERE course_id = ".$int_course_id." AND (sale_status = ".ROUND_SALE_STATUS_SALE." ) ORDER BY course_id";
        //echo $sql;die;
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
        $sql = "SELECT id,course_id,round_id FROM ".TABLE_CLASS." WHERE round_id = ".$int_round_id." AND parent_id > 0  ORDER BY sequence ASC";
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
        $sql = "SELECT DISTINCT student_id FROM ".TABLE_STUDENT_CLASS." WHERE round_id = ".$int_round_id;
        $array_result = $this->db->query($sql)->result_array();
        return  $array_result;
    }
    
    /**
     * 课程公告(先按置顶时间倒序，再按创建时间倒序)
     * @param  $int_round_id
     * @return $array_result
     */
    public function get_class_note_data($int_round_id)
    {
        $array_result = array();
        $sql = "SELECT round_id,author,author_role,content,create_time FROM ".TABLE_ROUND_NOTE." WHERE (round_id = ".$int_round_id." OR round_id = 0) AND status = 3 ORDER BY top_time DESC,create_time DESC LIMIT 5";
        //echo $sql;die;
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
        $sql = "SELECT title,begin_time,end_time,classroom_id,status FROM ".TABLE_CLASS." WHERE round_id = ".$int_round_id." AND (status = ".CLASS_STATUS_SOON_CLASS." OR status = ".CLASS_STATUS_ENTER_ROOM." OR status = ".CLASS_STATUS_CLASSING.") AND parent_id !=0 LIMIT 1";
        $array_result = $this->db->query($sql)->row_array();
        //echo $sql;die;
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
        $sql = "SELECT id FROM ".TABLE_STUDENT_CLASS." WHERE round_id = ".$int_round_id." AND student_id = ".$int_user_id;
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
        $this->db->insert(TABLE_CLASS_FEEDBACK, $array_data);
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
        $sql = "SELECT content,classroom_id FROM ".TABLE_CLASS_NOTE." WHERE classroom_id = ".$int_classroom_id." AND student_id = ".$int_user_id." AND del = 0";
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
        $sql = "SELECT title FROM ".TABLE_CLASS." WHERE classroom_id = ".$int_classroom_id." AND parent_id>0";
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
        $sql = "SELECT id FROM ".TABLE_CLASS_FEEDBACK." WHERE student_id = ".$int_user_id." AND class_id = ".$int_class_id;
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
        $sql = "SELECT id,course_id,round_id,lesson_id,title,begin_time,end_time,sequence,status,classroom_id,courseware_id FROM ".TABLE_CLASS." WHERE id = ".$int_class_id;
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
        $int_result = $this->db->query($sql)->num_rows();
        return  $int_result > 0 ? true : false;
    }
    
    /**
     * 判断是否是这节课的老师
     * @param  $int_user_id
     * @param  $int_round_id
     * @return boolean
     */
    public function check_is_teacher_in_class($int_user_id,$int_round_id)
    {
    	$sql = "SELECT id FROM ".TABLE_ROUND_TEACHER_RELATION." WHERE teacher_id=".$int_user_id." AND round_id = ".$int_round_id;
    	$int_result = $this->db->query($sql)->num_rows();
    	return  $int_result > 0 ? true : false;
    }
    
    /**
     * 获取一节课的状态（student_class）
     * @param  $int_user_id
     * @param  $int_class_id
     * @return $array_result
     */
    public function get_student_class_status($int_user_id,$int_class_id)
    {
    	$sql = "SELECT status  FROM  ".TABLE_STUDENT_CLASS." WHERE student_id =".$int_user_id." AND class_id = ".$int_class_id;
    	$array_result = $this->db->query($sql)->row_array();
    	return  $array_result;
    }
    
    /**
     * 未开始课次
     * @param  $int_round_id
     * @return $array_result['num']
     */
    public function get_uncalss_count($int_round_id)
    {
    	$sql = "SELECT COUNT(id) AS num FROM ".TABLE_CLASS." WHERE round_id = ".$int_round_id." AND parent_id > 0 AND (status = ".CLASS_STATUS_INIT." OR status=".CLASS_STATUS_SOON_CLASS." OR status =".CLASS_STATUS_MISS_CLASS." OR status=".CLASS_STATUS_FORI_CLASS.")";
    	$array_result = $this->db->query($sql)->row_array();
    	return  $array_result['num'];
    }
    
    /**
     * 课时总数
     * @param  $int_round_id
     */
    public function get_calss_hour_totle($int_round_id)
    {
    	$array_result = array();
    	$sql = "SELECT SUM(school_hour) AS num FROM ".TABLE_CLASS." WHERE round_id =".$int_round_id." AND parent_id >0";
    	$array_result = $this->db->query($sql)->row_array();
    	return  $array_result;
    }
    
    /**
     * 获取课程的评分
     * @param  $int_course_id
     */
    public function get_course_score($int_course_id)
    {
    	$array_result = array();
    	$sql = "SELECT score FROM ".TABLE_COURSE." WHERE id =".$int_course_id;
    	$array_result = $this->db->query($sql)->row_array();
    	return  $array_result;
    }
    
    /**
     * 检查这个$int_round_id是否有效：（轮id是否存在）
     * @param  $int_round_id
     * @return $bool_result
     */
    public function check_round_id_is_exist($int_round_id)
    {
    	$sql = "SELECT id FROM ".TABLE_ROUND." WHERE id = ".$int_round_id;
    	$int_num = $this->db->query($sql)->num_rows();
    	$bool_result = $int_num > 0 ? true : false;
    	return $bool_result;
    }
    
    /**
     * 检查这个$int_round_id是否在（销售中、已售罄、已停售、已下架）的状态中
     * @param $int_round_id
     * @return $bool_result
     */
    public function check_round_status($int_round_id)
    {
    	$sql = "SELECT id FROM ".TABLE_ROUND." WHERE id = ".$int_round_id." AND (sale_status =".ROUND_SALE_STATUS_SALE." OR sale_status = ".ROUND_SALE_STATUS_OVER." OR sale_status = ".ROUND_SALE_STATUS_FINISH." OR sale_status = ".ROUND_SALE_STATUS_OFF.")";
    	$int_num = $this->db->query($sql)->num_rows();
    	$bool_result = $int_num > 0 ? true : false;
    	return $bool_result;
    }
    
    /**
     * 该课程系列的其他课程
     * @param  $array_data
     * @return $array_result
     */
    public function get_other_round_data($array_data)
    {
    	switch ($array_data['education_type']){
    		case ROUND_TYPE_SUBJECT :
    			$sql = "SELECT id,title,sale_price,img,SUM(bought_count+extra_bought_count) AS study_count FROM ".TABLE_ROUND." WHERE grade_from = ".$array_data['grade_from']." AND grade_to = ".$array_data['grade_to']." AND subject = ".$array_data['subject']." AND id != ".$array_data['round_id']." ORDER BY buy_num DESC LIMIT ".$array_data['limit'];
    			break;
    		case ROUND_TYPE_EDUCATION :
    			$sql = "SELECT id,title,sale_price,img,SUM(bought_count+extra_bought_count) AS study_count FROM ".TABLE_ROUND." WHERE subject = ".$array_data['subject']." AND id != ".$array_data['round_id']." ORDER BY buy_num DESC LIMIT ".$array_data['limit'];
    			break;
    	}
    	$array_result = array();
    	$array_result = $this->db->query($sql)->result_array();
    	return  $array_result;
    }
    
    /**
     * 看过本课程的用户还看了
     * @param  $array_data
     * @return $array_result
     */
    public function get_recommend_round_data($array_data)
    {
    	switch ($array_data['education_type']){
    		case ROUND_TYPE_SUBJECT :
    			$sql = "SELECT id,title,sale_price,img,SUM(bought_count+extra_bought_count) AS study_count FROM ".TABLE_ROUND." WHERE grade_from = ".$array_data['grade_from']." AND grade_to = ".$array_data['grade_to']." AND subject != ".$array_data['subject']." AND id != ".$array_data['round_id']." ORDER BY buy_num DESC LIMIT ".$array_data['limit'];
    			break;
    		case ROUND_TYPE_EDUCATION :
    			$sql = "SELECT id,title,sale_price,img,SUM(bought_count+extra_bought_count) AS study_count FROM ".TABLE_ROUND." WHERE subject != ".$array_data['subject']." AND id != ".$array_data['round_id']." ORDER BY buy_num DESC LIMIT ".$array_data['limit'];
    			break;
    	}
    	$array_result = array();
    	$array_result = $this->db->query($sql)->result_array();
    	return  $array_result;
    }
    
    /**
     * 重要提醒
     * @return  $array_result
     */
    public function get_important_notice_data()
    {
    	$array_result = array();
    	$sql = "SELECT content FROM ".TABLE_ROUND_NOTE." WHERE round_id = 0 ORDER BY top_time DESC LIMIT 1";
    	$array_result = $this->db->query($sql)->row_array();
    	return  $array_result;
    }
}