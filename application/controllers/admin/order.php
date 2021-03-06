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
            //$this->load->model("business/admin/order");
            $sea_total=$this->order->order_list();
            $sea_total2=$sea_total;
            $order_count=$this->order->order_message();
            $this->load->library('pagination');
            $config = config_item('page_admin');
            $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
            $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
            $config['total_rows'] = $sea_total;
            $config['per_page'] = PER_PAGE_NO;
            $this->pagination->initialize($config);
            $int_start=$this->uri->segment(3);
            $this->db->limit(PER_PAGE_NO,$int_start);
            $spendata=$this->order->order_data()->result_array();

            //var_dump($config);die;
            //var_dump($spendata);die;
            $page = $this->pagination->create_links();
            //  var_dump($this->data);die;
            $this->smarty->assign('config_pay',$config_pay);
            $this->smarty->assign('sea_total',$sea_total);
            $this->smarty->assign('sea_total2',$sea_total2);
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
            //$this->load->model("business/admin/order");
            $sea_total2=$this->order->search_order_count($post);
            $order_count=$this->order->order_message();
            $this->load->library('pagination');
            $config = config_item('page_admin');
            $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
            $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
            $config['total_rows'] = $sea_total2;
            $config['per_page'] = PER_PAGE_NO;
            $this->pagination->initialize($config);
            $int_start=$this->uri->segment(3);

            //var_dump($this->input->server('QUERY_STRING'));
            //var_dump($order_count);die;
            $this->db->limit(PER_PAGE_NO,$int_start);
            $spendata=$this->order->sea_order($post)->result_array();
            //var_dump($spendata);die;
            $page = $this->pagination->create_links();

            parse_str($this->input->server('QUERY_STRING'),$search_term);
            $this->smarty->assign('search_term',$search_term);

            $this->smarty->assign('config_pay',$config_pay);
            $this->smarty->assign('sea_total2',$sea_total2);
            $this->smarty->assign('config_status',$config_status);
            $this->smarty->assign('spendata',$spendata);
            $this->smarty->assign('page',$page);
            //var_dump($page);die;
            $this->smarty->assign('order_count',$order_count);
            $this->smarty->assign('sea_total',$order_count['count']);
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
            //$this->load->model("business/admin/order");
            $details = $this->order->order_detail($int_order_id);
            //var_dump($details);die;
            $note=$this->order->order_note($int_order_id)->result_array();
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
         * 管理员完成退款操作
         * @param
         * @return
         * @author shangshikai@nahao.com
        */

        public function ok_refund()
        {
            $student_id=$this->input->post('student_id',TRUE);
            $order_id=$this->input->post('order_id',TRUE);
            //$this->load->model("business/admin/order");
            echo $this->order->refund_ok($student_id,$order_id);
        }

        /**
         * 管理员同意退款操作
         * @param
         * @return
         * @author shangshikai@nahao.com
        */

        public function suc_refund()
        {
            $student_id=$this->input->post('student_id',TRUE);
            $order_id=$this->input->post('order_id',TRUE);
            //$this->load->model("business/admin/order");
            echo $this->order->agr_refund($student_id,$order_id);
        }

        /**
         * 管理员拒绝退款操作
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function refund()
        {
            $student_id=$this->input->post('student_id',TRUE);
            $order_id=$this->input->post('order_id',TRUE);
            //$this->load->model("business/admin/order");
            //echo $this->order->stu_refund($student_id,$order_id);
            echo $this->order->stu_refund($student_id,$order_id);
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
          //$this->load->model("business/admin/order");
          //echo $this->order->note_insert($note,$order_id);
          echo $this->order->note_insert($note,$order_id);
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
            echo $this->order->phone_show($int_uid);
        }

        /**
         * 修改订单价格
         * @author shangshikai@tizi.com
         */
        public function modify_price()
        {
            $modify_price=$this->input->post('modify_price',TRUE);
            $order_id=$this->input->post('order_id',TRUE);
            $spend=$this->input->post('spend',TRUE);
            echo $this->order->price_modify($modify_price,$order_id,$spend);
        }
        /**
         * 订单改为已支付(线下支付)
         * @author sahngshikai@tizi.com
         */
        public function pay_money()
        {
            $order_id=$this->input->post('order_id',TRUE);
            $user_id=$this->input->post('user_id',TRUE);
            $round_id=$this->input->post('round_id',TRUE);
            $admin_id=$this->userinfo['id'];
            $array_data = array(
                'user_id'=>$admin_id,
                'order_id'=>$order_id,
                'status'=>2,
                'user_type'=>NH_MEETING_TYPE_ADMIN,
                'pay_type'=>ORDER_TYPE_OFFLINE,          #支付方式
                'action'=>ORDER_STATUS_SUCC,             #日志动作
                'note'=>'线下支付成功'                     #日志记录
            );
            $this->load->model('business/student/student_order');
            $this->load->model('business/common/business_user');
            if($this->student_order->update_order_status($array_data) && $this->student_order->add_student_class_relation($order_id,$user_id) && $this->student_order->update_round_data($round_id))
            {
                echo $this->business_user->modify_user_info(array('has_bought'=>1),$user_id);
            }
        }
    }