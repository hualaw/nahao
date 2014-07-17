<?php
    class Statistics extends NH_Crm_Controller
    {
    	function __construct(){
    		parent::__construct();
    		$this->load->model('model/crm/model_statistics','statistics');
    	}
        /**
         * @desc 统计每天教室的上课情况
         */
        public function index()
        {
//         	$now = date("Y-m-d",time());
        	
        	$int_start = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        	$time = trim($this->input->post('start_time'));
        	$post['time'] = !empty($time)?strtotime($time):'';
        	$nickname = trim($this->input->post('nickname'));
        	$phone = trim($this->input->post('phone'));
        	if ($nickname | $phone){
        		$user_id = $this->statistics->get_user_id_by_where($nickname,$phone);
        	}
        	$post['user_id'] = !empty($user_id)?$user_id:'';

            $result = $this->statistics->get_classroom_history($post,$int_start*10,PER_PAGE_NO);
            
            $list = $result['list'];
            
		#分页
	    $this->load->library('pagination');
	    
	    $int_count  = $result['total'];
	   
	    $config = config_item('page_student');
	    $config['base_url'] = '/statistics/index';
	    $config['total_rows'] = $int_count;
	    $config['per_page'] = PER_PAGE_NO;
	    $config['use_page_numbers'] = true;
	    //$config['uri_segment'] = '4';//设为页面的参数，如果不添加这个参数分页用不了
	    
	    $this->pagination->initialize($config);
	    $show_page = $this->pagination->create_links();
            
            $this->smarty->assign('page',$show_page);
            
            $this->smarty->assign('list', $list);
            $this->smarty->assign('time', $time);
            $this->smarty->assign('nickname', $nickname);
            $this->smarty->assign('phone', $phone);
            $this->smarty->display('crm/statistics_view.html');
        }
    }