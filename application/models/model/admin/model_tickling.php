<?php
    class Model_tickling extends NH_Model
    {
        /**
         * 展示反馈
         * @author shangshikai@tizi.com
         */
        public function list_tickling($nickname,$content,$create_time,$start_time,$end_time,$email)
        {
            self::sql($nickname,$content,$create_time,$start_time,$end_time,$email);
            $this->db->order_by('create_time','desc');
            return $this->db->get()->result_array();
        }
        /**
         * 数量
         * @author shangshikai@tizi.com
         */
        public function total_tickling($nickname,$content,$create_time,$start_time,$end_time,$email)
        {
            self::sql($nickname,$content,$create_time,$start_time,$end_time,$email);
            return $this->db->get()->num_rows();
        }
        /**
         * 拼装sql搜索语句
         * @author shangshikai@tizi.com
         */
        public function sql($nickname,$content,$create_time,$start_time,$end_time,$email)
        {
            $this->db->select(TABLE_FEEDBACK.'.id,nickname,content,create_time,email')->from(TABLE_FEEDBACK);
            if($nickname!='')
            {
                $this->db->where(TABLE_FEEDBACK.'.nickname',$nickname);
            }
            if($content!='')
            {
                $this->db->like(TABLE_FEEDBACK.'.content',$content);
            }
            if($create_time!='')
            {
                $this->db->where(TABLE_FEEDBACK.'.create_time>=',$start_time);
                $this->db->where(TABLE_FEEDBACK.'.create_time<',$end_time);
            }
            if($email!='')
            {
                $this->db->where(TABLE_FEEDBACK.'.email',$email);
            }
        }
    }