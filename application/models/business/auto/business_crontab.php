<?php
    class Business_crontab extends NH_Model
    {

        public function __construct()
        {
            parent::__construct();
            $this->load->model('model/auto/model_crontab');
        }

        /**
         * 获取当前时间+30分钟 == 上课时间的课id
         */
        public function get_class_id($type)
        {
        	if ($type == '1')
        	{
        		$time = time()+30*60;
        	}
        	$this->model_crontab->get_class_id($time);
        }

    }