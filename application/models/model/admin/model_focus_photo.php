<?php
    class Model_focus_photo extends NH_Model
    {
        /**
         * 展示焦点图
         * @author shangshikai@tizi.com
         */
        public function photo_list($is_show)
        {
            self::sql($is_show);
            $this->db->order_by(TABLE_FOCUS_PHOTO.'.id','desc');
            return $this->db->get()->result_array();
        }
        /**
         * 拼装sql语句
         * @author shangshikai@tizi.com
         */
        public function sql($is_show=0)
        {
            $this->db->select(TABLE_FOCUS_PHOTO.'.id,round_id,img_src,color,is_show')->from(TABLE_FOCUS_PHOTO);
            if($is_show==1)
            {
                $this->db->where(TABLE_FOCUS_PHOTO.'.is_show',1);
            }
        }
        /**
         * 总数量
         * @author shangshikai@tizi.com
         */
        public function total_photo()
        {
            self::sql();
            return $this->db->get()->num_rows();
        }
        /**
         * 修改焦点图片
         * @author shangshiki@tizi.com
         */
        public function modify_edit($id,$data)
        {
            $this->db->update(TABLE_FOCUS_PHOTO,$data,array(TABLE_FOCUS_PHOTO.'.id'=>$id));
            return $this->db->affected_rows();
        }
        /**
         * 验证轮ID是否存在
         * @author shangshikai@tizi.com
         */
        public function round_check($round_id)
        {
            if($this->db->select(TABLE_FOCUS_PHOTO.'.id')->from(TABLE_FOCUS_PHOTO)->where(TABLE_FOCUS_PHOTO.'.round_id',$round_id)->get()->row_array())
            {
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
        /**
         * 添加轮播图
         * @author shangshikai@tizi.com
         */
        public function success_add($arr_data)
        {
            $this->db->insert(TABLE_FOCUS_PHOTO,$arr_data);
            return $this->db->affected_rows();
        }
    }