<?php
    class Affiche extends NH_Admin_Controller
    {
        /**
         * 公告展示
         * @author shangshikai@tizi.com
         */
        public function index()
        {
            $admin_name=$this->input->get('admin_name',TRUE) ? $this->input->get('admin_name',TRUE) : "";
            $author_role=$this->input->get('author_role',TRUE) ? $this->input->get('author_role',TRUE) : 0;
            $content=$this->input->get('content',TRUE) ? $this->input->get('content',TRUE) : "";
            $start_time=$this->input->get('start_time',TRUE) ? $this->input->get('start_time',TRUE) : "";
            $end_time=$this->input->get('end_time',TRUE) ? $this->input->get('end_time',TRUE) : "";
            $status=$this->input->get('status',TRUE) ? $this->input->get('status',TRUE) : 0;
            $total=$this->affiche->total_affiche($admin_name,$author_role,$content,$start_time,$end_time,$status);
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
            $list_affiche=$this->affiche->list_affiche($admin_name,$author_role,$content,$start_time,$end_time,$status);

            $config_role=config_item('author_role');
            $affiche_status=config_item('affiche_status');
            $this->smarty->assign('affiche_status',$affiche_status);
            $this->smarty->assign('config_role',$config_role);
            $this->smarty->assign('page',$page);
            $this->smarty->assign('list_affiche',$list_affiche);
            $this->smarty->assign('search_total',$search_total);
            $this->smarty->assign('view','affiche');
            $this->smarty->display('admin/layout.html');
        }
    }