<?php
    class Focus_photo extends NH_Admin_Controller
    {
        /**
         * 展示焦点图
         * @author shangshikai@tizi.com
         */
        public function index()
        {
            $total=$this->focus_photo->row_photo();
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
            $photo=$this->focus_photo->list_photo(0);
            $this->smarty->assign('page',$page);
            $this->smarty->assign('search_total',$search_total);
            $this->smarty->assign('photo',$photo);
            $this->smarty->assign('view','focus_photo');
            $this->smarty->display('admin/layout.html');
        }
        /**
         * 修改焦点图片
         * @author shangshiki@tizi.com
         */
        public function modify()
        {
            $data=$this->input->post(NULL,TRUE);
            $is_round=0;
            $int_rows=$this->focus_photo->edit($data);
            echo $int_rows;
        }
        /**
         * 启用/屏蔽
         * @author shangshiki@tizi.com
         */
        public function is_show()
        {
            $id=$this->input->post('id',TRUE);
            $is_show=$this->input->post('is_show',TRUE);
            $int_rows=$this->focus_photo->show_is($id,$is_show);
            echo $int_rows;
        }
        /**
         * 添加轮播图
         * @author shangshiki@tizi.com
         */
        public function add()
        {
            require_once APPPATH . 'libraries/qiniu/rs.php';
            require_once APPPATH . 'libraries/qiniu/io.php';
            Qiniu_SetKeys ( NH_QINIU_ACCESS_KEY, NH_QINIU_SECRET_KEY );
            $obj_putPolicy = new Qiniu_RS_PutPolicy ( NH_QINIU_BUCKET );
            $str_upToken = $obj_putPolicy->Token (null);
            $str_salt = random_string('alnum', 6);
            $filename = 'focus_'.time().'focus_photo'.$str_salt.'.jpg';
            $this->smarty->assign('key',$filename);
            $this->smarty->assign('token',$str_upToken);
            $this->smarty->assign('view','add_photo');
            $this->smarty->display('admin/layout.html');
        }

        /**
         * 验证添加表单
         * @author shangshikai@tizi.com
         */
        public function check_add()
        {
            $arr_data=$this->input->post(NULL,TRUE);
            $is_round=0;
            if(!$this->focus_photo->check_round($arr_data['round_id'],$is_round))
            {
                redirect('/focus_photo/add');
            }
            if($this->focus_photo->create_photo($arr_data))
            {
                redirect('/focus_photo');
            }
            else
            {
                redirect('/focus_photo/add');
            }
        }
        /**
         * 验证轮ID是否存在
         * @author shangshikai@tizi.com
         */
        public function check_round_id()
        {
            $round_id=$this->input->post('round_id',TRUE);
            $is_round=$this->input->post('is_round',TRUE);
            echo $this->focus_photo->check_round($round_id,$is_round);
        }
    }