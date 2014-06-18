<?php
/**
 * Author: liuhua
 * Date: 14-5-23
 * Time: 下午05:23
 */
class NH_User_Controller extends NH_Controller
{
    /**
     * 用户的详细信息
     * @var array 
     */
    protected $_user_detail = array();
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('business/common/business_user');
//        $this->_load_user_detail();
//        $this->smarty->assign('user_detail', $this->_user_detail);
    }
    
    /**
     * 加载用户的详细信息
     * @author yanhj
     */
    protected function _load_user_detail()
    {
        $user_id = $this->session->userdata('user_id');
        if($user_id) {
            $this->_user_detail = $this->business_user->get_user_detail($user_id);
        }
    }
    
    /**
     * 检查用户密码是否正确, 用于前台修改密码时对密码实时验证
     */
    public function front_check_password()
    {
        $arr_return = array();
        $password = $this->input->post('password');
        $user_id = $this->session->userdata('user_id');
        if(!$user_id){
            $arr_return = array('status' => ERROR, 'info' => '用户信息错误');
            self::json_output($arr_return);
        }
        $check_ret = $this->business_user->check_user_password($user_id, $password);
        if($check_ret) {
            $arr_return = array('status' => SUCCESS, 'info' => '密码正确');
        } else {
            $arr_return = array('status' => ERROR, 'info' => '密码错误');
        }
        self::json_output($arr_return);
    }

    
    /**
     * 前台修改密码
     */
    public function front_modify_password()
    {
        $arr_return = array();
        $user_id = $this->session->userdata('user_id');
        if(!$user_id) {
            $arr_return = array('status' => ERROR, info => '用户信息错误');
            self::json_output($arr_return);
        }
        $old_password = $this->input->post('password');
        $new_password = $this->input->post('set_password');
        $password_repition = $this->input->post('reset_password');
        $check_ret = $this->business_user->check_user_password($user_id, $old_password);
        if(!$check_ret || ($new_password != $password_repition)) {
            $arr_return = array('status' => ERROR, info => '用户信息错误');
            self::json_output($arr_return);
        }
        $modify_res = $this->business_user->reset_password($user_id, $new_password);
        if($modify_res) {
            $arr_return = array('status' => SUCCESS, 'info' => '密码修改成功');
            #清除掉session中的信息
            $this->session->sess_destroy();
        } else {
            $arr_return = array('status' => ERROR, 'info' => '密码修改失败');
        }
        
        self::json_output($arr_return);
    }
    
    /**
     * 获取城市列表,学生和教师的个人资料都要用
     */
    public function get_city_list()
    {
        $arr_return = array();
        $province = intval($this->input->post('province'));
        if($province) {
            $this->load->model('business/admin/business_teacher');
            $arr_return = $this->business_teacher->city1($province);
        }
        self::json_output($arr_return);
    }
    
    /**
     * 获取区、县列表
     */
    public function get_county_list()
    {
        $arr_return = array();
        $city = intval($this->input->post('city'));
        if($city) {
            $this->load->model('business/admin/business_teacher');
            $arr_return = $this->business_teacher->area1($city);
        }
        self::json_output($arr_return);
    }
}
