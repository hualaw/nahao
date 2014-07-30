<?php
    class Business_tickling extends NH_Model
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('model/admin/model_tickling');
        }
        /**
         * 展示反馈
         * @author shangshikai@tizi.com
         */
        public function tickling_list($nickname,$content,$create_time,$email)
        {
            $nickname=trim($nickname);
            $content=trim($content);
            $time=strtotime(trim($create_time));
            $year=date('Y',$time);
            $mon=date('m',$time);
            $day=date('d',$time);
            $start_time=mktime(0,0,0,$mon,$day,$year);
            $end_time=mktime(0,0,0,$mon,$day+1,$year);
            $email=trim($email);
            return $this->model_tickling->list_tickling($nickname,$content,$create_time,$start_time,$end_time,$email);
        }
        /**
         * 数量
         * @author shangshikai@tizi.com
         */
        public function tickling_total($nickname,$content,$create_time,$email)
        {
            $nickname=trim($nickname);
            $content=trim($content);
            $time=strtotime(trim($create_time));
            $year=date('Y',$time);
            $mon=date('m',$time);
            $day=date('d',$time);
            $start_time=mktime(0,0,0,$mon,$day,$year);
            $end_time=mktime(0,0,0,$mon,$day+1,$year);
            $email=trim($email);
            return $this->model_tickling->total_tickling($nickname,$content,$create_time,$start_time,$end_time,$email);
        }
    }