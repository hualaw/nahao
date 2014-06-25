<?php
    class Model_class_status extends NH_Model
    {
        /**
         * 用户进出教室调用接口
         * @author shangshikai@tizi.com
         */
        public function classrooms_status($arr_data)
        {
            $this->db->insert(TABLE_ENTERING_CLASSROOM,$arr_data);
            return 1;
        }
        /**
         * 用户上课下课调用接口
         * @author shangshikai@tizi.com
         */
        public function classs_status($arr_data)
        {
            $this->db->insert(TABLE_CLASS_ACTION_LOG,$arr_data);
            return 1;
        }
    }