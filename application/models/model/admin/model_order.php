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
           return $this->db->select('student_order.status,student_order.id,student_order.student_id,student_order.create_time,student_order.confirm_time,student_order.pay_type,student_order.spend,user.phone_mask,user.email,user.nickname')->from('student_order')->join('user', 'user.id = student_order.student_id','left')->where("user.role",1)->order_by('student_order.id','desc')->get();
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
            if(trim($post['order_id'])!="")
            {
                $this->db->where("student_order.id = $post[order_id]");
            }
            if(trim($post['status'])!=0)
            {
                $this->db->where("student_order.status = $post[status]");
            }
            if(trim($post['name_phone_email'])!=0)
            {
                $str_cri=$str_criteria[$post['name_phone_email']];
                if(trim($post['name_phone_email'])!=2)
                {
                    $this->db->where("user.$str_cri = $post[phone_name_email]");
                }
                else
                {

                }
            }
            if(trim($post['pay_type'])!=0)
            {
                $this->db->where("student_order.pay_type = $post[pay_type]");
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
            $this->load->model("model/admin/model_admin");
            $this->model_admin->sea_order($post);
            return $this->db->get()->num_rows();
        }

        public function sea_order_list($post)
        {
            /**
             * 查询符合搜索条件的订单
             * @param $arr $post
             * @return boolean
             * @author shangshikai@nahao.com
            */
            $this->load->model("model/admin/model_admin");
            $this->model_admin->sea_order($post);
            return $this->db->get();
        }
    }