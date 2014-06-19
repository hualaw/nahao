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
     * @param  $int_user_id
     * @return $array_return
     */
    public function get_exercise_data($int_class_id,$int_max_sequence,$int_user_id)
    {
        
        #获取练习题的题目id数组
        $array_qid = $this->get_exercise_qid($int_class_id,$int_max_sequence);
        //var_dump($array_qid); echo '----';
        #去答题记录表中查看用户是否做过当前批次的题，将这些题的id出来
        $array_qid_done = $this->get_student_question_qid($int_class_id,$int_max_sequence,$int_user_id);
        //var_dump($array_qid_done);die;
        if (empty($array_qid))
        {
            $array_return = array('status'=>'error','msg'=>'获取练习题失败');
        }
        #在当前批次中将已经做过的题去掉
        $array_diff = array_diff($array_qid, $array_qid_done);
        //var_dump($array_diff);die;
        if (empty($array_diff))
        {
            $array_return = array('status'=>'error','msg'=>'用户已经做过题了');
        }
        #根据练习题的题目id，获取练习题的具体数据
        if ($array_diff)
        {
            foreach ($array_diff as $k=>$v)
            {
                $array_infor = $this->model_classroom->get_question_infor($v);
                #处理数据
                $array_infor['options'] = json_decode($array_infor['options'],true);
                $array_infor['sequence'] = $int_max_sequence;
                $array_infor['class_id'] = $int_class_id;
                $array_data[] = $array_infor;
            }
            $array_return = array('status'=>'ok','data'=>$array_data);
        }
        return $array_return;
    }
    
    /**
     * 获取练习题的题目id数组
     * @param  $int_class_id
     * @param  $int_max_sequence
     * @return $array_return
     */
    public function get_exercise_qid($int_class_id,$int_max_sequence)
    {
        $array_return = array();
        $array_qid = $this->model_classroom->get_exercise_qid($int_class_id,$int_max_sequence);
        if ($array_qid)
        {
            foreach ($array_qid as $k=>$v)
            {
                $array_return[]  = $v['question_id'];
            }
        }
        
        return $array_return;
    }
    
    /**
     * 去答题记录表中查看用户是否做过当前批次的题，将这些题的id出来
     * @param  $int_class_id
     * @param  $int_max_sequence
     * @param  $int_user_id
     * @return $array_return
     */
    public function get_student_question_qid($int_class_id,$int_max_sequence,$int_user_id)
    {
        $array_return = array();
        $array_qid = $this->model_classroom->get_student_question_qid($int_class_id,$int_max_sequence,$int_user_id);
        if ($array_qid)
        {
            foreach ($array_qid as $k=>$v)
            {
                $array_return[]  = $v['question_id'];
            }
        }
    
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
        $error_num = $this->model_classroom->get_student_statistics($array_data,0);
        
        #处理数据
        $array_return['count_score'] = (100/$count)*$right_num;
        $array_return['right_num'] = $right_num;
        $array_return['error_num'] = $error_num;
        $array_return['data_question'] = $array_qids;

        return $array_return;
    }

    /**
     * 保存课堂笔记
     * @param $arr_data
     * @return bool
     * @author yanrui@tizi.com
     */
    public function save_class_note($arr_data)
    {
        $bool_return = false;
        if (is_array($arr_data) AND $arr_data) {
            $bool_return = $this->model_classroom->save_class_note($arr_data);
        }
        return $bool_return;
    }

    /**
     * 取课堂笔记
     * @param $arr_param
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_class_note($arr_param){
        $arr_return = array();
        if (is_array($arr_param) AND $arr_param) {
            $arr_return = $this->model_classroom->get_class_note($arr_param);
        }
        return $arr_return;
    }
    
}