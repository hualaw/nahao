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
        $this->_load_user_detail();
        $this->smarty->assign('user_detail', $this->_user_detail);
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
            log_message('debug_nahao', "user_detail is:".print_r($this->_user_detail,1));
            $this->_user_detail['phone'] = $this->session->userdata('phone');
        }
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
            $arr_return = array('status' => ERROR, 'info' => '输入的密码不正确!');
            self::json_output($arr_return);
        }
        $modify_res = $this->business_user->reset_password($user_id, $new_password);
        if($modify_res) {
            $arr_return = array('status' => SUCCESS, 'info' => '密码修改成功', 'url' => student_url());
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
    
    /**
     * 头像处理
     */
    public function update_avatar()
    {
        $user_id = $this->session->userdata('user_id');
        //generate param for uploading to qiniu
        require_once APPPATH . 'libraries/qiniu/rs.php';
        require_once APPPATH . 'libraries/qiniu/io.php';
        Qiniu_SetKeys ( NH_QINIU_ACCESS_KEY, NH_QINIU_SECRET_KEY );
        $obj_putPolicy = new Qiniu_RS_PutPolicy ( NH_QINIU_BUCKET );
        $str_upToken = $obj_putPolicy->Token ( null );
        $putExtra = new Qiniu_PutExtra();
        $putExtra->Crc32 = 1; 
        //user photo name
        $this->load->helper('string');
        $str_salt = random_string('alnum', 6);
        $avatar_key = 'user_avartar_'.$user_id.date('YmdHis',time()).'_i'.$str_salt;//头像图片在qiniu上的key
        $avatar_source_key = 'source_' . $avatar_key;//头像原图的key
        $result = array('success'=>false,'msg'=>'上传失败');
        $success_num = 0;        
        while (list($key, $val) = each($_FILES))
        {
            if($key == '__source') {
                $detect_res = $this->detect_avatar($_FILES[$key]);
                if($detect_res['code'] == 1) {
                    list($ret, $err) = Qiniu_PutFile($str_upToken, $avatar_source_key, $_FILES[$key]['tmp_name'], $putExtra);
                    if($err === null) {
                        $success_num++;
                        $result['source_avatar_key'] = $ret['key'];
                    }
                }
                else {
                    self::json_output($detect_res);
                }
            } else {
                $detect_res = $this->detect_avatar($_FILES[$key]);
                if($detect_res['code'] == 1) {
                    list($ret, $err) = Qiniu_PutFile($str_upToken, $avatar_key, $_FILES[$key]['tmp_name'], $putExtra);
                    if($err === null) {
                        $success_num++;
                        $result['avatar_key'] = $ret['key'];
                    }
                } else {
                    self::json_output($detect_res);
                }
            }
            
            if($success_num == 2) {
                $avatar_url = NH_QINIU_URL . $result['avatar_key'];
                $update_data = array('avatar' => $result['avatar_key']);
                $this->business_user->modify_user($update_data, $user_id);
                $this->session->set_userdata('avatar', $avatar_url);
                $result['success'] = true;
                $result['msg'] = '上传成功';
            }
        }
        
        self::json_output($result);
    }
    
    /**
     * 侦测上传的头像是否符合规定
     * @param resource $avatar_data  图片数据
     */
    public function detect_avatar($avatar_data)
    {
        $detect_res = 0;
        if(!isset($avatar_data['error']) || $avatar_data['error'] > 0) {
            $detect_res =  -1; //上传失败
        }
        
        if(!$detect_res) {
            $img_info = getimagesize($avatar_data['tmp_name']);
            $image_type = $this->config->item('image_type');
            if(empty($img_info) || !array_key_exists($img_info[2], $image_type)) {
                $detect_res = -2; //图片类型不对
            }
        }
        
        if(!$detect_res) {
            $avatar_size_limit = $this->config->item('avatar_size_limit');
            if(!isset($avatar_data['size']) || $avatar_data['size'] > $avatar_size_limit) {
                return -3;//文件过大
            } 
        }
        
        switch($detect_res) {
            case -1:$arr_return = array("code"=> $detect_res, "msg"=>"上传失败");break;
            case -2:$arr_return = array("code"=> $detect_res,"msg"=>"格式错误");break;
            case -3:$arr_return = array("code"=> $detect_res,"msg"=>"文件过大");break;
            default:$arr_return = array("code"=>1);break;
        }
        
        return $arr_return;
    }
    
    /**
     * 检查用户的昵称,该方法只适用于update昵称时的验证,insert昵称的验证在login控制器里
     */
    public function validate_user_nickname()
    {
        $arr_return = array('status' => ERROR);
        if(isset($this->_user_detail['user_id'])) {
            $nickname = trim($this->input->post('nickname'));
            if($nickname == $this->_user_detail['nickname']) {
                #昵称未有改动直接返回成功, 这块是为了前台validFrom验证通过
                $arr_return = array('status' => SUCCESS, 'info' => '验证通过');
                self::json_output($arr_return);
            }
            #验证昵称长度
            $length_ret = check_name_length($nickname);
            if(!$length_ret) {
                $arr_return['info'] = '昵称要控制在4~25个字符,一个汉字按两个字符计算';
                self::json_output($arr_return);
            }
            #验证昵称是否重复
            $check_ret = $this->business_user->get_user_by_nickname($nickname);
            if(isset($check_ret['id'])) {
                $arr_return['info'] = "该昵称已被其他人占用";
                self::json_output($arr_return);
            } else {
                $arr_return = array('status' => SUCCESS, 'info' => '验证通过');
                self::json_output($arr_return);
            }
            
        }
        $arr_return['info'] = '验证失败';
        self::json_output($arr_return);
    }
    
    /**
     * 检查用户真实姓名的长度
     */
    public function check_realname_length()
    {
        $arr_return = array('status' => ERROR);
        $realname = trim($this->input->post('realname'));
        $check_ret = check_name_length($realname);
        if(!$check_ret) {
            $arr_return['info'] = '真实姓名要控制在4~25个字符,一个汉字按两个字符计算';
        } else {
            $arr_return['status'] = SUCCESS;
            $arr_return['info'] = '验证通过';
        }
        
        self::json_output($arr_return);
    }
    
    /**
     * 检查邮箱是否可用
     */
    public function check_email_availability()
    {
        $arr_return = array('status' => ERROR);
        $email = trim($this->input->post('email'));
        if($email == $this->session->userdata('email')) {
            #用户邮箱未做更改直通过验证，这块是为了前台validFrom验证通过
            $arr_return['status'] = SUCCESS;
            self::json_output($arr_return);
        }
        $user_info = $this->business_user->get_user_by_email($email);
        if(isset($user_info['id'])) {
            $arr_return = array('status' => ERROR, 'info' => '该邮箱已绑定其他账户');
        } else {
            $arr_return = array('status' => SUCCESS, 'info' => '邮箱可用');
        }
        self::json_output($arr_return);
    }
}
