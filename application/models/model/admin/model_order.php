<?php
    class Model_Order extends NH_Model
    {
        /**
         * 查询总记录数
         * @param
         * @return boolean
         * @author shangshikai@nahao.com
         */
        public function admin_order_list()
        {
            return $this->db->get("student_order")->num_rows();
        }

        /**
         * 查询订单
         * @param
         * @return boolean
         * @author shangshikai@nahao.com
         */
        public function admin_order_data()
        {
           return $this->db->select('student_order.status,student_order.id,student_order.student_id,student_order.create_time,student_order.confirm_time,student_order.pay_type,student_order.spend,user.phone_mask,user.email,user.nickname,user.id as uid')->from('student_order')->join('user', 'user.id = student_order.student_id','left')->order_by('student_order.id','desc')->get();
           // var_dump($c->result_array());die;
        }

        /**
         * 搜索订单条件拼装
         * @param
         * @return boolean
         * @author shangshikai@nahao.com
         */
        public function sea_order($post)
        {
            $str_criteria=config_item('criteria');
            $this->db->select('student_order.status,student_order.id,student_order.student_id,student_order.create_time,student_order.confirm_time,student_order.pay_type,student_order.price,student_order.spend,user.phone_mask,user.nickname,user.email')->from('student_order')->join('user', 'user.id = student_order.student_id','left');
            if(!empty($post['order_id']))
            {
                $this->db->where("student_order.id = $post[order_id]");
            }
            if($post['status']!='')
            {
                $this->db->where("student_order.status = $post[status]");
            }
            if($post['name_phone_email']!=0)
            {
                $str_cri=$str_criteria[$post['name_phone_email']];
                if($post['name_phone_email']!=2)
                {
                    $this->db->where("user.$str_cri = $post[phone_name_email]");
                }
                else
                {
                    $int_uid=get_uid_phone_server($post['phone_name_email']);
                    $this->db->where("user.id=$int_uid");
                }
            }
            if($post['pay_type']!=0)
            {
                $this->db->where("student_order.pay_type = $post[pay_type]");
            }
            if($post['create_time1']!="" && $post['create_time2']!="")
            {
                $this->db->where("student_order.create_time >",$post['create_time1']);
                $this->db->where("student_order.create_time <",$post['create_time2']);
            }
            if(trim($post['confirm_time1'])!="" && trim($post['confirm_time2'])!="")
            {
                $this->db->where("student_order.confirm_time >",$post['confirm_time1']);
                $this->db->where("student_order.confirm_time <",$post['confirm_time2']);
            }
        }
        /**
         * 符合搜索条件的订单数量
         * @param $arr $post
         * @return boolean
         * @author shangshikai@nahao.com
         */
        public function sea_order_count($post)
        {
            $this->load->model("model/admin/model_order");
            $this->model_order->sea_order($post);
            return $this->db->get()->num_rows();
        }
        /**
         * 查询符合搜索条件的订单
         * @param $arr $post
         * @return boolean
         * @author shangshikai@nahao.com
         */
        public function sea_order_list($post)
        {
            $this->load->model("model/admin/model_order");
            $this->model_order->sea_order($post);
            return $this->db->order_by('student_order.id','desc')->get();
        }
        /**
         * 查询订单总信息
         * @param
         * @return $arr_order
         * @author shangshikai@nahao.com
         */
        public function order_information()
        {
            $arr_order=array();
            $arr_order['count'] = $this->db->get("student_order")->num_rows();
            $arr_order['non_pay'] = $this->db->get_where("student_order",array('status' => 0))->num_rows();
            $arr_order['pay_fail'] = $this->db->get_where("student_order",array('status' => 1))->num_rows();
            $arr_order['pay_payment'] = $this->db->get_where("student_order",array('status' => 2))->num_rows();
            $arr_order['pay_success'] = $this->db->get_where("student_order",array('status' => 3))->num_rows();
            $arr_order['order_cancel'] = $this->db->get_where("student_order",array('status' => 4))->num_rows();
            $arr_order['order_close'] = $this->db->get_where("student_order",array('status' => 5))->num_rows();
            $arr_order['apply_refund_round'] = $this->db->get_where("student_order",array('status' => 6))->num_rows();
            $arr_order['refund_fail_round'] = $this->db->get_where("student_order",array('status' => 7))->num_rows();
            $arr_order['refund_success_round'] = $this->db->get_where("student_order",array('status' => 8))->num_rows();

            return $arr_order;
        }
        /**
         * 订单详情
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function details_order($int_order_id)
        {
            return $this->db->select('student_order.status,student_order.id,student_order.student_id,student_order.create_time,student_order.pay_type,student_order.spend,user.phone_mask,user.email,user.nickname,order_round_relation.round_id,round.title,round.start_time,round.end_time,round.price,round.sale_price')->from('student_order')->join('user', 'user.id = student_order.student_id','left')->join('order_round_relation','order_round_relation.order_id=student_order.id','left')->join('round','round.id=order_round_relation.round_id','left')->join('student_class','student_class.student_id=user.id','left')->where("student_order.id",$int_order_id)->get();
        }
        /**
         * 订单备注
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function note_order($int_order_id)
        {
            return $this->db->select('order_note.note,order_note.create_time')->from('order_note')->where("order_note.order_id=$int_order_id")->get();
        }
        /**
         * 管理员退款操作
         * @param
         * @return boolean TRUE or FALSE
         * @author shangshikai@nahao.com
         */
        public function refund_stu($student_id,$order_id)
        {
            $round_id=$this->db->select('order_round_relation.round_id')->from('order_round_relation')->where("order_round_relation.order_id=$order_id")->get()->row_array();
            if($this->db->update('student_order',array("status"=>8), "student_order.id = $order_id") && $this->db->update('student_class',array("status"=>6), array('student_id' => $student_id,'round_id'=>$round_id['round_id'])) && $this->db->update('student_refund',array("status"=>2),array('student_id' => $student_id,'round_id'=>$round_id['round_id'])))
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }

        }
        /**
         *添加订单备注
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function insert_order_note($note,$order_id)
        {
            $admin_id=1;
            $data=array("note"=>$note,
                "order_id"=>$order_id,
                "admin_id"=>$admin_id,
                "create_time"=>time()
            );
           return $this->db->insert('order_note',$data);
        }

        public function show_tel($int_uid)
        {
            return get_pnum_phone_server($int_uid);
        }
    }