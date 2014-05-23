<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Studnet相关逻辑
 * Class Business_Student
 * @author yanrui@91waijiao.com
 */
class Student_Course extends NH_Model{
    
    function __construct(){
        parent::__construct();
        $this->load->model('model/student/model_course');
        $this->load->model('model/student/model_index');
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
        #获取该轮下的所有章
        $array_parent = $this->model_course->get_all_chapter($int_round_id);
        $array_return = array();
        #如果有章
        if ($array_parent)
        {
            foreach ($array_parent as $k=>$v)
            {
                $array_parent[$k]['son'] = $this->model_course->get_one_chapter_children($v['id'],$int_round_id);
            }
            $array_return[] = $array_parent;
        } else {
            #没有章，获取该轮下的所有节
            $array_return[] = $this->model_course->get_all_section($int_round_id);
        }
        return $array_return;
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
        return $array_return;
    }
    
    /**
     * 根据$int_round_id获取该轮的课程团队
     * @param  $int_round_id
     * @return $array_return
     */
    public function get_round_team($int_round_id)
    {
        $array_return = array();
        #这个轮里面的所有老师id
        $array_teacher = $this->model_course->get_round_team($int_round_id);
        if (empty($array_teacher))
        {
            show_error('无老师信息');
        }
        #common.php里面的数据字典 老师角色
        $array_teacher_role = config_item('teacher_role');
        #获取老师的具体信息
        foreach ($array_teacher as $k=>$v)
        {
            $array_return[] = $this->model_course->get_teacher_info($v['teacher_id']);
            $array_return[$k]['teacher_role'] = $array_teacher_role[$k];
        }
        return $array_return;
    }
}