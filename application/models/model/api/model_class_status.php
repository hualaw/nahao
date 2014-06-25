<?php
    class Model_class_status extends NH_Model
    {
        /**
         * 用户进出教室调用接口
         * @author shangshikai@tizi.com
         */
        public function classrooms_status($arr_data)
        {
            if($this->db->insert(TABLE_ENTERING_CLASSROOM,$arr_data))
            {
                $id=$this->db->insert_id();
                log_message('info_nahao','Successful execution:'.$id);
                return 1;
            }
            else
            {
                log_message('error_nahao','Failed to execute:'.print_r($arr_data,1));
                return 0;
            }
        }
        /**
         * 用户上课下课调用接口
         * @author shangshikai@tizi.com
         */
        public function classs_status($arr_data)
        {
            if($this->db->insert(TABLE_CLASS_ACTION_LOG,$arr_data))
            {
                $id=$this->db->insert_id();
                log_message('info_nahao','Successful execution:'.$id);
                return 1;
            }
            else
            {
                log_message('error_nahao','Failed to execute:'.print_r($arr_data,1));
                return 0;
            }
        }
    }