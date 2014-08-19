<?php
    class Model_affiche extends NH_Model
    {
        /**
         * 展示通告
         * @author shangshikai@tizi.com
         */
        public function affiche_list($admin_name,$author_role,$content,$start_time,$end_time,$status,$round_id)
        {
            self::search_affiche($admin_name,$author_role,$content,$start_time,$end_time,$status,$round_id);
            return$this->db->order_by('create_time','desc')->get()->result_array();
        }
        /**
         * sql拼装
         * @author shangshikai@tizi.com
         */
        public function sql($admin_name,$author_role,$content,$start_time,$end_time,$status,$round_id)
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
            if($round_id!=0)
            {
                $this->db->where(TABLE_ROUND_NOTE.'.round_id',$round_id);
            }
        }
        /**
         * 搜索通告条件拼装
         * @author shangshikai@tizi.com
         */
        public function search_affiche($admin_name,$author_role,$content,$start_time,$end_time,$status,$round_id)
        {
            self::sql($admin_name,$author_role,$content,$start_time,$end_time,$status,$round_id);
        }
        /**
         * 公告数量
         * @author shangshikai@tizi.com
         */
        public function affiche_total($admin_name,$author_role,$content,$start_time,$end_time,$status,$round_id)
        {
            self::search_affiche($admin_name,$author_role,$content,$start_time,$end_time,$status,$round_id);
            return $this->db->get()->num_rows();
        }
        /**
         * 公告编辑
         * @author shangshikai@tizi.com
         */
        public function modify_content($post)
        {
            return $this->db->update(TABLE_ROUND_NOTE,array(TABLE_ROUND_NOTE.'.content'=>$post['content']),array(TABLE_ROUND_NOTE.'.id'=>$post['id']));
        }
        /**
         * 公告通过审核
         * @author shangshikai@tizi.com
         */
        public function pass_affiche($id)
        {
            return $this->db->update(TABLE_ROUND_NOTE,array(TABLE_ROUND_NOTE.'.status'=>3),array(TABLE_ROUND_NOTE.'.id'=>$id));
        }
        /**
         * 公告不通过审核
         * @author shangshikai@tizi.com
         */
        public function nopass_affiche($id)
        {
            return $this->db->update(TABLE_ROUND_NOTE,array(TABLE_ROUND_NOTE.'.status'=>2),array(TABLE_ROUND_NOTE.'.id'=>$id));
        }
        /**
         * 公告置顶
         * @author shangshikai@tizi.com
         */
        public function top_affiche($id)
        {
            return $this->db->update(TABLE_ROUND_NOTE,array(TABLE_ROUND_NOTE.'.top_time'=>time()),array(TABLE_ROUND_NOTE.'.id'=>$id));
        }
        /**
         * 公告不置顶
         * @author shangshikai@tizi.com
         */
        public function notop_affiche($id)
        {
            return $this->db->update(TABLE_ROUND_NOTE,array(TABLE_ROUND_NOTE.'.top_time'=>0),array(TABLE_ROUND_NOTE.'.id'=>$id));
        }
        /**
         * 添加公告
         * @author shangshikai@tizi.com
         */
        public function add_affiche($data)
        {
            return $this->db->insert(TABLE_ROUND_NOTE,$data);
        }

        public function edit_affiche_content($id)
        {
            return $this->db->select(TABLE_ROUND_NOTE.'.content')->from(TABLE_ROUND_NOTE)->where(TABLE_ROUND_NOTE.'.id',$id)->get()->row_array();
        }
    }