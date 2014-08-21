<?php
    class Employment extends NH_Admin_Controller
    {
        /**
         *@permission 招聘信息展示
         *@author shangshikai@tizi.com
         */
        public function index()
        {
            $total=$this->employment->num_employment();
            $search_total=$total;
            $this->load->library('pagination');
            $config = config_item('page_admin');
            $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
            $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
            $config['total_rows'] = $total;
            $config['per_page'] = PER_PAGE_NO;
            $this->pagination->initialize($config);
            $int_start=$this->uri->segment(3);
            $this->db->limit(PER_PAGE_NO,$int_start);
            $page = $this->pagination->create_links();
            $list_employment=$this->employment->get_employment();
            $this->smarty->assign('search_total',$search_total);
            $this->smarty->assign('page',$page);
            $this->smarty->assign('list_employment',$list_employment);
            $this->smarty->assign('view','employment');
            $this->smarty->display('admin/layout.html');
        }

        public function is_open()
        {
            $is_open=$this->input->post('is_open',TRUE);
            $id=$this->input->post('id',TRUE);
            $this->employment->open_close($is_open,$id);
        }
    }