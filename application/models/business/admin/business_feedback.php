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
        public function feedback_list($class_id,$content,$score_start,$score_end,$course_id,$round_id,$student_id)
        {
            //echo $score_start,$score_end;
            $content=trim($content);
            return $this->model_feedback->list_feedback($class_id,$content,$score_start,$score_end,$course_id,$round_id,$student_id);
        }
        /**
         * 评价数量
         * @author shanshikai@tizi.com
         */
        public function feedback_total($class_id,$content,$score_start,$score_end,$course_id,$round_id,$student_id)
        {
            $content=trim($content);
            return $this->model_feedback->total_feedback($class_id,$content,$score_start,$score_end,$course_id,$round_id,$student_id);
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