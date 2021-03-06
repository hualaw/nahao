<?php
    class Model_count_time_num extends NH_Model{
        /**
         * 预估某个时间段的总上课人数
         * @author shangshikai@tizi.com
         */
        public function num_goclass($str_start_time,$str_end_time)
        {
            $sql="SELECT class.id, class.status, class.title, class.round_id, round.caps, round.title as round_title, class.begin_time, class.end_time, class.classroom_id
    FROM class
    LEFT JOIN round ON round.id=class.round_id
    WHERE (class.begin_time<= $str_start_time OR class.begin_time<$str_end_time)
    AND (class.end_time>= $str_end_time OR class.end_time>$str_start_time)
    AND class.status!= 6
    AND round.teach_status!=3
    AND round.sale_status!=0
    AND round.sale_status!=1
    AND round.sale_status!=6";
            return $this->db->query($sql)->result_array();
        }
        
    }