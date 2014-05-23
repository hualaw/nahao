<?php
    class Order extends NH_Admin_Controller
    {
        /**
         * 展示订单列表
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function index()
        {
            $this->data['config_pay']=config_item("order_type");
            $this->data['config_status']=config_item("order_status");
            $this->load->model("business/admin/business_order");
            $this->data['sea_total']=$this->business_order->order_list();
            $this->data['order_count']=$this->business_order->order_message();
            $this->load->library('pagination');
            $config = config_item('page_admin');
            $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
            $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
            $config['total_rows'] = $this->data['sea_total'];
            $config['per_page'] = 1;
            $this->pagination->initialize($config);
            $int_start=$this->uri->segment(3);
            $this->db->limit(1,$int_start);
            $this->data["spendata"]=$this->business_order->order_data()->result_array();
            $this->data['page'] = $this->pagination->create_links();
          //  var_dump($this->data);die;
            $this->layout->view('admin/order_list',$this->data);
        }
        /**
         * 展示搜索订单列表
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function search_order()
        {
            $post=$this->input->post();
            //var_dump(strtotime($post["create_time1"]));die;
            $this->data['config_pay']=config_item("order_type");
            $this->data['config_status']=config_item("order_status");
            $this->load->model("business/admin/business_order");
            $this->data['sea_total']=$this->business_order->search_order_count($post);
            $this->data['order_count']=$this->business_order->order_message();
            $this->load->library('pagination');
            $config = config_item('page_admin');
            $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
            $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
            $config['total_rows'] = $this->data['sea_total'];
            $config['per_page'] = 1;
            $this->pagination->initialize($config);
            $int_start=$this->uri->segment(3);
            $this->db->limit(1,$int_start);
            $this->data["spendata"]=$this->business_order->sea_order($post)->result_array();
            //var_dump($this->data["spendata"]);die;
            $this->data['page'] = $this->pagination->create_links();
            //var_dump($this->data);die;
            $this->layout->view('admin/order_list',$this->data);
        }
        /**
         * 展示订单详情列表
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function order_details()
        {
            $int_order_id=$this->input->get('id',TRUE);
            $this->data['config_pay']=config_item("order_type");
            $this->data['config_refund']=config_item('refund');
            $this->data['config_status']=config_item("order_status");
            $this->load->model("business/admin/business_order");
            $this->data['details'] = $this->business_order->order_detail($int_order_id)->row_array();
           // var_dump($this->data['details']);die;
            $this->data['note']=$this->business_order->order_note($int_order_id)->result_array();
           // var_dump($this->data['note']);die;
            $this->layout->view('admin/order_details_list',$this->data);
        }

        /**
         * 管理员退款操作
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function refund()
        {
            $student_id=$this->input->post('student_id',TRUE);
            $order_id=$this->input->post('order_id',TRUE);
            $this->load->model("business/admin/business_order");
            echo $this->business_order->stu_refund($student_id,$order_id);
        }
        /**
         * 添加订单备注
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function insert_note()
        {
          $note=$this->input->post('note',TRUE);
          $order_id=$this->input->post('order_id',TRUE);
          $this->load->model("business/admin/business_order");
         echo $this->business_order->note_insert($note,$order_id);
        }

    }