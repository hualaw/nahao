<?php
    class Employment extends NH_Admin_Controller
    {
        /**
         *@permission 招聘信息展示
         *@author shangshikai@tizi.com
         */
        public function index()
        {
            $title=$this->input->get('title',TRUE) ? $this->input->get('title',TRUE) : '';
            $total=$this->employment->num_employment(0,$title);
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
            $list_employment=$this->employment->get_employment(0,$title);
            parse_str($this->input->server('QUERY_STRING'),$search_term);
            $this->smarty->assign('search_term',$search_term);
            $this->smarty->assign('search_total',$search_total);
            $this->smarty->assign('page',$page);
            $this->smarty->assign('list_employment',$list_employment);
            $this->smarty->assign('view','employment');
            $this->smarty->display('admin/layout.html');
        }

        /**
         *@permission 招聘信息启用/禁用
         *@author shangshikai@tizi.com
         */
        public function is_open()
        {
            $is_open=$this->input->post('is_open',TRUE);
            $id=$this->input->post('id',TRUE);
            $arr_return=$this->employment->open_close($is_open,$id);
            self::json_output($arr_return);
        }

        /**
         *@permission 招聘信息添加_展示页面
         *@author shangshikai@tizi.com
         */
        public function create_employment()
        {
            $this->smarty->assign('view','employment_insert');
            $this->smarty->display('admin/layout.html');
        }

        /**
         * @permission 招聘信息添加_验证
         * @author shangshikai@tizi.com
         */
        public function check_employment()
        {
            $arr=$this->input->post(NULL,TRUE);
            $arr_return=$this->employment->add_employment($arr);
            self::json_output($arr_return);
        }

        /**
         *@permission 招聘信息修改_展示页面
         *@author shangshikai@tizi.com
         */
        public function edit_employment()
        {
            $id=$this->input->get('id',TRUE);
            $seq=$this->input->get('seq',TRUE);
            $detail=$this->employment->get_employment(trim($id),$title='');
            $this->smarty->assign('id',trim($id));
            $this->smarty->assign('seq',trim($seq));
            $this->smarty->assign('detail',$detail);
            $this->smarty->assign('view','employment_edit');
            $this->smarty->display('admin/layout.html');
        }

        /**
         * @permission 招聘信息修改_验证
         * @author shangshikai@tizi.com
         */
        public function check_edit_employment()
        {
            $arr=$this->input->post(NULL,TRUE);
            $arr_return=$this->employment->modify_employment($arr);
            self::json_output($arr_return);
        }
    }