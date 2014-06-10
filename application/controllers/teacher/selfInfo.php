<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header('content-type:text/html;charset=utf-8');
class Selfinfo extends NH_User_Controller {

    function __construct(){
        ini_set('display_errors', 'On');
        parent::__construct();
        $this->load->model('business/common/business_user');
    }
	public function index(){
        if($this->is_post()) {
            $post_data = array();
            $post_data['realname'] = trim($this->input->post('realname'));
            $post_data['stage'] = intval($this->input->post('stage'));
            $post_data['teacher_subject'] = trim($this->input->post('teacher_subject'));
            $post_data['title'] = intval($this->input->post('teacher_title'));
            $post_data['gender'] = intval($this->input->post('gender'));
            $post_data['provice'] = intval($this->input->post('province'));
            $post_data['city'] = intval($this->input->post('city'));
            $post_data['area'] = intval($this->input->post('area'));
            $post_data['teacher_age'] = intval($this->input->post('teacher_age'));
            $post_data['teacher_intro'] = trim($this->input->post('teacher_intro'));
            $post_data['bankname'] = intval($this->input->post('bank'));
            $post_data['bankbench'] = trim($this->input->post('bankbench'));
            $post_data['bankcard'] = trim($this->input->post('bankcard'));
            $post_data['id_code'] = trim($this->input->post('id_code'));
            $result = $this->business_user->modify_user_data($post_data, $this->_user_detail['user_id']);
            if($result) {
                $arr_return = array('status' => 'ok', 'msg' => '更新资料成功');
            } else {
                $arr_return = array('status' => 'error', 'msg' => '更新资料失败请稍后重试');
            }
            
            self::json_output($arr_return);
        }
        $this->load->model('business/common/business_school');
        #教学阶段
        $stages = $this->config->item('stage');
        #科目
        $subjects = $this->config->item('subject');
        #老师所属的学校
        $my_school = $this->business_school->school_info($this->_user_detail['school'], 'schoolname');
        #教师职称
        $teacher_tile = $this->config->item('teacher_title');
        $gender = $this->config->item('gender');
        #支持的银行
        $banks = $this->config->item('bank');
        $this->smarty->assign('stages', $stages);
        $this->smarty->assign('school', $my_school['schoolname']);
        $this->smarty->assign('subjects', $subjects);
        $this->smarty->assign('titles', $teacher_tile);
        $this->smarty->assign('gender', $gender);
        $this->smarty->assign('banks', $banks);
        $this->smarty->assign('teacher_age_ceiling', TEACHER_AGE_CEILING);
		$this->smarty->display('teacher/teacherSelfinfo/index.html');
	}
	public function openClass(){
		$this->smarty->display('teacher/teacherSelfinfo/openClass.html');
	}
	public function password(){
		$this->smarty->display('teacher/teacherSelfinfo/password.html');
	}
	public function photo(){
		$this->smarty->display('teacher/teacherSelfinfo/photo.html');
	}
	public function success(){
		$this->smarty->display('teacher/teacherSelfinfo/success.html');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */