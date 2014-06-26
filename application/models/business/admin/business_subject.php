<?php
    class Business_subject extends NH_Model
    {

        public function __construct()
        {
            parent::__construct();
            $this->load->model('model/admin/model_subject');
        }
        /**
         * 获取全部学科
         * @author shangshikai@tizi.com
         */
        public function all_subject($status,$name)
        {
            $name=trim($name);
            return $this->model_subject->subject_all($status,$name);
        }
        /**
         * 学科数量
         * @author shangshikai@tizi.com
         */
        public function total_subject($status,$name)
        {
            $name=trim($name);
            return $this->model_subject->subject_total($status,$name);
        }
        /**
         * 禁用学科
         * @author shangshikai@tizi.com
         */
        public function subject_close($ids)
        {
                return $this->model_subject->disabled_subject($ids);
        }
        /**
         * 启用学科
         * @author shangshikai@tizi.com
         */
        public function subject_open($ids)
        {
                return $this->model_subject->open_subject($ids);
        }
    }