<?php
    class Tickling extends NH_Admin_Controller
    {
        /**
         * 展示反馈
         * @author shangshikai@tizi.com
         */
        public function index()
        {
            $nickname=$this->input->get('nickname',TRUE) ? $this->input->get('nickname',TRUE) : '';
            $content=$this->input->get('content',TRUE) ? $this->input->get('content',TRUE) : '';
            $create_time=$this->input->get('create_time',TRUE) ? $this->input->get('create_time',TRUE) : '';
            $email=$this->input->get('email',TRUE) ? $this->input->get('email',TRUE) : '';
            $total_tickling=$this->tickling->tickling_total($nickname,$content,$create_time,$email);
            $search_total=$total_tickling;
            $this->load->library('pagination');
            $config = config_item('page_admin');
            $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
            $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
            $config['total_rows'] = $total_tickling;
            $config['per_page'] = PER_PAGE_NO;
            $this->pagination->initialize($config);
            $int_start=$this->uri->segment(3);
            $this->db->limit(PER_PAGE_NO,$int_start);
            $page = $this->pagination->create_links();
//            var_dump($page);die;
            $tick=$this->tickling->tickling_list($nickname,$content,$create_time,$email);
            parse_str($this->input->server('QUERY_STRING'),$search_term);
            $this->smarty->assign('search_term',$search_term);
            $this->smarty->assign('search_total',$search_total);
            $this->smarty->assign('page',$page);
            $this->smarty->assign('tick',$tick);
            $this->smarty->assign('view','tickling');
            $this->smarty->display('admin/layout.html');
        }
    }