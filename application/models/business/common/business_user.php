<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User相关逻辑
 * Class Business_User
 * @author yanhengjia@tizi.com
 */
class Business_User extends NH_Model
{
    function __construct(){
        parent::__construct();
        $this->load->model('model/common/model_user');
    }

    /**
     * 根据绑定的email取user
     * @param string $str_username
     * @return array
     * @author yanhengjia@tizi.com
     */
    public function get_user_by_email($email)
    {
        $arr_return = array();
        if($email){
            $str_table_range = 'user';
            $str_result_type = 'one';
            $str_fields = 'id,nickname,email';
            $arr_where = array(
                'email' => $email
            );
//            echo $str_table_range.'--'.$str_result_type.'--'.$str_fields."\n";echo "where : \n";var_dump($arr_where);;exit;
            $arr_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }
    
    /**
     * 根据绑定的email取user
     * @param string $str_username
     * @return array
     * @author yanhengjia@tizi.com
     */
    public function get_user_by_nickname($nickname)
    {
        $arr_return = array();
        if($nickname){
            $str_table_range = 'user';
            $str_result_type = 'one';
            $str_fields = 'id,nickname,email';
            $arr_where = array(
                'nickname' => $nickname,
                'status'   => 1,
            );
//            echo $str_table_range.'--'.$str_result_type.'--'.$str_fields."\n";echo "where : \n";var_dump($arr_where);;exit;
            $arr_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }

    /**
     * 通过手机重新设置密码
     * @param int       $user_id 用户Id
     * @param string    $password 新密码
     */
    public function reset_password($user_id, $password)
    {
        $user_info = $this->model_user->get_user_by_param('user', 'one', '*', array('id' => $user_id));
        if($user_info) {
            $new_password = create_password($user_info['salt'], $password);
            $res = $this->model_user->update_user(array('password' => $new_password), array('id' => $user_info['id']));
            return $res === false ? false : true;
        }
        
        return false;
    }
    
    /**
     * 获得用户的基本信息
     * @param  int $user_id 用户Id
     * @return array
     * @
     */
    public function get_user_detail($user_id)
    {
        $this->load->model('business/common/business_subject');
        $arr_return = array();
        if($user_id) {
            $str_table_range = 'user_info';
            $str_result_type = 'one';
            $str_fields = 'id AS user_id,nickname,realname,phone_mask,email,avatar,phone_verified,email_verified,teach_priv,
                           gender,age,bankname,bankbench,bankcard,id_code,title,work_auth,teacher_auth,titile_auth,work_auth_img,
                           teacher_auth_img,title_auth_img,province,city,area,school,teacher_age,teacher_intro,teacher_signature,has_bought,stage,grade';
            $arr_where = array(
                'id' => $user_id,
                TABLE_USER.'.status' => 1,
            );
            $arr_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
            $arr_return['phone'] = get_pnum_phone_server($user_id);
            #加载用户教学科目数据(可以为空主要针对老师用户)
            $arr_return['teacher_subject'] = $this->business_subject->get_teacher_subject($user_id);
            #加载用户感兴趣的科目(可以为空主要是针对学生用户)
            $arr_return['student_subject'] = $this->business_subject->get_student_subject($user_id);
        }
        
        return $arr_return;
    }
    
    /**
     * 修改用户的user表信息
     * @param  array $update_data 要更新的用户数据
     * @param  int   $user_id 用户Id
     * @reutrn bool
     */
    public function modify_user($update_data, $user_id)
    {
        $userdata = array();
        !empty($update_data['avatar']) && $userdata['avatar'] = $update_data['avatar'];
        !empty($update_data['nickname']) && $userdata['nickname'] = $update_data['nickname'];
        !empty($update_data['phone_mask']) && $userdata['phone_mask'] = $update_data['phone_mask'];
        !empty($update_data['phone_verified']) && $userdata['phone_verified'] = $update_data['phone_verified'];
        $this->model_user->update_user($userdata, array('id' => $user_id));
        
        return true;
    }
    
    /**
     * 修改用户的user_info表信息
     * @param  array $update_data 要更新的用户数据
     * @param  int   $user_id 用户Id
     * @reutrn bool
     */
    public function modify_user_info($update_data, $user_id)
    {
        $userinfo = array();
        #user表中要更新的数据
        !empty($update_data['realname']) && $userinfo['realname'] = $update_data['realname'];
        #user_info表中要更新的数据
        !empty($update_data['stage']) && $userinfo['stage'] = $update_data['stage'];
        isset($update_data['title']) && $userinfo['title'] = $update_data['title'];
        isset($update_data['gender']) && $userinfo['gender'] = $update_data['gender'];
        !empty($update_data['province']) && $userinfo['province'] = $update_data['province'];
        !empty($update_data['city']) && $userinfo['city'] = $update_data['city'];
        !empty($update_data['area']) && $userinfo['area'] = $update_data['area'];
        !empty($update_data['teacher_age']) && $userinfo['teacher_age'] = $update_data['teacher_age'];
        !empty($update_data['teacher_intro']) && $userinfo['teacher_intro'] = $update_data['teacher_intro'];
        !empty($update_data['bankname']) && $userinfo['bankname'] = $update_data['bankname'];
        !empty($update_data['bankbench']) && $userinfo['bankbench'] = $update_data['bankbench'];
        !empty($update_data['bankcard']) && $userinfo['bankcard'] = $update_data['bankcard'];
        !empty($update_data['id_code']) && $userinfo['id_code'] = $update_data['id_code'];
        !empty($update_data['grade']) && $userinfo['grade'] = $update_data['grade'];
        !empty($update_data['school_id']) && $userinfo['school'] = $update_data['school_id'];
        !empty($update_data['work_auth_img']) && $userinfo['work_auth_img'] = $update_data['work_auth_img'];
        !empty($update_data['teacher_auth_img']) && $userinfo['teacher_auth_img'] = $update_data['teacher_auth_img'];
        !empty($update_data['title_auth_img']) && $userinfo['title_auth_img'] = $update_data['title_auth_img'];

        $this->model_user->update_user_info($userinfo, array('user_id' => $user_id));

        if(isset($update_data['teacher_subject'])) {
            $this->update_user_subject($update_data['teacher_subject'], $user_id, 'teacher');
        }
        if(isset($update_data['student_subject'])) {
            $this->update_user_subject($update_data['student_subject'], $user_id, 'student');
        }
        
        return true;
    }
    
    /**
     * 更新用户选择的学科
     * @param mixed  $subject 学科ID组成的数组或者是字符串
     * @param int    $user_id     用户Id
     * @param string $type    student | teacher
     * @return bool
     */
    public function update_user_subject($subject, $user_id, $type)
    {
        if(!is_array($subject)) {
            $subject = explode('-', $subject);
        }
        
        $table_name = $type . '_subject';
        $user_field = $type . '_id';
        #先把用户学科表里的记录清掉
        $this->db->delete($table_name, array($user_field => $user_id));
        foreach($subject as $val) {
            $this->db->insert($table_name, array($user_field => $user_id, 'subject_id' => $val));
        }
        
        return true;
    }
    
    /**
     * 验证用户的密码是否正确
     * @param int $user_id  用户ID
     * @param string  $password 密码
     * @return bool
     */
    public function check_user_password($user_id, $password)
    {
        if($user_id && $password) {
            $str_fields = 'id,password,salt';
            $arr_where  = array('id' => $user_id);
            $user_info = $this->model_user->get_user_by_param('user', 'one', $str_fields, $arr_where);
            $check_ret = check_password($user_info['salt'], $password, $user_info['password']);
            return $check_ret;
        }
        
        return false;
    }
}