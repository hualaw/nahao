<?php
    class Business_affiche extends NH_Model
    {

        public function __construct()
        {
            parent::__construct();
            $this->load->model('model/admin/model_affiche');
        }
        /**
         * 公告展示
         * @author shangshikai@tizi.com
         */
        public function list_affiche($admin_name,$author_role,$content,$start_time,$end_time,$status)
        {
            $admin_name=trim($admin_name);
            $content=trim($content);
            $start_time=strtotime($start_time);
            $end_time=strtotime($end_time);
            return $this->model_affiche->affiche_list($admin_name,$author_role,$content,$start_time,$end_time,$status);
        }
        /**
         * 公告数量
         * @author shangshikai@tizi.com
         */
        public function total_affiche($admin_name,$author_role,$content,$start_time,$end_time,$status)
        {
            $admin_name=trim($admin_name);
            $content=trim($content);
            $start_time=strtotime($start_time);
            $end_time=strtotime($end_time);
            return $this->model_affiche->affiche_total($admin_name,$author_role,$content,$start_time,$end_time,$status);
        }
    }