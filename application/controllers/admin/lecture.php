<?php
    class Lecture extends NH_Admin_Controller
    {
        /**
         * 展示试讲列表
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function index()
        {
            $this->load->model('business/common/business_subject','subject');
            $subject=$this->subject->get_subjects();
            //$this->load->model('business/admin/lecture');
            $province=$this->lecture->all_province();
            $this->load->library('pagination');
            $config = config_item('page_admin');
            $total=$this->lecture->lecture_total();
            $sea_total=$total;
            $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
            $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
            $config['total_rows'] = $total;
            $config['per_page'] = PER_PAGE_NO;
            $this->pagination->initialize($config);
            $int_start=$this->uri->segment(3);
            $this->db->limit(PER_PAGE_NO,$int_start);
            $page = $this->pagination->create_links();

            $config_status=config_item('lecture_status');
            $config_tea_type=config_item('teacher_type');
            $config_stage=config_item('stage');
            $config_title=config_item('teacher_title');
            $lecture=$this->lecture->lecture_list()->result_array();
            $time_day=$this->lecture->day_lecture();
            //var_dump($arr);die;
            $this->smarty->assign('config_status',$config_status);
            $this->smarty->assign('config_tea_type',$config_tea_type);
            $this->smarty->assign('config_stage',$config_stage);
            $this->smarty->assign('config_title',$config_title);
            $this->smarty->assign('subject',$subject);
            $this->smarty->assign('province',$province);
            $this->smarty->assign('lecture',$lecture);
            $this->smarty->assign('total',$total);
            $this->smarty->assign('sea_total',$sea_total);
            $this->smarty->assign('time_day',$time_day);
            $this->smarty->assign('page',$page);
            $this->smarty->assign('view','teacher_lecture');
            $this->smarty->display('admin/layout.html');
        }
        /**
         * 展示搜索试讲列表
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function sea_lecture()
        {
            $get=$this->input->get(NULL,TRUE);
            //var_dump($get);die;
            $this->load->model('business/common/business_subject','subject');
            $subject=$this->subject->get_subjects();
            //$this->load->model('business/admin/lecture');
            $province=$this->lecture->all_province();
            $this->load->library('pagination');
            $config = config_item('page_admin');
            $total=$this->lecture->lecture_total();
            $sea_total=$this->lecture->sea_lecture($get);
            //var_dump($sea_total);die;
            $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
            $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
            $config['total_rows'] = $sea_total;
            $config['per_page'] = PER_PAGE_NO;
            $this->pagination->initialize($config);
            $int_start=$this->uri->segment(3);
            $this->db->limit(PER_PAGE_NO,$int_start);
            $page = $this->pagination->create_links();

            $config_status=config_item('lecture_status');
            $config_tea_type=config_item('teacher_type');
            $config_stage=config_item('stage');
            $config_title=config_item('teacher_title');
            $lecture=$this->lecture->lecture_seach_list($get)->result_array();
            $time_day=$this->lecture->day_lecture();
            //var_dump($arr);die;
            $this->smarty->assign('config_status',$config_status);
            $this->smarty->assign('config_tea_type',$config_tea_type);
            $this->smarty->assign('config_stage',$config_stage);
            $this->smarty->assign('config_title',$config_title);
            $this->smarty->assign('subject',$subject);
            $this->smarty->assign('province',$province);
            $this->smarty->assign('lecture',$lecture);
            $this->smarty->assign('total',$total);
            $this->smarty->assign('sea_total',$sea_total);
            $this->smarty->assign('time_day',$time_day);
            $this->smarty->assign('page',$page);
            $this->smarty->assign('view','teacher_lecture');
            $this->smarty->display('admin/layout.html');
        }
        /**
         * 展示详情
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function lecture_details()
        {
            $con_gender=config_item('gender');
            $con_stage=config_item('stage');
            $con_title=config_item('teacher_title');
            $con_teach_type=config_item('teacher_type');
            $con_lecture_status=config_item('lecture_status');
            //var_dump($con_title);die;
            $lecture_id=$this->input->get('lecture_id',TRUE);
            $details=$this->lecture->details_lecture($lecture_id);
            //var_dump($details);die;
            $city_area=$this->lecture->city_area($lecture_id);
           // var_dump($city_area);die;
            $notes = $this->lecture->notes($lecture_id);
            //var_dump($notes);die;
            $this->smarty->assign('con_lecture_status',$con_lecture_status);
            $this->smarty->assign('city_area',$city_area);
            $this->smarty->assign('con_gender',$con_gender);
            $this->smarty->assign('con_stage',$con_stage);
            $this->smarty->assign('con_title',$con_title);
            $this->smarty->assign('con_teach_type',$con_teach_type);
            $this->smarty->assign('notes',$notes);
            $this->smarty->assign('details',$details);
            $this->smarty->assign('view','teacher_details_lecture');
            $this->smarty->display('admin/layout.html');
        }
        /**
         *添加试讲备注和修改审核状态
         * @param
         * @return
         * @author shangshikai@nahao.com
         */
        public function insert_note()
        {
            $post=$this->input->post(NULL,TRUE);
            echo $this->lecture->note_insert($post);
           // echo $post['lecture_status'];
        }
        /**
         *通过试讲审核
         * @param
         * @return
         * @author shangshikai@nahao.com
        */
        public function pass_lecture()
        {
              $post=$this->input->post(NULL,TRUE);
              echo $this->lecture->lecture_pass($post);
        }

        /**
         *待定试讲审核
         * @param
         * @return
         * @author shangshikai@nahao.com
        */
        public function indeterminate_lecture()
        {
            $lecture_id=$this->input->post('lecture_id',TRUE);
            echo $this->lecture->lecture_indeterminate($lecture_id);
        }

        /**
         *不通过试讲审核
         * @param
         * @return
         * @author shangshikai@nahao.com
        */
        public function nopass_lecture()
        {
            $lecture_id=$this->input->post('lecture_id',TRUE);
            echo $this->lecture->lecture_nopass($lecture_id);
        }
    }