<?php
    class Model_testround extends NH_Model
    {
        public function ok_modify($post,$id)
        {
            return $this->db->update(TABLE_ROUND,$post,array(TABLE_ROUND.'.id'=>$id));
        }
    }