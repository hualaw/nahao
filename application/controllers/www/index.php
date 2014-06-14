<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/student/student_index');
        $this->load->model('business/teacher/business_teacher','teacher_b');
    }

    /**
     * 首页获取轮的列表信息
     */
	public function index()
	{  
        header('content-type: text/html; charset=utf-8');
        //var_dump($this->session->all_userdata());
        $array_data = $this->student_index->get_course_latest_round_list();
        //var_dump($array_data);die;
        $this->smarty->assign('array_data', $array_data);
        $this->smarty->display('www/studentHomePage/index.html');
	}
	
	/**
	 * 我要开课
	 */
	public function apply_teach()
	{
		#判断是否登录
	    if(! $this->is_login){
	        redirect('/login');
	    }
	    
		$param['stage'] = config_item('stage');
		$param['teacher_title'] = config_item('teacher_title');
		$param['teacher_type'] = config_item('teacher_type');
		$param['subject'] = $this->teacher_b->get_subject();
		$param['teach_years'] = 50;
		$user_info = $this->_user_detail;
		
		$data = array(
			'data' => $param,
			'user_info' => isset($user_info['phone']) || isset($user_info['email']) ? $user_info : array('phone'=>'','email'=>''),
		);
		$this->smarty->assign('data',$data);
	    $this->smarty->display('www/studentStartClass/writeInfo.html');
	}
	
	/**
	 * 保存我要开课申请
	 */
	public function save_apply_teach()
	{
	    #判断是否登录
	    if(! $this->is_login){
	        redirect('/login');
	    }
	    $course = $this->input->post("course");
	    $resume = $this->input->post("resume");
	    $subject= $this->input->post("subject");
	    $province= $this->input->post("province_id");
	    $city= $this->input->post("city_id");
	    $area= $this->input->post("area_county_id");
	    $school= $this->input->post("school_id");
	    $school_type= $this->input->post("school_type");
	    $schoolname= $this->input->post("schoolname");
	    $stage= $this->input->post("stage");
	    $teach_years= $this->input->post("teach_years");
	    $course_intro= $this->input->post("course_intro");
	    $teach_type= $this->input->post("teach_type");
	    $gender= $this->input->post("gender");
	    $title= $this->input->post("title");
	    $age= $this->input->post("age");
	    $phone= $this->input->post("phone");
	    $email= $this->input->post("email");
	    $qq= $this->input->post("qq");
	    $date_time= $this->input->post("date_time");
	    $start_time= $this->input->post("start_time");
	    $end_time= $this->input->post("end_time");
	    $start_time = strtotime($date_time.' '.$start_time);
	    $end_time = strtotime($date_time.' '.$end_time);
	    $name= $this->input->post("name");
	    $user_id = $this->session->userdata('user_id');                        #TODO用户登录就是user_id
	    
		$array_data = array(
            "course"=>$course,
            "resume"=>$resume,
            "subject"=>$subject,
            "status"=>1,
            "create_time"=>time(),
            "province"=>$province,
            "city"=>$city,
            "area"=>$area,
            "school"=>$school,
            "school_type"=>$school_type,
            "schoolname"=>$schoolname,
            "stage"=>$stage,
            "teach_years"=>$teach_years,
            "course_intro"=>$course_intro,
            "teach_type"=>$teach_type,
            "gender"=>$gender,
            "title"=>$title,
            "age"=>$age,
            "phone"=>$phone,
            "email"=>$email,
            "qq"=>$qq,
            "start_time"=>$start_time,
            "end_time"=>$end_time,
            "name"=>$name,
	        "user_id"=>$user_id
	    );
	    $bool_flag = $this->student_index->save_apply_teach($array_data);
	    header('Content-Type:text/html;CHARSET=utf-8');
	    if ($bool_flag)
	    {
//	        self::json_output(array('status'=>'ok','msg'=>'申请试讲操作成功'));
			echo '<script>alert("申请成功");window.location.href="/"</script>';
	    } else {
//	        self::json_output(array('status'=>'error','msg'=>'申请试讲操作失败'));
			echo '<script>alert("申请失败");window.location.href="/index/apply_teach/"</script>';
	    }
	}
	
	/**
	 * 意见反馈
	 */
	public function feedback()
	{
	    $str_content = $this->input->post("content");
	    $str_nickname = $this->input->post("nickname");
	    $str_email = $this->input->post("email");
	    $array_data = array(
	            'content'=>$str_content,
	            'nickname'=>  $str_nickname,
	            'email'=>$str_email,
	            'create_time'=>time()
	    );
	    $bool_flag = $this->model_index->save_feedback($array_data);
	    if ($bool_flag)
	    {
	        self::json_output(array('status'=>'ok','msg'=>'提交意见反馈成功'));
	    } else {
	        self::json_output(array('status'=>'error','msg'=>'提交意见反馈失败'));
	    }
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
