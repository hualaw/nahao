<?php
    class Model_index extends NH_Model{
        /**
         * 预估某个时间段的总上课人数
         * @author shangshikai@tizi.com
         */
        public function num_goclass($str_start_time,$str_end_time)
        {
            $this->db->select(TABLE_CLASS.'.id,'.TABLE_CLASS.'.title,'.TABLE_CLASS.'.round_id,'.TABLE_ROUND.'.caps,'.TABLE_ROUND.'.title as round_title')->from(TABLE_CLASS)->join(TABLE_ROUND,TABLE_ROUND.'.id='.TABLE_CLASS.'.round_id','left');
            $this->db->where(TABLE_CLASS.'.begin_time<=',$str_start_time);
            $this->db->where(TABLE_CLASS.'.end_time>=',$str_end_time);
            $this->db->where(TABLE_CLASS.'.status!=',5);
            $this->db->where(TABLE_CLASS.'.status!=',6);
            return $this->db->get()->result_array();
        }
    }