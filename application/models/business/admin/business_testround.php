<?php
    class Business_testround extends NH_Model
    {
        public function test()
        {
            $arr=$this->db->select('user_info.realname,user_info.user_id')->from('student_order')
            ->join('user_info','user_info.user_id=student_order.student_id','left')
            ->where(array('student_order.round_id'=>14,'student_order.spend'=>0))->get()->result_array();
            foreach($arr as $k=>$v)
            {
                $arr[$k]['tel']=get_pnum_phone_server($v['user_id']);
            }
           return $arr;
        }
    }