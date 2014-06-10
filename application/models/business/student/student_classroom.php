<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student_Classroom extends NH_Model{
    
    function __construct(){
        parent::__construct();
        $this->load->model('model/student/model_classroom');
/*         $this->load->model('model/common/model_redis', 'redis');
        $this->redis->connect('student_answer'); */
        
    }
    
    
    /**
     * 获取练习题数据 
     * @param  $int_class_id
     * @param  $int_max_sequence
     * @return $array_return
     */
    public function get_exercise_data($int_class_id,$int_max_sequence)
    {
        $array_return = array();
        #获取练习题的题目id数组
        $array_qid = $this->model_classroom->get_exercise_qid($int_class_id,$int_max_sequence);
        #根据练习题的题目id，获取练习题的具体数据
        if ($array_qid)
        {
            foreach ($array_qid as $k=>$v)
            {
                $array_infor = $this->model_classroom->get_question_infor($v['question_id']);               
                #处理数据
                $array_infor['options'] = json_decode($array_infor['options'],true);
                $array_return[] = $array_infor;
                
            }
        }
        //var_dump($array_return);die;
        return $array_return;
    }
    
    
    /**
     * 查看做题结果
     * @param  $array_data
     * @return $array_return
     */
    public function get_question_result_data($array_data)
    {
        $array_return = array();
        
        #获取学生做题记录
        $array_qids = $this->model_classroom->get_student_question_data($array_data);
        if ($array_qids)
        {
            foreach ($array_qids as $k=>$v)
            {
                #获取题目具体数据
                $array_infor = $this->model_classroom->get_question_infor($v['question_id']);
                
                #处理数据
                $array_infor['options'] = json_decode($array_infor['options'],true);
                $array_qids[$k] = array_merge($array_qids[$k], $array_infor);
                
            }
        }
        
        #获取学生的做题统计（总分、做对题目数、做错的题目数、出题总数）
        #出题总数
        $count = $this->model_classroom->get_question_count_by_sequence($array_data);
        #做对题目数
        $right_num = $this->model_classroom->get_student_statistics($array_data,1);
        #做错的题目数
        $wrong_num = $this->model_classroom->get_student_statistics($array_data,0);
        
        #处理数据
        $array_return['count'] = $count;
        $array_return['right_num'] = $right_num;
        $array_return['wrong_num'] = $wrong_num;
        $array_return['data_question'] = $array_qids;

        return $array_return;
    }
    
}