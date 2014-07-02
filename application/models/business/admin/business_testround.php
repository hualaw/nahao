<?php
    class Business_testround extends NH_Model
    {
        public function modify($post)
        {
            $post['sell_begin_time']=strtotime($post['sell_begin_time']);
            $post['sell_end_time']=strtotime($post['sell_end_time']);
            $post['end_time']=strtotime($post['end_time']);
            $post['start_time']=strtotime($post['start_time']);
            $id=$post['round_id'];
            unset($post['round_id']);
            $this->load->model('model/admin/model_testround');
            return $this->model_testround->ok_modify($post,$id);
        }
    }