<?php
    class Model_affiche extends NH_Model
    {
        /**
         * 展示通告
         * @author shangshikai@tizi.com
         */
        public function affiche_list($admin_name,$author_role,$content,$start_time,$end_time,$status)
        {
            self::search_affiche($admin_name,$author_role,$content,$start_time,$end_time,$status);
            return$this->db->order_by('create_time','desc')->get()->result_array();
        }
        /**
         * sql拼装
         * @author shangshikai@tizi.com
         */
        public function sql($admin_name,$author_role,$content,$start_time,$end_time,$status)
        {
            $this->db->select(TABLE_ROUND_NOTE.'.top_time,id,round_id,author,author_role,content,create_time,status')->from(TABLE_ROUND_NOTE);
            if($admin_name!="")
            {
                $this->db->where(TABLE_ROUND_NOTE.'.author',$admin_name);
            }
            if($author_role!=0)
            {
                $this->db->where(TABLE_ROUND_NOTE.'.author_role',$author_role);
            }
            if($status!=0)
            {
                $this->db->where(TABLE_ROUND_NOTE.'.status',$status);
            }
            if($content!="")
            {
                $this->db->like(TABLE_ROUND_NOTE.'.content',$content);
            }
            if($start_time!="")
            {
                $this->db->where(TABLE_ROUND_NOTE.'.create_time>=',$start_time);
            }
            if($end_time!="")
            {
                $this->db->where(TABLE_ROUND_NOTE.'.create_time<',$end_time);
            }
        }
        /**
         * 搜索通告条件拼装
         * @author shangshikai@tizi.com
         */
        public function search_affiche($admin_name,$author_role,$content,$start_time,$end_time,$status)
        {
            self::sql($admin_name,$author_role,$content,$start_time,$end_time,$status);
        }
        /**
         * 公告数量
         * @author shangshikai@tizi.com
         */
        public function affiche_total($admin_name,$author_role,$content,$start_time,$end_time,$status)
        {
            self::search_affiche($admin_name,$author_role,$content,$start_time,$end_time,$status);
            return $this->db->get()->num_rows();
        }
    }