<?php
   class Model_Feedback extends NH_Model
   {
       /**
        * 评价展示
        * @author shangshikai@tizi.com
        */
       public function list_feedback($class_id,$content,$score_start,$score_end)
       {
           self::sql($class_id,$content,$score_start,$score_end);
           return $this->db->order_by(TABLE_CLASS_FEEDBACK.'.create_time','desc')->get()->result_array();
           //return $this->db->last_query();
       }

       /**
        * 评价数量
        * @author shangshikai@tizi.com
        */
       public function total_feedback($class_id,$content,$score_start,$score_end)
       {
           self::sql($class_id,$content,$score_start,$score_end);
           return $this->db->get()->num_rows();
       }
       /**
        * sql语句
        * @author shangshikai@tizi.com
        */
       public function sql($class_id,$content,$score_start,$score_end)
       {
           $this->db->select(TABLE_CLASS_FEEDBACK.'.id,course_id,round_id,class_id,student_id,nickname,content,create_time,score,is_show')->from(TABLE_CLASS_FEEDBACK);
           if($content!="")
           {
               $this->db->like(TABLE_CLASS_FEEDBACK.'.content',$content);
           }
           if($class_id!="")
           {
               $this->db->where(TABLE_CLASS_FEEDBACK.'.class_id',$class_id);
           }
           if($score_start!=0)
           {
               $this->db->where(TABLE_CLASS_FEEDBACK.'.score>=',$score_start);
           }
           if($score_end!=0)
           {
               $this->db->where(TABLE_CLASS_FEEDBACK.'.score<',$score_end);
           }
       }
       /**
        * 切换评价状态
        * @author sahngshikai@tizi.com
        */
       public function feedback_changing_over($id,$a)
       {
            if($a==0)
            {
                if($this->db->update(TABLE_CLASS_FEEDBACK,array(TABLE_CLASS_FEEDBACK.'.is_show'=>0),array(TABLE_CLASS_FEEDBACK.'.id'=>$id)))
                {
                    return TRUE;
                }
            }
            if($a==1)
            {
                if($this->db->update(TABLE_CLASS_FEEDBACK,array(TABLE_CLASS_FEEDBACK.'.is_show'=>1),array(TABLE_CLASS_FEEDBACK.'.id'=>$id)))
                {
                    return TRUE;
                }
            }
       }
   }