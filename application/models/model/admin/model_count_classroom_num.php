<?php
    class Model_count_classroom_num extends NH_Model
    {
        /**
         * 展示人数统计
         * @author shangshikai@tizi.com
         */
        public function count_classroom($type)
        {
            if($type==1)
            {
                $sql="select t1.user_id, t2.nickname, t2.phone_mask, t3.realname, count(distinct(t1.classroom_id)) as c from entering_classroom  as t1 left join user as t2 on t1.user_id = t2.id left join user_info as t3 on t1.user_id = t3.user_id group by t1.user_id order by c desc limit 50";
            }
            if($type==2)
            {
                $sql="select t1.user_id, t2.nickname, t2.phone_mask, t3.realname, count(distinct(t1.classroom_id)) as c from entering_classroom  as t1 left join user as t2 on t1.user_id = t2.id left join user_info as t3 on t1.user_id = t3.user_id group by t1.user_id order by c desc";
            }
            return $this->db->query($sql)->result_array();
        }
        /**
         * 总数
         * @author shangshikai@tizi.com
         */
        public function count_num()
        {
            $sql="select distinct(t1.user_id) from entering_classroom  as t1 where t1.user_id>0 AND t1.user_type=0 ";
            return $this->db->query($sql)->num_rows();
        }
    }