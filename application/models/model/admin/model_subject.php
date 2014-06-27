<?php
    class Model_subject extends NH_Model
    {
        /**
         * 获取全部学科
         * @author shangshikai@tizi.com
         */
        public function subject_all($status,$name)
        {
            self::search_subject($status,$name);
            return $this->db->get()->result_array();
        }
        /**
         * 学科数量
         * @author shangshikai@tizi.com
         */
        public function subject_total($status,$name)
        {
            self::search_subject($status,$name);
            return $this->db->get()->num_rows();
        }
        /**
         * 搜索条件
         * @author shangshikai@tizi.com
         */
        public function search_subject($status,$name)
        {
            self::sql($status,$name);
            if($status!=0)
            {
                $this->db->where(TABLE_SUBJECT.'.status',$status-1);
            }
            if($name!='')
            {
                $this->db->where(TABLE_SUBJECT.'.name',$name);
            }
        }
        /**
         * sql
         * @author shangshikai@tizi.com
         */
        public function sql($status,$name)
        {
            $this->db->select(TABLE_SUBJECT.'.id,name,status')->from(TABLE_SUBJECT);
        }
        /**
         * 禁用学科
         * @author shangshikai@tizi.com
         */
        public function disabled_subject($ids)
        {
            foreach($ids as $v)
            {
                $this->db->update(TABLE_SUBJECT,array(TABLE_SUBJECT.'.status'=>0),array(TABLE_SUBJECT.'.id'=>$v));
            }
            return TRUE;
        }
        /**
         * 启用学科
         * @author shangshikai@tizi.com
         */
        public function open_subject($ids)
        {
            foreach($ids as $v)
            {
                $this->db->update(TABLE_SUBJECT,array(TABLE_SUBJECT.'.status'=>1),array(TABLE_SUBJECT.'.id'=>$v));
            }
            return TRUE;
        }
        /**
         * 删除学科
         * @author shangshikai@tizi.com
         */
        public function delete_subject($ids)
        {
            foreach($ids as $v)
            {
                $this->db->delete(TABLE_SUBJECT,array(TABLE_SUBJECT.'.id'=>$v));
            }
            return TRUE;
        }
        /**
         * 学科是否存在
         * @author shangshikai@tizi.com
         */
        public function only_subject($name)
        {
            self::search_subject(0,$name);
            if($this->db->get()->num_rows()>0)
            {
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
        /**
         * 添加学科
         * @author shangshikai@tizi.com
         */
        public function add_subject($data)
        {
            return $this->db->insert(TABLE_SUBJECT,$data);
        }
        /**
         * 修改学科
         * @author shangshikai@tizi.com
         */
        public function edit_subject($data,$id)
        {
            return $this->db->update(TABLE_SUBJECT,$data,array(TABLE_SUBJECT.'.id'=>$id));
        }
    }