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
            $config_pay=config_item("order_type");
            $config_status=config_item("order_status");
            $this->load->model("business/admin/business_order");
            $sea_total=$this->business_order->order_list();
            $order_count=$this->business_order->order_message();
            $this->load->library('pagination');
            $config = config_item('page_admin');
            $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
            $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
            $config['total_rows'] = $sea_total;
            $config['per_page'] = 10;
            $this->pagination->initialize($config);
            $int_start=$this->uri->segment(3);
            $this->db->limit(10,$int_start);
            $spendata=$this->business_order->order_data()->result_array();

            //var_dump($config);die;
            //var_dump($spendata);die;
            $page = $this->pagination->create_links();
            //  var_dump($this->data);die;
            $this->smarty->assign('config_pay',$config_pay);
            $this->smarty->assign('sea_total',$sea_total);
            $this->smarty->assign('config_status',$config_status);
            $this->smarty->assign('spendata',$spendata);
            $this->smarty->assign('page',$page);
            //var_dump($page);die;
            $this->smarty->assign('order_count',$order_count);
            $this->smarty->assign('view',"order_list");
            $this->smarty->display('admin/layout.html');
        }
        /**
         * 展示搜索订单列表
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function search_order()
        {
            $post=$this->input->get();
            //var_dump(strtotime($post["create_time1"]));die;
            $config_pay=config_item("order_type");
            $config_status=config_item("order_status");
            $this->load->model("business/admin/business_order");
            $sea_total=$this->business_order->search_order_count($post);
            $order_count=$this->business_order->order_message();
            $this->load->library('pagination');
            $config = config_item('page_admin');
            $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
            $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
            $config['total_rows'] = $sea_total;
            $config['per_page'] = 10;
            $this->pagination->initialize($config);
            $int_start=$this->uri->segment(3);

            //var_dump($this->input->server('QUERY_STRING'));

            $this->db->limit(10,$int_start);
            $spendata=$this->business_order->sea_order($post)->result_array();
            $page = $this->pagination->create_links();

            $this->smarty->assign('config_pay',$config_pay);
            $this->smarty->assign('sea_total',$sea_total);
            $this->smarty->assign('config_status',$config_status);
            $this->smarty->assign('spendata',$spendata);
            $this->smarty->assign('page',$page);
            //var_dump($page);die;
            $this->smarty->assign('order_count',$order_count);
            $this->smarty->assign('view',"order_list");
            $this->smarty->display('admin/layout.html');
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
            $config_pay=config_item("order_type");
            $config_refund=config_item('refund');
            $config_status=config_item("order_status");
            $this->load->model("business/admin/business_order");
            $details = $this->business_order->order_detail($int_order_id)->row_array();
           // var_dump($this->data['details']);die;
            $note=$this->business_order->order_note($int_order_id)->result_array();
           // var_dump($this->data['note']);die;
            $this->smarty->assign('config_pay',$config_pay);
            $this->smarty->assign('config_refund',$config_refund);
            $this->smarty->assign('config_status',$config_status);
            $this->smarty->assign('details',$details);
            $this->smarty->assign('note',$note);
            $this->smarty->assign('view',"order_details_list");
            $this->smarty->display('admin/layout.html');
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
            //echo $this->business_order->stu_refund($student_id,$order_id);
            self::json_output($this->business_order->stu_refund($student_id,$order_id));
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
          //echo $this->business_order->note_insert($note,$order_id);
          self::json_output($this->business_order->note_insert($note,$order_id));
        }
        /**
         * 显示手机号
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function show_phone()
        {
            $int_uid=$this->input->post('uid',TRUE);
            $this->load->model("business/admin/business_order");
           // self::json_output($this->business_order->phone_show($int_uid));
            echo $this->business_order->phone_show($int_uid);
        }
    }