<?php
    class Subject extends NH_Admin_Controller
    {
        /**
         * 获取全部学科
         * @author shangshikai@tizi.com
         */
        public function index()
        {
            $status=$this->input->get('status',TRUE) ? $this->input->get('status',TRUE) : 0;
            $name=$this->input->get('name',TRUE) ? $this->input->get('name',TRUE) : '';
            $total=$this->subject->total_subject($status,$name);
            $search_total=$total;
            $this->load->library('pagination');
            $config = config_item('page_admin');
            $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
            $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
            $config['total_rows'] = $total;
            $config['per_page'] = 1;
            $this->pagination->initialize($config);
            $int_start=$this->uri->segment(3);
            $this->db->limit(1,$int_start);
            $page = $this->pagination->create_links();
            $list=$this->subject->all_subject($status,$name);
        }

        /**
         * 禁用学科
         * @author shangshikai@tizi.com
         */
        public function close_subject()
        {
            $ids=$this->input->post('id',TRUE);
            $this->subject->subject_close($ids);
        }
        /**
         * 启用学科
         * @author shangshikai@tizi.com
         */
        public function start_subject()
        {
            $ids=$this->input->post('id',TRUE);
            $this->subject->subject_open($ids);
        }
    }