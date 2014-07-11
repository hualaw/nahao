<?php
    class Model_index extends NH_Model{
        /**
         * 预估某个时间段的总上课人数
         * @author shangshikai@tizi.com
         */
        public function num_goclass($str_start_time,$str_end_time)
        {
            $this->db->select(TABLE_ROUND.'.id,title,caps')->from(TABLE_ROUND);
            $this->db->where(TABLE_ROUND.'.start_time<=',$str_start_time);
            $this->db->where(TABLE_ROUND.'.end_time>=',$str_end_time);
            return $this->db->get()->result_array();
        }
    }