<?php
    class Model_index extends NH_Model{
        /**
         * 预估某个时间段的总上课人数
         * @author shangshikai@tizi.com
         */
        public function num_goclass($str_start_time,$str_end_time)
        {
            $sql="SELECT count(student_class.class_id) as con, class.id, class.title, class.round_id, round.caps, round.title as round_title
FROM class
LEFT JOIN round ON round.id=class.round_id LEFT JOIN student_class ON student_class.class_id=class.id
WHERE class.begin_time<= $str_start_time
AND (class.end_time>= $str_end_time OR class.end_time>=$str_start_time)
AND class.status!= 4
AND class.status!= 5
AND class.status!= 6";
            return $this->db->query($sql)->result_array();
        }
    }