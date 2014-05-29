<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Studnet相关逻辑
 * Class Business_Student
 * @author yanrui@91waijiao.com
 */
class Student_Index extends NH_Model{
    
    function __construct(){
        parent::__construct();
        $this->load->model('model/student/model_index');
        $this->load->model('business/student/student_course');
    }
    
    /**
     * 首页获取不同课程里面的最新轮组成的列表信息
     * @return array $array_return
     */
    public function get_course_latest_round_list()
    {
        #首页获取一门课程里面最新的一轮（在审核通过和销售中）
        $array_round = $this->model_index->get_course_latest_round();
        $array_return = array();
        if ($array_round)
        {
            #年级
            $array_grade = config_item('grade');
            #首页获取轮的信息列表
            foreach ($array_round as $k=>$v)
            {
                $array_return[] = $this->get_one_round_info($v['course_id'],$v['start_time'],$array_grade);
            }
        }
        return $array_return;
    }
    
    /**
     * 根据course_id和开始时间获取最新一轮的信息
     * @param  $int_course_id
     * @param  $int_start_time
     * @return $array_return
     */
    public function get_one_round_info($int_course_id,$int_start_time,$array_grade)
    {
        $array_return = array();
        $array_return = $this->model_index->get_one_round_info($int_course_id,$int_start_time);
        #获取每一轮里面有几次课
        if ($array_return['id'])
        {
            $int_num = $this->model_index->round_has_class_nums($array_return['id']);
            $array_return['class_nums'] = $int_num;
            #获取该轮里面的主讲老师信息
            $teacher = $this->student_course->get_round_team($array_return['id'],TEACH_SPEAKER);
            $array_return['teacher'] = $teacher['0'];
        }
        #适合人群
        if ($array_return['grade_from'] == $array_return['grade_to'])
        {
            $array_return['for_people'] = $array_grade[$array_return['grade_from']];
        } else {
            if (($array_return['grade_from'] < 6) || ($array_return['grade_to'] <6))
            {
                $array_return['for_people'] = $array_return['grade_from'].'-'.$array_return['grade_to']."年级";
            } else{
                $array_return['for_people'] = $array_grade[$array_return['grade_from']].'-'.$array_grade[$array_return['grade_to']];
            }
        }
        $array_return['start_time'] = date("m月d日",$array_return['start_time']);
        $array_return['end_time'] = date("m月d日",$array_return['end_time']);
        $array_return['img'] = empty($array_return['img']) ? HOME_IMG_DEFAULT : $array_return['img'];
        return $array_return;
    }
}