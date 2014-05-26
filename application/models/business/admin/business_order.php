<?php
    class business_order extends NH_Model
    {
        public function order($post)
        {
            //echo "this is business";die;
            $this->load->model("model/admin/model_admin");
            return $this->admin_order->order($post);
        }
        /**
         * 查询总记录数
         * @param
         * @return boolean
         * @author shangshikai@nahao.com
         */
        public function order_list()
        {

            $this->load->model("model/admin/model_order");
            return $this->model_order->admin_order_list();
        }
        /**
         * 查询订单
         * @param
         * @return boolean
         * @author shangshikai@nahao.com
         */
        public function order_data()
        {
            $this->load->model("model/admin/model_order");
            return $this->model_order->admin_order_data();
        }
        /**
         * 搜索订单
         * @param
         * @return boolean
         * @author shangshikai@nahao.com
         */
        public function sea_order($post)
        {
            if(trim($post['create_time1'])!="" && trim($post['create_time2'])!="")
            {
                $post["create_time1"] = strtotime($post["create_time1"]);
                $post["create_time2"] = strtotime($post["create_time2"]);
            }
            if(trim($post['confirm_time1'])!="" && trim($post['confirm_time2'])!="")
            {
                $post["confirm_time1"] = strtotime($post["confirm_time1"]);
                $post["confirm_time2"] = strtotime($post["confirm_time2"]);
            }
            $post['order_id']=trim($post['order_id']);
            $post['status']=trim($post['status']);
            $post['phone_name_email']=trim($post['phone_name_email']);
            //var_dump($post);die;
            $this->load->model("model/admin/model_order");
            return $this->model_order->sea_order_list($post);
        }
        /**
         * 复合搜索条件订单的总数
         * @param
         * @return boolean
         * @author shangshikai@nahao.com
         */
        public function search_order_count($post)
        {

            $this->load->model("model/admin/model_order");
            return $this->model_order->sea_order_count($post);
        }
        /**
         * 订单总信息
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function order_message()
        {
            $this->load->model("model/admin/model_order");
            return $this->model_order->order_information();
        }
        /**
         * 订单详情
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function order_detail($int_order_id)
        {
            $this->load->model("model/admin/model_order");
            return $this->model_order->details_order($int_order_id);
        }
        /**
         * 管理员退款操作
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function stu_refund($student_id,$order_id)
        {
            $this->load->model("model/admin/model_order");
            return $this->model_order->refund_stu($student_id,$order_id);
        }
        /**
         * 订单备注
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function order_note($int_order_id)
        {
            $this->load->model("model/admin/model_order");
            return $this->model_order->note_order($int_order_id);
        }
        /**
         *添加订单备注
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function note_insert($note,$order_id)
        {
            $note=htmlspecialchars($note);
            if(trim($note)=="")
            {
                return FALSE;
            }
            $this->load->model("model/admin/model_order");
            return $this->model_order->insert_order_note($note,$order_id);
        }

        public function phone_show($int_uid)
        {
            $this->load->model("model/admin/model_order");
            return $this->model_order->show_tel($int_uid);
        }
    }
