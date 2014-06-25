<?php
    class Business_class_status extends NH_Model
    {

        public function __construct()
        {
            parent::__construct;
            $this->load->model('model/api/model_class_status');
        }

        /**
         * 用户进出教室调用接口
         * @author shangshikai@tizi.com
         */
        public function status_classroom($classroom_status)
        {
            $arr_data=array(
                TABLE_ENTERING_CLASSROOM.'.user_id'=>$classroom_status['uid'],
                TABLE_ENTERING_CLASSROOM.'.classroom_id'=>$classroom_status['cid'],
                TABLE_ENTERING_CLASSROOM.'.ip'=>$classroom_status['ip'],
                TABLE_ENTERING_CLASSROOM.'.type'=>$classroom_status['type'],
                TABLE_ENTERING_CLASSROOM.'.action'=>$classroom_status['action'],
                TABLE_ENTERING_CLASSROOM.'.create_time'=>time()
            );
            return $this->model_class_status->classrooms_status($arr_data);
        }
        /**
         * 用户上课下课调用接口
         * @author shangshikai@tizi.com
         */
        public function status_class($class_status)
        {
            if($class_status['action']==1)
            {
                $class_status['action']=4;
            }
            if($class_status['action']==2)
            {
                $class_status['action']=5;
            }
            $arr_data=array(
                TABLE_CLASS_ACTION_LOG.'.user_id'=>$class_status['uid'],
                TABLE_CLASS_ACTION_LOG.'.classroom_id'=>$class_status['cid'],
                TABLE_CLASS_ACTION_LOG.'.ip'=>$class_status['ip'],
                TABLE_CLASS_ACTION_LOG.'.type'=>$class_status['type'],
                TABLE_CLASS_ACTION_LOG.'.action'=>$class_status['action'],
                TABLE_CLASS_ACTION_LOG.'.create_time'=>time()
            );
            return $this->model_class_status->classs_status($arr_data);
        }
    }