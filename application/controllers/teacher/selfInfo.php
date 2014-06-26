<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header('content-type:text/html;charset=utf-8');
class SelfInfo extends NH_User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('business/common/business_user');
        $this->load->model('business/common/business_area');
        $this->load->model('business/common/business_school');
        if(!$this->is_login)
        {
            redirect(student_url().'login');
        }
        if(!($this->session->userdata('user_type')==1))
        {
        	exit('<script>alert("您还不是那好课堂的老师！");window.location.href="'.student_url().'";</script>');
        }
        $this->smarty->assign('data', array('active'=>'selfinfo'));
    }
    
    
	public function index()
    {
        if($this->is_post()) {
            $post_data = $arr_return = array();
            $post_data['nickname'] = trim($this->input->post('nickname'));
            if($post_data['nickname'] && $post_data['nickname'] != $this->_user_detail['nickname']) {
                #修改数据库和redis中的昵称
                $this->business_user->modify_user($post_data, $this->session->userdata('user_id'));
                $this->session->set_userdata('nickname', $post_data['nickname']);
            }
            
            $post_data['realname'] = trim($this->input->post('realname'));
            $post_data['stage'] = intval($this->input->post('stage'));
            $post_data['teacher_subject'] = trim($this->input->post('teacher_subject'));
            $post_data['title'] = intval($this->input->post('teacher_title'));
            $post_data['gender'] = intval($this->input->post('gender'));
            $post_data['province'] = intval($this->input->post('province'));
            $post_data['city'] = intval($this->input->post('city'));
            $post_data['area'] = intval($this->input->post('area'));
            $post_data['teacher_age'] = intval($this->input->post('teacher_age'));
            $post_data['teacher_intro'] = trim($this->input->post('teacher_intro'));
            $post_data['bankname'] = intval($this->input->post('bank'));
            $post_data['bankbench'] = trim($this->input->post('bankbench'));
            $post_data['bankcard'] = trim($this->input->post('bankcard'));
            $post_data['id_code'] = trim($this->input->post('id_code'));
            $post_data['work_auth_img'] = trim($this->input->post('work_auth_img'));
            $post_data['teacher_auth_img'] = trim($this->input->post('teacher_auth_img'));
            $post_data['title_auth_img'] = trim($this->input->post('title_auth_img'));
            $post_data['school_id'] = intval($this->input->post('school_id'));
            $post_data['schoolname'] = trim($this->input->post('schoolname'));
            if($post_data['schoolname'] && empty($post_data['school_id'])) {
                #post过来的数据有学校名称但没学校ID, 这是用户自己输入的学校,需要把学校所属的地区也接收过来
                $post_data['province_id'] = intval($this->input->post('province_id'));
                $post_data['city_id'] = intval($this->input->post('city_id'));
                $post_data['county_id'] = intval($this->input->post('area_county_id'));
                $post_data['school_type'] = intval($this->input->post('school_type'));
                $post_data['custom_school'] = 1;
                $new_school_id = $this->business_school->add_custom_school($post_data);
                $post_data['school_id'] = intval($new_school_id);
            } else if($post_data['school_id'] && empty($post_data['schoolname'])) {
                #相反post过来的数据有学校id没学校名称,这是用户选择系统里的学校,把custom_school设成0,防止用户之前自定义过学校
                $post_data['custom_school'] = 0;
            }
            $result = $this->business_user->modify_user_info($post_data, $this->_user_detail['user_id']);
            if($result) {
                $arr_return = array('status' => SUCCESS, 'msg' => '更新资料成功');
            } else {
                $arr_return = array('status' => ERROR, 'msg' => '更新资料失败请稍后重试');
            }
            self::json_output($arr_return);
        }
        $this->load->model('business/common/business_school');
        $this->load->model('business/admin/business_lecture');
        $this->load->model('business/admin/business_teacher');
        $this->load->model('business/common/business_subject');
        #教学阶段
        $stages = $this->config->item('stage');
        #科目
        $subjects = $this->business_subject->get_subjects();
        #学校
        $my_school = $this->business_school->school_info($this->_user_detail['school'], 'schoolname,province_id,city_id,county_id', $this->_user_detail['custom_school']);
        $school_name = isset($my_school['schoolname']) ? $my_school['schoolname'] : '';
        array_shift($my_school);
        $school_area = $this->business_area->get_areas_by_ids($my_school);
        #教师职称
        $teacher_tile = $this->config->item('teacher_title');
        $gender = $this->config->item('gender');
        #支持的银行
        $banks = $this->config->item('bank');
        #省和直辖市
        $province=$this->business_lecture->all_province();
        $city = $area = array();
        if($this->_user_detail['province']) {
            $city = $this->business_teacher->city1($this->_user_detail['province']);
        }
        if($this->_user_detail['city']) {
            $area = $this->business_teacher->area1($this->_user_detail['city']);
        }
        //generate param for uploading to qiniu 设置传图到七牛的图片
        require_once APPPATH . 'libraries/qiniu/rs.php';
        require_once APPPATH . 'libraries/qiniu/io.php';
        Qiniu_SetKeys ( NH_QINIU_ACCESS_KEY, NH_QINIU_SECRET_KEY );
        $obj_putPolicy = new Qiniu_RS_PutPolicy ( NH_QINIU_BUCKET );
        $str_upToken = $obj_putPolicy->Token ( null );
        $this->load->helper('string');
        $str_salt = random_string('alnum', 6);
        //teacher auth img file name
        $str_work_img_file_name = 'teacher_'.date('YmdHis',time()).'_work_auth_i'.$str_salt;
        $str_auth_img_file_name = 'teacher_'.date('YmdHis',time()).'_teacher_auth_i'.$str_salt;
        $str_title_img_file_name = 'teacher_'.date('YmdHis',time()).'_title_auth_i'.$str_salt;
        $this->smarty->assign('upload_token',$str_upToken);
        $this->smarty->assign('upload_work_img_key', $str_work_img_file_name);
        $this->smarty->assign('upload_auth_img_key', $str_auth_img_file_name);
        $this->smarty->assign('upload_title_img_key', $str_title_img_file_name);
        $this->smarty->assign('stages', $stages);
        $this->smarty->assign('school_name', $school_name);
        $this->smarty->assign('school_area', $school_area);
        $this->smarty->assign('subjects', $subjects);
        $this->smarty->assign('titles', $teacher_tile);
        $this->smarty->assign('gender', $gender);
        $this->smarty->assign('banks', $banks);
        $this->smarty->assign('teacher_age_ceiling', TEACHER_AGE_CEILING);
        $this->smarty->assign('province', $province);
        $this->smarty->assign('area', $area);
        $this->smarty->assign('city', $city);
        $this->smarty->assign('special_city', array(2, 25, 27, 32));
		$this->smarty->display('teacher/teacherSelfInfo/index.html');
	}
	public function openClass(){
		$this->smarty->display('teacher/teacherSelfinfo/openClass.html');
	}
	public function password(){
		$this->smarty->display('teacher/teacherSelfinfo/password.html');
	}
	public function photo()
    {
        $user_id = $this->session->userdata('user_id');
        //generate param for uploading to qiniu
        require_once APPPATH . 'libraries/qiniu/rs.php';
        require_once APPPATH . 'libraries/qiniu/io.php';
        Qiniu_SetKeys ( NH_QINIU_ACCESS_KEY, NH_QINIU_SECRET_KEY );
        $obj_putPolicy = new Qiniu_RS_PutPolicy ( NH_QINIU_BUCKET );
        $str_upToken = $obj_putPolicy->Token ( null );
        $this->load->helper('string');
        $str_salt = random_string('alnum', 6);
        //user photo name
        $str_photo_name = 'user_photo_'.$user_id.date('YmdHis',time()).'_i'.$str_salt.'.jpg';
        $this->smarty->assign('upload_token',$str_upToken);
        $this->smarty->assign('photo_img_key', $str_photo_name);
		$this->smarty->display('teacher/teacherSelfinfo/photo.html');
	}
	public function success(){
		$this->smarty->display('teacher/teacherSelfinfo/success.html');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */