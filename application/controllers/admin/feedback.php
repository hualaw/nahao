<?php
    class Feedback extends NH_Admin_Controller
    {
        /**
         * 评价展示
         * @author shangshikai@tizi.com
         */
        public function index()
        {
            $this->input->get('class_id',TRUE) ? $this->input->get('class_id',TRUE) : "";
            $this->input->get('content',TRUE) ? $this->input->get('content',TRUE) : "";
            $this->input->get('score_start',TRUE) ? $this->input->get('score_start',TRUE) : 0;
            $this->input->get('score_end',TRUE) ? $this->input->get('score_end',TRUE) : 0;

            $config_feedback=config_item('feedback');
            $total=$this->feedback->feedback_total($this->input->get('class_id',TRUE),$this->input->get('content',TRUE),$this->input->get('score_start',TRUE),$this->input->get('score_end',TRUE));
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
            $list=$this->feedback->feedback_list($this->input->get('class_id',TRUE),$this->input->get('content',TRUE),$this->input->get('score_start',TRUE),$this->input->get('score_end',TRUE));

            $this->smarty->assign('config_feedback',$config_feedback);
            $this->smarty->assign('page',$page);
            $this->smarty->assign('search_total',$search_total);
            $this->smarty->assign('list',$list);
            $this->smarty->assign('view','feedback');
            $this->smarty->display('admin/layout.html');
        }
        /**
         * 切换评价状态
         * @author sahngshikai@tizi.com
         */
        public function show_hide_feedback()
        {
            $id=$this->input->post('id',TRUE);
            $a=$this->input->post('a',TRUE);
            echo $this->feedback->changing_over_feedback($id,$a);
        }
    }