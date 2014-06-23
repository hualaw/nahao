<?php
    class Business_Feedback extends NH_Model
    {

        function __construct(){
            parent::__construct();
            $this->load->model('model/admin/model_feedback');
        }
        /**
         * 评价展示
         * @author shangshikai@tizi.com
         */
        public function feedback_list($course_id,$round_id,$student_id,$class_id,$content,$score_start,$score_end,$start_time,$end_time)
        {
            //echo $score_start,$score_end;
            $content=trim($content);
            $course_id=trim($course_id);
            $round_id=trim($round_id);
            $student_id=trim($student_id);
            $class_id=trim($class_id);
            $start_time=strtotime($start_time);
            $end_time=strtotime($end_time);
            if($start_time=="" || $end_time=="")
            {
                $start_time="";
                $end_time="";
            }
            return $this->model_feedback->list_feedback($course_id,$round_id,$student_id,$class_id,$content,$score_start,$score_end,$start_time,$end_time);
        }
        /**
         * 评价数量
         * @author shanshikai@tizi.com
         */
        public function feedback_total($course_id,$round_id,$student_id,$class_id,$content,$score_start,$score_end,$start_time,$end_time)
        {
            $content=trim($content);
            $course_id=trim($course_id);
            $round_id=trim($round_id);
            $student_id=trim($student_id);
            $class_id=trim($class_id);
            $start_time=strtotime($start_time);
            $end_time=strtotime($end_time);
            if($start_time=="" || $end_time=="")
            {
                $start_time="";
                $end_time="";
            }
            return $this->model_feedback->total_feedback($course_id,$round_id,$student_id,$class_id,$content,$score_start,$score_end,$start_time,$end_time);
        }
        /**
         * 切换评价状态
         * @author sahngshikai@tizi.com
         */
        public function changing_over_feedback($id,$a)
        {
            return $this->model_feedback->feedback_changing_over($id,$a);
        }
    }