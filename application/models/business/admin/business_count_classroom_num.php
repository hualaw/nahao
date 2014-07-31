<?php
    class Business_count_classroom_num extends NH_Model
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('model/admin/model_count_classroom_num');
        }
        /**
         * 展示人数统计
         * @author shangshikai@tizi.com
         */
        public function classroom_num($type)
        {
            $arr=$this->model_count_classroom_num->count_classroom($type);
            foreach($arr as $k=>$v)
            {
                $arr[$k]['tel']=get_pnum_phone_server($v['user_id']);
            }
            return $arr;
        }
        /**
         * 总数
         * @author shangshikai@tizi.com
         */
        public function total()
        {
            return $this->model_count_classroom_num->count_num();
        }
    }