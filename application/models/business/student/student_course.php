<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student_Course extends NH_Model{
    
    function __construct(){
        parent::__construct();
        $this->load->model('model/student/model_course');
        $this->load->model('model/student/model_index');
        $this->load->model('model/student/model_index');
        $this->load->model('model/student/model_member');
    }
    
    /**
     * 从首页链接到课程购买前页面  获取一个课程下的所有轮（在审核通过和销售中）
     * 根据$int_round_id获取对应课程下的所有轮
     * @param  $int_round_id
     * @return $array_result
     */
    public function get_all_round_under_course($int_round_id)
    {
        #根据$int_round_id获取course_id
        $int_course_id = $this->model_course->get_course_id($int_round_id);
        if (!$int_course_id)
        {
            show_error("course错误");
        }
        $array_result = array();
        #根据course_id获取该课程下的所有轮（在审核通过和销售中）
        $array_result = $this->model_course->get_all_round($int_course_id);
        if ($array_result)
        {
            foreach ($array_result as $k=>$v)
            {
                $array_result[$k]['start_time'] = date("m月d日",$v['start_time']);
                $array_result[$k]['end_time'] = date("m月d日",$v['end_time']);
            }
        }
        return $array_result;
    }
    
    /**
     * 检查这个$int_round_id是否有效
     * @param  $int_round_id
     * @return $bool_return
     */
    public function check_round_id($int_round_id)
    {
        $bool_return = $this->model_course->check_round_id($int_round_id);
        return $bool_return;
    }
    
    /**
     * 根据$int_round_id获取该轮的部分信息
     * @param  $int_round_id
     * @return $array_return
     */
    public function get_round_info($int_round_id)
    {
        $array_return = array();
        #根据$int_round_id获取该轮的部分信息
        $array_return = $this->model_course->get_round_info($int_round_id);
        if ($array_return)
        {
            #售罄人数
            $array_return['sold_out_count'] = $array_return['caps'] - $array_return['bought_count'];
            #一轮有几次课
            $class_nums = $this->model_index->round_has_class_nums($int_round_id);
            #课次
            $array_return['class_nums'] = $class_nums;
            #课时
            $array_return['class_hour'] = $class_nums*2;
            #图片地址
            $array_return['class_img'] = empty( $array_return['img']) ? HOME_IMG_DEFAULT : $array_return['img'];
        }
        return $array_return;
    }
    
    /**
     * 根据$int_round_id获取该轮的课程大纲
     * @param  $int_round_id
     * @return $array_return
     */
    public function get_round_outline($int_round_id)
    {   
        $array_return = array();
        #获取该轮下的所有章
        $array_chapter = $this->model_course->get_all_chapter($int_round_id);
        #如果有章
        if ($array_chapter)
        {
            foreach ($array_chapter as $k=>$v)
            {
                $array_chapter[$k]['son'] = $this->get_one_chapter_children($v['id'],$int_round_id);
            }
            return $array_chapter;
        } else {
            #没有章，获取该轮下的所有节
            $array_return[] = $this->get_all_section($int_round_id);
            return $array_return;
        }
    }
    
    /**
     * 如果有章，获取下面的节
     * @param  $int_chapter_id
     * @param  $int_round_id
     * @return $array_return
     */
    public function get_one_chapter_children($int_chapter_id,$int_round_id)
    {
        $array_return = array();
        $array_return = $this->model_course->get_one_chapter_children($int_chapter_id,$int_round_id);
        if ($array_return)
        {
            foreach ($array_return as $key=>$val)
            {
                 $array_return[$key]['time'] = $this->handle_time($val['begin_time'], $val['end_time']);
            }
        }
        return $array_return;
    }
    
    /**
     * 如果没有章，取节
     * @param  $int_round_id
     * @return $array_return
     */
    public function get_all_section($int_round_id)
    {
        $array_return = array();
        $array_result = $this->model_course->get_all_section($int_round_id);
        if ($array_result)
        {
            foreach ($array_result as $key=>$val)
            {
                $array_result[$key]['time'] = $this->handle_time($val['begin_time'], $val['end_time']);
            }
        }
        $array_return['id'] = 1;
        $array_return['title'] = '';
        $array_return['son'] = $array_result;
        return $array_return;
    }
    
    /**
     * 处理课程大纲章节开课时间
     * @param  $begin_time
     * @param  $begin_time
     * @return $str
     */
    public function handle_time($begin_time,$end_time)
    {
        $str_year = date("Y-m-d",$begin_time);
        $array_week=array("日","一","二","三","四","五","六");
        $str_day = $array_week[date("w",$begin_time)];
        $str_stime = date("H:i",$begin_time);
        $str_etime = date("H:i",$end_time);
        return $str_year." 星期".$str_day." ".$str_stime."-".$str_etime;
    }
    
    /**
     * 根据$int_round_id获取该轮的课程评价
     * @param  $int_round_id
     * @param  $int_course_id
     * @return $array_return
     */
    public function get_round_evaluate($int_round_id)
    {
        $array_return = array();
        #根据$int_round_id 找course_id
        $int_course_id = $this->model_course->get_course_id($int_round_id);
        if (!$int_course_id)
        {
            show_error("course错误");
        }
        #获取该课程所有评价（取审核通过的5条）
        $array_return = $this->model_course->get_round_evaluate($int_course_id);
        if ($array_return)
        {
            foreach ($array_return as $kk=>$vv)
            {
                $array_return[$kk]['avater'] = $this->model_member->get_user_avater($vv['student_id']);
            }
        }
        return $array_return;
    }
    
    /**
     * 获取课程评价总数
     * @param  $int_round_id
     */
    public function get_evaluate_count($int_round_id)
    {
        #根据$int_round_id 找course_id
        $int_course_id = $this->model_course->get_course_id($int_round_id);
        if (!$int_course_id)
        {
            show_error("course错误");
        }
        $str_evaluate_count = $this->model_course->get_evaluate_count($int_course_id);
        return $str_evaluate_count['num'];
    }
    
    /**
     * 根据$int_round_id获取该轮的课程团队
     * @param  $int_round_id
     * @param  $int_type "-1为所有教师团队"
     * @return $array_return
     */
    public function get_round_team($int_round_id,$int_type= '-1')
    {
        $array_return = array();
        #这个轮里面的所有老师id
        $array_teacher = $this->model_course->get_round_team($int_round_id,$int_type);
        if (empty($array_teacher))
        {
            show_error('无老师信息');
        }
        #common.php里面的数据字典 老师角色
        $array_teacher_role = config_item('teacher_role');
        #获取老师的具体信息
        foreach ($array_teacher as $k=>$v)
        {
            $array_return[] = $this->model_member->get_user_infor($v['teacher_id']);
            $array_return[$k]['teacher_role'] = $array_teacher_role[$k];
            #老师头像
            $array_return[$k]['avater'] = $this->model_member->get_user_avater($v['teacher_id']);
        }
        return $array_return;
    }
    
    /**
     * 课堂同学
     * @param  $int_round_id
     * @return $array_return
     */
    public function get_classmate_data($int_round_id)
    {
        $array_return = array();
        #去学生与课的关系表寻找信息
        $array_return = $this->model_course->get_classmate_data($int_round_id);
        if ($array_return)
        {
            foreach ($array_return as $k=>$v)
            {
                #用户信息
                $array_result = $this->model_member->get_user_infor($v['student_id']);
                
                #处理数据
                $array_return[$k]['avatar'] = empty($array_result['nickname']) ? DEFAULT_AVATER:$array_result['nickname'];
                $array_return[$k]['nickname'] = $array_result['nickname'];
            }
        }
        return $array_return;
    }
    
    /**
     * 课程公告
     * @param  $int_round_id
     * @return $array_return
     */
    public function get_class_note_data($int_round_id)
    {
        $array_return = array();
        #去轮公告表寻找信息
        $array_return = $this->model_course->get_class_note_data($int_round_id);
        if ($array_return)
        {
            foreach ($array_return as $k=>$v)
            {
                if ($v['author_role'] == '-1')
                {
                   #发布者是管理员
                    $array_return[$k]['nickname'] = '管理员';
                    $array_return[$k]['avatar'] =DEFAULT_AVATER;
                } else {
                    #获取发布者的信息
                    $array_result = $this->model_member->get_user_infor($v['author']);
                    $array_return[$k]['nickname'] = $array_result['nickname'];
                    $array_return[$k]['avatar'] = empty($array_result['nickname']) ? DEFAULT_AVATER:$array_result['nickname'];
                }
            }
        }
        return $array_return;
    }
    
    /**
     * 即将上课的信息--购买后顶部
     * @param  $int_round_id
     * @param  $int_user_id
     * @return $array_return
     */
    public function get_soon_class_data($int_user_id,$int_round_id)
    {
        $array_return = array();
        #获取轮的信息
        $array_round = $this->model_course->get_round_info($int_round_id);
        #获取轮的老师团队
        $array_team = $this->get_round_team($int_round_id);
        #即将开始的课的信息
        $array_soon = $this->model_course->get_soon_class_data($int_round_id);
        #已经上了几节课
        $int_num = $this->model_member->get_student_class_done($int_user_id,$int_round_id);
        
        #组合数据
        $array_return['round_id'] = $array_round['id'];
        $array_return['title'] = $array_round['title'];
        $array_return['team'] = $array_team;
        $array_return['soon_class_title'] = $array_soon['title'];
        $array_return['soon_class_stime'] = $array_soon['begin_time'];
        $array_return['class'] = $int_num;
        return $array_return;
    }
    
    /**
     * 检查学生是否购买此轮
     * @param  $int_user_id
     * @param  $int_round_id
     * @return $bool_return
     */
    public function check_student_buy_round($int_user_id,$int_round_id)
    {
        $bool_return = $this->model_course->check_student_buy_round($int_user_id,$int_round_id);
        return $bool_return;
    }
}