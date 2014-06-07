<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User相关逻辑
 * Class Business_User
 * @author yanrui@tizi.com
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
     * 通过手机重新设置密码
     * @param int       $user_id 用户Id
     * @param string    $password 新密码
     */
    public function phone_reset_password($user_id, $password)
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
                           gender,age,bankname,bankbench,bankcard,id_code,title,work_auth,teacher_auth,titile_auth,
                           province,city,area,school,teacher_age,teacher_intro,teacher_signature,stage,grade';
            $arr_where = array(
                'id' => $user_id,
                'status' => 1,
            );
            $arr_return = $this->model_user->get_user_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
            #加载用户教学科目数据(可以为空主要针对老师用户)
            $arr_return['teacher_subject'] = $this->business_subject->get_teacher_subject($user_id);
            #加载用户感兴趣的科目(可以为空主要是针对学生用户)
            $arr_return['student_subject'] = $this->business_subject->get_student_subject($user_id);
        }
        
        return $arr_return;
    }
}