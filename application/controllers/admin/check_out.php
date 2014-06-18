<?php
    class Check_out extends NH_Admin_Controller
    {
        /**
         * 课酬展示
         * @author shangshikai@tizi.com
         */
        public function index()
        {
            $this->load->library('pagination');
            $config = config_item('page_admin');
            $total=$this->check_out->check_out_total();
            $search_total=$total;
            $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
            $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
            $config['total_rows'] = $total;
            $config['per_page'] = PER_PAGE_NO;
            $this->pagination->initialize($config);
            $int_start=$this->uri->segment(3);
            $this->db->limit(PER_PAGE_NO,$int_start);
            $page = $this->pagination->create_links();
            $list=$this->check_out->for_class_list();
            $statistics=$this->check_out->statistics_amount();
            $teacher_balance=config_item('teacher_balance');
            //var_dump($list);die;
            $this->smarty->assign('list',$list);
            $this->smarty->assign('search_total',$search_total);
            $this->smarty->assign('page',$page);
            $this->smarty->assign('teacher_balance',$teacher_balance);
            $this->smarty->assign('statistics',$statistics);
            $this->smarty->assign('view','accounts');
            $this->smarty->display('admin/layout.html');
        }
        /**
         * 课酬搜索
         * @author shangshikai@tizi.com
         */
        public function search()
        {
            $get=$this->input->get(NULL,TRUE);
            $search_total=$this->check_out->search_accounts_count($get);
            $this->load->library('pagination');
            $config = config_item('page_admin');
            $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
            $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
            $config['total_rows'] = $search_total;
            $config['per_page'] = PER_PAGE_NO;
            $this->pagination->initialize($config);
            $int_start=$this->uri->segment(3);
            $this->db->limit(PER_PAGE_NO,$int_start);
            $page = $this->pagination->create_links();
            $search_list=$this->check_out->search_accounts_list($get);
            $statistics=$this->check_out->statistics_amount();
            $teacher_balance=config_item('teacher_balance');
            $this->smarty->assign('statistics',$statistics);
            $this->smarty->assign('teacher_balance',$teacher_balance);
            $this->smarty->assign('list',$search_list);
            $this->smarty->assign('search_total',$search_total);
            $this->smarty->assign('page',$page);
            $this->smarty->assign('view','accounts');
            $this->smarty->display('admin/layout.html');
        }
        /**
         * 课酬详情
         * @author shangshikai@tizi.com
         */
        public function details_for_class()
        {
            $checkout_id=$this->input->get('teacher_checkout_log_id',TRUE);
            $get=$this->check_out->details_count($checkout_id);
            //var_dump($get);die;
            $details=$this->check_out->details($get['class_ids']);
            $teacher_balance=config_item('teacher_balance');
            $this->smarty->assign('teacher_balance',$teacher_balance);
            $this->smarty->assign('checkout_id',$checkout_id);
            $this->smarty->assign('details',$details);
            $this->smarty->assign('get',$get);
            $this->smarty->assign('view','details_accounts');
            $this->smarty->display('admin/layout.html');
        }
        /**
         * 确认结算
         * @author shangshikai@tizi.com
         */
        public function ok_checkout()
        {
            $check_id=$this->input->post('check_id',TRUE);
            echo $this->check_out->checkout_ok($check_id);
        }
        /**
         * 确认付款
         * @author shangshikai@tizi.com
         */
        public function ok_pay()
        {
            $user_id=$this->input->post('user_id',TRUE);
            $check_id=$this->input->post('check_id',TRUE);
            $net_income=$this->input->post('net_income',TRUE);
            echo $this->check_out->pay_ok($user_id,$check_id,$net_income);
        }
    }