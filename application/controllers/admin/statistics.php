<?php
    class Statistics extends NH_Admin_Controller
    {
    	function __construct(){
    		parent::__construct();
    		$this->load->model('model/admin/model_statistics','statistics');
    	}
        /**
         * @desc 统计每天教室的上课情况
         */
        public function index()
        {
        	
        	$int_start = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        	$time = trim($this->input->get('start_time'));
        	$post['time'] = !empty($time)?strtotime($time):'';
        	$nickname = trim($this->input->get('nickname'));
//         	print_r($nickname);
        	$phone = trim($this->input->get('phone'));
        	if ($nickname | $phone){
        		$user_id = $this->statistics->get_user_id_by_where($nickname,$phone);
        	}
        	$post['user_id'] = !empty($user_id)?$user_id:'';
            $result = $this->statistics->get_classroom_history($post,$int_start,PER_PAGE_NO);
         
            $list = $result['list'];
            
		    $int_count  = $result['total'];
		   
            //分页
            $this->load->library('pagination');
            
            $config = config_item('page_admin');
            $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
            $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
            $config['total_rows'] = $int_count;
            $config['per_page'] = PER_PAGE_NO;
            $this->pagination->initialize($config);
            parse_str($this->input->server('QUERY_STRING'),$arr_query_param);
            $this->smarty->assign('page',$this->pagination->create_links());
            
            $this->smarty->assign('count', $int_count);
            $this->smarty->assign('list', $list);
            $this->smarty->assign('time', $time);
            $this->smarty->assign('nickname', $nickname);
            $this->smarty->assign('phone', $phone);
            $this->smarty->assign('view', 'statistics_view');
            $this->smarty->display('admin/layout.html');
        }

	     
    }