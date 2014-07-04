<?php
    class model_check_out extends NH_Model
    {
        /**
         * 课酬总数
         * @author shangshikai@tizi.com
         */
        public function total_check_out()
        {
            return $this->db->select(TABLE_TEACHER_CHECKOUT_LOG.'.id')->from(TABLE_TEACHER_CHECKOUT_LOG)->get()->num_rows();
        }

        /**
         * 课酬统计
         * @author shangshikai@tizi.com
         */
        public function amount_statistics()
        {
            $check_out_count=array();
            $check_out_count['balance_total']=$this->db->select(TABLE_TEACHER_CHECKOUT_LOG.'.id')->from(TABLE_TEACHER_CHECKOUT_LOG)->where(TABLE_TEACHER_CHECKOUT_LOG.'.status',2)->get()->num_rows();
            $check_out_count['unsettled_total']=$this->db->select(TABLE_TEACHER_CHECKOUT_LOG.'.id')->from(TABLE_TEACHER_CHECKOUT_LOG)->where(TABLE_TEACHER_CHECKOUT_LOG.'.status',1)->get()->num_rows();
            $check_out_count['pay_total']=$this->db->select(TABLE_TEACHER_CHECKOUT_LOG.'.id')->from(TABLE_TEACHER_CHECKOUT_LOG)->where(TABLE_TEACHER_CHECKOUT_LOG.'.status',3)->get()->num_rows();
            $check_out_count['balance_net_income']=$this->db->select(TABLE_TEACHER_CHECKOUT_LOG.'.net_income')->from(TABLE_TEACHER_CHECKOUT_LOG)->where(TABLE_TEACHER_CHECKOUT_LOG.'.status',2)->get()->result_array();
            $check_out_count['unsettled_net_income']=$this->db->select(TABLE_TEACHER_CHECKOUT_LOG.'.net_income')->from(TABLE_TEACHER_CHECKOUT_LOG)->where(TABLE_TEACHER_CHECKOUT_LOG.'.status',1)->get()->result_array();
            $check_out_count['pay_net_income']=$this->db->select(TABLE_TEACHER_CHECKOUT_LOG.'.net_income')->from(TABLE_TEACHER_CHECKOUT_LOG)->where(TABLE_TEACHER_CHECKOUT_LOG.'.status',3)->get()->result_array();

            return $check_out_count;
        }
        /**
         * 课酬展示
         * @author shangshikai@tizi.com
         */
        public function list_class()
        {
            $this->load->model('model/admin/model_check_out');
            $this->model_check_out->sql();
            return $this->db->get()->result_array();
        }

        /**
         * 课酬搜索条件拼装
         * @shangsihkai@tizi.com
         */
        public function accounts_search($get,$int_uid)
        {
            $this->load->model('model/admin/model_check_out');
            $this->model_check_out->sql();
            if($get['term']!=0 && $get['term_val']!='')
            {
                if($get['term']==1)
                {
                    $this->db->where('user_info.realname',$get['term_val']);
                }
                if($get['term']==2)
                {
                    $this->db->where('user.nickname',$get['term_val']);
                }
                if($get['term']==3)
                {
                    $this->db->where('user.id',$int_uid);
                }
            }
            if($get['status']!=0)
            {
                $this->db->where('teacher_checkout_log.status',$get['status']);
            }
            if($get['settle_time']!='')
            {
                $this->db->where('teacher_checkout_log.checkout_time>',$get['settle_time']);
                $this->db->where('teacher_checkout_log.checkout_time<',$get['next_mon']);
            }
        }
        /**
         * 展示搜索列表
         * @author shangshikai@tizi.com
         */
        public function accounts_search_list($get)
        {
            $int_uid='';
            if($get['term']==3 && $get['term_val']!='')
            {
                $int_uid=get_uid_phone_server($get['term_val']);
            }
            $this->load->model('model/admin/model_check_out');
            $this->model_check_out->accounts_search($get,$int_uid);
            return $this->db->get()->result_array();
        }
        /**
         * 搜索总数
         * @author shangshikai@tizi.com
         */
        public function accounts_search_count($get)
        {
            $int_uid='';
            if($get['term']==3 && $get['term_val']!='')
            {
                $int_uid=get_uid_phone_server($get['term_val']);
            }
            $this->load->model('model/admin/model_check_out');
            $this->model_check_out->accounts_search($get,$int_uid);
            return $this->db->get()->num_rows();
        }
        /**
         * sql拼装
         */
        public function sql()
        {
            $this->db->select('teacher_checkout_log.id,teacher_checkout_log.gross_income,teacher_checkout_log.net_income,teacher_checkout_log.teach_times,teacher_checkout_log.status,teacher_checkout_log.class_times,teacher_checkout_log.checkout_time,user.nickname,user.phone_mask,user_info.realname')->from(TABLE_TEACHER_CHECKOUT_LOG)->join(TABLE_USER,"user.id=teacher_checkout_log.teacher_id",'left')->join(TABLE_USER_INFO,'user_info.user_id=teacher_checkout_log.teacher_id','left');
        }
        /**
         * 课酬详情
         * @author shangshikai@tizi.com
         */
        public function detail($id)
        {
            return $this->db->select('class.title as class_title,class.begin_time,class.end_time,round.title as round_title,round.reward')->from(TABLE_CLASS)->join(TABLE_ROUND,'round.id=class.round_id','left')->where(TABLE_CLASS.'.id',$id)->get()->row_array();
           // return $this->db->last_query();
        }
        /**
         * 课酬详情统计
         * @author shangshikai@tizi.com
         */
        public function count_details($checkout_id)
        {
            return $this->db->select('teacher_checkout_log.class_ids,teacher_checkout_log.checkout_time,teacher_checkout_log.pay_time,teacher_checkout_log.net_income,teacher_checkout_log.teach_times,teacher_checkout_log.class_times,teacher_checkout_log.status,user.nickname,user.phone_mask,user_info.realname,user_info.user_id')->from(TABLE_TEACHER_CHECKOUT_LOG)->join(TABLE_USER,"user.id=teacher_checkout_log.teacher_id",'left')->join(TABLE_USER_INFO,'user_info.user_id=teacher_checkout_log.teacher_id','left')->where('teacher_checkout_log.id',$checkout_id)->get()->row_array();
            //return $this->db->last_query();
        }
        /**
         * 确认结算
         * @author shangshikai@tizi.com
         */
        public function check_out_ok($check_id)
        {
            if($this->db->update(TABLE_TEACHER_CHECKOUT_LOG,array(TABLE_TEACHER_CHECKOUT_LOG.'.status'=>2,TABLE_TEACHER_CHECKOUT_LOG.'.checkout_time'=>time()),array(TABLE_TEACHER_CHECKOUT_LOG.'.id'=>$check_id)))
            {
                return TRUE;
            }
        }
        /**
         * 确认付款
         * @author shangshikai@tizi.com
         */
        public function pay_success($user_id,$check_id,$net_income)
        {
            if($this->db->update(TABLE_TEACHER_CHECKOUT_LOG,array(TABLE_TEACHER_CHECKOUT_LOG.'.status'=>3,TABLE_TEACHER_CHECKOUT_LOG.'.pay_time'=>time()),array(TABLE_TEACHER_CHECKOUT_LOG.'.id'=>$check_id)) && $this->db->update(TABLE_USER_INFO,array(TABLE_USER_INFO.'.remuneration'=>$net_income,TABLE_USER_INFO.'.update_time'=>time()),array(TABLE_USER_INFO.'.user_id'=>$user_id)))
            {
                return TRUE;
            }
        }
    }