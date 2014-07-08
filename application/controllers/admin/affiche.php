<?php
    class Affiche extends NH_Admin_Controller
    {
        /**
         * 公告展示
         * @author shangshikai@tizi.com
         */
        public function index()
        {
            $round_id=$this->input->get('round_id',TRUE) ? $this->input->get('round_id',TRUE) : 0;
            $round_title=$this->input->get('round_title',TRUE) ? $this->input->get('round_title',TRUE) : '请输入公告内容';
            $admin_name=$this->input->get('admin_name',TRUE) ? $this->input->get('admin_name',TRUE) : "";
            $author_role=$this->input->get('author_role',TRUE) ? $this->input->get('author_role',TRUE) : 0;
            $content=$this->input->get('content',TRUE) ? $this->input->get('content',TRUE) : "";
            $start_time=$this->input->get('start_time',TRUE) ? $this->input->get('start_time',TRUE) : "";
            $end_time=$this->input->get('end_time',TRUE) ? $this->input->get('end_time',TRUE) : "";
            $status=$this->input->get('status',TRUE) ? $this->input->get('status',TRUE) : 0;
            $total=$this->affiche->total_affiche($admin_name,$author_role,$content,$start_time,$end_time,$status,$round_id);
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
            $list_affiche=$this->affiche->list_affiche($admin_name,$author_role,$content,$start_time,$end_time,$status,$round_id);

            $config_role=config_item('nh_meeting_type');
            $affiche_status=config_item('affiche_status');

            parse_str($this->input->server('QUERY_STRING'),$search_term);

            $this->smarty->assign('search_term',$search_term);
            $this->smarty->assign('affiche_status',$affiche_status);
            $this->smarty->assign('round_title',$round_title);
            $this->smarty->assign('round_id',$round_id);
            $this->smarty->assign('config_role',$config_role);
            $this->smarty->assign('page',$page);
            $this->smarty->assign('list_affiche',$list_affiche);
            $this->smarty->assign('search_total',$search_total);
            $this->smarty->assign('view','affiche');
            $this->smarty->display('admin/layout.html');
        }
        /**
         * 编辑公告内容
         * @author shangshikai@tizi.com
         */
        public function edit_content()
        {
            $post=$this->input->post(NULL,TRUE);
            echo $this->affiche->content_edit($post);
        }
        /**
         * 公告通过审核
         * @author shangshikai@tizi.com
         */
        public function pass()
        {
            $id=$this->input->post('id',TRUE);
            echo $this->affiche->affiche_pass($id);
        }
        /**
         * 公告不通过审核
         * @author shangshikai@tizi.com
         */
        public function nopass()
        {
            $id=$this->input->post('id',TRUE);
            echo $this->affiche->affiche_nopass($id);
        }
        /**
         * 公告置顶
         * @author shangshikai@tizi.com
         */
        public function top()
        {
            $id=$this->input->post('id',TRUE);
            echo $this->affiche->affiche_top($id);
        }
        /**
         * 公告取消置顶
         * @author shangshikai@tizi.com
         */
        public function notop()
        {
            $id=$this->input->post('id',TRUE);
            echo $this->affiche->affiche_notop($id);
        }
        /**
         * 添加公告
         * @author shangshikai@tizi.com
         */
        public function insert_affiche()
        {
            $post=$this->input->post(NULL,TRUE);
            echo $this->affiche->affiche_insert($post);
        }
    }