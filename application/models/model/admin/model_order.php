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
         *
         * @param 查询的公共sql部分
         * @return
         * @author shangshikai@nahao.com
         */
        public function sql()
        {
            $this->db->select('student_order.status,student_order.id,student_order.student_id,student_order.create_time,student_order.confirm_time,student_order.pay_type,student_order.price,student_order.spend,user.phone_mask,user.nickname,user.email')->from('student_order')->join('user', 'user.id = student_order.student_id','left')->order_by('student_order.id','desc');
        }
        /**
         * 查询订单
         * @param
         * @return boolean
         * @author shangshikai@nahao.com
         */
        public function admin_order_data()
        {
           $this->load->model('model/admin/model_order');
           $this->model_order->sql();
           return $this->db->get();
           // var_dump($c->result_array());die;
        }

        /**
         * 搜索订单条件拼装
         * @param
         * @return boolean
         * @author shangshikai@nahao.com
         */
        public function sea_order($post,$int_uid)
        {
            $str_criteria=config_item('criteria');
            $this->load->model('model/admin/model_order');
            $this->model_order->sql();
            if(!empty($post['order_id']))
            {
                $this->db->where("student_order.id = $post[order_id]");
            }
            if($post['status']!=10)
            {
                $this->db->where("student_order.status = $post[status]");
            }
            if($post['name_phone_email']!=0)
            {
                $str_cri=$str_criteria[$post['name_phone_email']];
                if($post['name_phone_email']!=2)
                {
                    $this->db->where("user.$str_cri = '$post[phone_name_email]'");
                }
                else
                {
                    $this->db->where("user.id=$int_uid");
                }
            }
            if($post['pay_type']!=5)
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
            if($post['spend1']!="" && $post['spend2']!="")
            {
                $this->db->where("student_order.spend >=",$post['spend1']);
                $this->db->where("student_order.spend <=",$post['spend2']);
            }
            if($post['spend1']!="" && $post['spend2']=="")
            {
                $this->db->where("student_order.spend",$post['spend1']);
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
            $int_uid=get_uid_phone_server($post['phone_name_email']);
            $this->load->model("model/admin/model_order");
            $this->model_order->sea_order($post,$int_uid);
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
            $int_uid=get_uid_phone_server($post['phone_name_email']);
            $this->load->model("model/admin/model_order");
            $this->model_order->sea_order($post,$int_uid);
            return $this->db->get();
           // echo $this->db->last_query();die;
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
            $arr_order['refund_ok_round'] = $this->db->get_where("student_order",array('status' => 9))->num_rows();
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
            return $this->db->select('student_order.status,student_order.id,student_order.student_id,student_order.create_time,student_order.pay_type,student_order.spend,user.phone_mask,user.email,user.nickname,student_order.round_id,round.title,round.start_time,round.end_time,student_order.price,student_refund.refund_price')->from('student_order')->join('user', 'user.id = student_order.student_id','left')->join('round','round.id=student_order.round_id','left')->join('student_class','student_class.student_id=user.id','left')->join('student_refund','student_refund.order_id=student_order.id','left')->where("student_order.id",$int_order_id)->get()->row_array();
        }
        /**
         * 订单备注
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function note_order($int_order_id)
        {
            return $this->db->select('order_note.note,order_note.create_time,admin.username')->from('order_note')->join('admin','admin.id=order_note.admin_id','left')->where("order_note.order_id=$int_order_id")->order_by('order_note.id','desc')->get();
        }
        /**
         * 管理员拒绝退款操作
         * @param
         * @return boolean TRUE or FALSE
         * @author shangshikai@nahao.com
         */
        public function refund_stu($student_id,$order_id)
        {
            $admin_id=$this->userinfo['id'];
            //echo $admin_id;die;
            $round_id=$this->db->select('student_order.round_id')->from('student_order')->where("student_order.id=$order_id")->get()->row_array();
            if($this->db->update('student_class',array("status"=>0), array('student_id' => $student_id,'round_id'=>$round_id['round_id'])) && $this->db->update('student_refund',array("status"=>1),array('student_id' => $student_id,'round_id'=>$round_id['round_id'],'order_id'=>$order_id)) && $this->db->update('student_order',array('status'=>7),array('student_id' => $student_id,'round_id'=>$round_id['round_id'],'id'=>$order_id)) && $this->db->insert("order_action_log",array('order_id'=>$order_id,'user_type'=>1,'user_id'=>$admin_id,'action'=>9,'create_time'=>time(),'note'=>'管理员拒绝退款')))
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }

        /**
         * 管理员同意退款操作
         * @param
         * @return boolean TRUE or FALSE
         * @author shangshikai@nahao.com
         */
        public function refund_agr($student_id,$order_id)
        {
            $admin_id=$this->userinfo['id'];
            $round_id=$this->db->select('student_order.round_id')->from('student_order')->where("student_order.id=$order_id")->get()->row_array();
            if($this->db->update('student_class',array("status"=>4), array('student_id' => $student_id,'round_id'=>$round_id['round_id'])) && $this->db->update('student_refund',array("status"=>2),array('student_id' => $student_id,'round_id'=>$round_id['round_id'],'order_id'=>$order_id)) && $this->db->update('student_order',array('status'=>8),array('student_id' => $student_id,'round_id'=>$round_id['round_id'],'id'=>$order_id)) && $this->db->insert("order_action_log",array('order_id'=>$order_id,'user_type'=>1,'user_id'=>$admin_id,'action'=>10,'create_time'=>time(),'note'=>'管理员同意退款')))
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }

        /**
         * 管理员完成退款操作
         * @param
         * @return boolean TRUE or FALSE
         * @author shangshikai@nahao.com
         */
        public function refund_last($student_id,$order_id)
        {
            $admin_id=$this->userinfo['id'];
            $round_id=$this->db->select('student_order.round_id')->from('student_order')->where("student_order.id=$order_id")->get()->row_array();
            if($this->db->update('student_class',array("status"=>5), array('student_id' => $student_id,'round_id'=>$round_id['round_id'])) && $this->db->update('student_refund',array("status"=>3),array('student_id' => $student_id,'round_id'=>$round_id['round_id'],'order_id'=>$order_id)) && $this->db->update('student_order',array('status'=>9),array('student_id' => $student_id,'round_id'=>$round_id['round_id'],'id'=>$order_id)) && $this->db->insert("order_action_log",array('order_id'=>$order_id,'user_type'=>1,'user_id'=>$admin_id,'action'=>11,'create_time'=>time(),'note'=>'管理员完成退款')))
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
            $admin_id=$this->userinfo['id'];
            $data=array("note"=>$note,
                "order_id"=>$order_id,
                "admin_id"=>$admin_id,
                "create_time"=>time()
            );
           return $this->db->insert('order_note',$data);
        }
        /**
         *展示手机号
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function show_tel($int_uid)
        {
            return get_pnum_phone_server($int_uid);
        }
        /**
         *修改订单价格
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
    public function price_order_modify($modify_price,$order_id,$spend)
        {
            $admin_id=$this->userinfo['id'];
            if($this->db->update(TABLE_STUDENT_ORDER,array(TABLE_STUDENT_ORDER.'.spend'=>$modify_price),array(TABLE_STUDENT_ORDER.'.id'=>$order_id)))
            {
                if($this->db->insert("order_action_log",array('order_id'=>$order_id,'user_type'=>1,'user_id'=>$admin_id,'action'=>12,'create_time'=>time(),'note'=>"修改订单价格,由$spend 元修改为 $modify_price 元")))
                {
                    $this->load->model('model/common/model_redis', 'redis');
                    $this->redis->connect('order');
                    $redis_data=$this->cache->redis->get($order_id);
                    if($redis_data!=false)
                    {
                        $redis_data=json_decode($redis_data,TRUE);
                        $redis_data['spend']=$modify_price;
                        $redis_data=json_encode($redis_data);
                        if($this->cache->redis->set($order_id,$redis_data))
                        {
                            return TRUE;
                        }
                    }
                    return TRUE;
                }
            }
        }
    }