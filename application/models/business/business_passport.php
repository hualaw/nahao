<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 通用passport相关逻辑
 * Class Business_Passport
 * @author yanrui@91waijiao.com
 */
class Business_Passport extends NH_Model{


    /**
     *  暂时没用   可干掉
     */


    /**
     * 从cookie中取出用户id和密码
     * @param $int_user_type
     * @return array
     * @author yanrui@91waijiao.com
     */
    public function get_user_from_cookie($int_user_type){
        $arr_return = array();
        $str_token = get_cookie("token_{$int_user_type}");
        if ($str_token) {
            $str_token = authcode($str_token, 'DECODE');
            $arr_data = explode("\t", $str_token);
            $arr_return['user_id'] = isset($arr_data[0]) ? $arr_data[0] : 0;
            $arr_return['password'] = isset($arr_data[1]) ? $arr_data[1] : '';
        }
        return $arr_return;
    }

    /**
     * 从cache中取用户信息
     * @param $int_user_type
     * @param $int_user_id
     * @return array
     * @author yanrui@91waijiao.com
     */
    public function get_user_from_cache($int_user_type,$int_user_id){
        $arr_return = array();
        $str_user_info = $this->cache->get("{$int_user_type}-{$int_user_id}");
        if ($str_user_info) {
            $arr_return = (array)json_decode($str_user_info, true);
        }
        return $arr_return;
    }

    /**
     * 从db中取用户信息
     * @param $int_user_type
     * @param $int_user_id
     * @return array
     * @author yanrui@91waijiao.com
     */
    public function get_user_from_db($int_user_type,$int_user_id){
        $arr_return = array();
        $arr_role = array(ROLE_ADMIN,ROLE_STUDENT,ROLE_TEACHER);
        if(in_array($int_user_type,$arr_role) AND $int_user_id > 0){
            switch ($int_user_type) {
                case ROLE_ADMIN: //管理员
                    $this->load->model('model/admin/Model_Admin', 'admin');
                    $arr_return = $this->admin->get_admin_by_id($int_user_id);
                    $arr_return['permission'] = $this->admin->get_admin_permission($int_user_id, 'format');
                    break;
                case ROLE_STUDENT: //学生
                    $this->load->model('model/student/Model_Student', 'student');
                    $arr_return = $this->student->get_student_by_id($int_user_id);
                    if ($arr_return) {
                        $arr_return += $this->student->get_student_info_by_id($int_user_id);
                    }
                    break;
                case ROLE_TEACHER: //教师
                    $this->load->model('model/teacher/Model_Teacher', 'teacher');
                    $arr_return = $this->teacher->get_teacher_by_id($int_user_id);
                    break;
            }
        }
        return $arr_return;
    }

}