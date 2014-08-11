<?php
    class Lecture extends NH_Admin_Controller
    {
        private $arr_response = array(
            'status' => 'error',
            'msg' => '操作失败',
            'redirect' => '/lecture'
        );
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
           // var_dump($lecture);die;
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
            parse_str($this->input->server('QUERY_STRING'),$search_term);
            $this->smarty->assign('search_term',$search_term);
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
            $token = get_meeting_token(0,NH_MEETING_TYPE_SUPER_ADMIN);
            $this->smarty->assign('token',$token);
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
               //echo $post['basic_reward'];
              echo $this->lecture->lecture_pass($post);
        }

        /**
         *已试讲
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
        /**
         * 不允许试讲
         * @author shangshikai@tizi.com
         */
        public function disagree_lecture()
        {
            $lecture_id=$this->input->post('lecture_id',TRUE);
            echo $this->lecture->lecture_disagree($lecture_id);
        }
        /**
         * 允许试讲
         * @author shangshikai@tizi.com
         */
        public function agree_lecture()
        {
            $lecture_id=$this->input->post('lecture_id',TRUE);
            $start_time=date('Y-m-d H:i:s',$this->input->post('start_time',TRUE));
            $end_time=date('Y-m-d H:i:s',$this->input->post('end_time',TRUE));
            $subject=$this->input->post('subject',TRUE);
            $course=$this->input->post('course',TRUE);
            $user_id=$this->input->post('user_id',TRUE);
            $arr_classroom_param=array(
                'name'=>$course,
                'start_at' => $start_time,
                'end_at' => $end_time
            );
            $int_classroom_id = general_classroom_id($arr_classroom_param);
            echo $this->lecture->lecture_agree($lecture_id,$this->input->post('start_time',TRUE),$this->input->post('end_time',TRUE),$subject,$course,$int_classroom_id,$user_id);
        }

        /**
         * 试讲课列表
         * @author shangshikai@tizi.com
         */
        public function lecture_class_list()
        {
            $title=trim($this->input->get('title',TRUE)) ? trim($this->input->get('title',TRUE)) : '';
            $total=$this->lecture->total_lecture_class($title);
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
            $list_lecture_class=$this->lecture->list_lecture_class($title);
            parse_str($this->input->server('QUERY_STRING'),$search_term);

            $this->smarty->assign('search_term',$search_term);
            $this->smarty->assign('search_total',$search_total);
            $this->smarty->assign('page',$page);
            $this->smarty->assign('list_lecture_class',$list_lecture_class);
            $this->smarty->assign('view','lecture_class');
            $this->smarty->display('admin/layout.html');
        }
        /**
         * 添加试讲课件
         * @author shangshikai@tizi.com
         */
        public function add_courseware(){
            $int_class_id = $this->input->post('class_id') ? intval($this->input->post('class_id')) : 0;
            $int_classroom_id = $this->input->post('classroom_id') ? intval($this->input->post('classroom_id')) : 0;
            $int_courseware_id = $this->input->post('courseware_id') ? intval($this->input->post('courseware_id')) : 0;
            $int_create_time = $this->input->post('create_time') ? strtotime($this->input->post('create_time')) : 0;
            $str_filename = $this->input->post('filename') ? trim($this->input->post('filename')) : '';
            $int_filesize = $this->input->post('filesize') ? intval($this->input->post('filesize')) : 0;
            $int_filetype = $this->input->post('filetype') ? intval($this->input->post('filetype')) : 0;

            if($int_class_id > 0 AND $int_courseware_id > 0 AND $int_create_time > 0 AND $str_filename){
                $arr_courseware = array(
                    'id' => $int_courseware_id,
                    'create_time' => $int_create_time,
                    'name' => $str_filename,
                    'filesize' => $int_filesize,
                    'filetype' => $int_filetype,
                );
                $bool_return = $this->lecture->create_courseware($int_class_id,$arr_courseware,$int_classroom_id);
                if($bool_return==true){
                    $this->arr_response['status'] = 'ok';
                    $this->arr_response['msg'] = '添加成功';
                }
            }
            self::json_output($this->arr_response);
        }

        /**
         * 管理员进教室
         * @author shangshikai@tizi.com
         */
        public function enter(){
            $int_classroom_id = $this->uri->rsegment(3) ? $this->uri->rsegment(3) : 0;
            $arr_class = $this->lecture->get_class_by_classroom_id($int_classroom_id);
            if($arr_class){
                $arr_class_map = config_item('round_class_map');
                $int_classroom_id = isset($arr_class_map[$int_classroom_id]) ? $arr_class_map[$int_classroom_id] : $int_classroom_id ;
                $str_iframe = self::enter_classroom($int_classroom_id,NH_MEETING_TYPE_ADMIN,array('class_title'=>$arr_class['title']));
                $this->smarty->assign('js_module', 'classRoom');
                $this->smarty->assign('classroom_id', $int_classroom_id);
                $this->smarty->assign('iframe', $str_iframe);
                $this->smarty->display('admin/classroom.html');
            }else{
                die('');
            }
        }
}