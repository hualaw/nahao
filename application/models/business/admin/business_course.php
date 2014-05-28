<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Course相关逻辑
 * Class Business_Course
 * @author yanrui@tizi.com
 */
class Business_Course extends NH_Model
{
    function __construct(){
        parent::__construct();
        $this->load->model('model/admin/model_course');
    }

    /**
     * 创建course
     * @param $arr_param
     * @return int
     * @author yanrui@tizi.com
     */
    public function create_course($arr_param)
    {
        $int_return = 0;
        if($arr_param){
            $arr_param['register_time'] = TIME_STAMP;
            $arr_param['status'] = 0;//默认启用
            $int_return = $this->model_course->create_course($arr_param);
        }
        return $int_return;
    }

    /**
     * 修改course
     * @param array $arr_param
     * @param array $arr_where
     * @return bool
     * @author yanrui@tizi.com
     */
    public function update_course($arr_param,$arr_where){
        $bool_flag = false;
        if($arr_param AND $arr_where){
            $bool_flag = $this->model_course->update_course($arr_param,$arr_where);
        }
        return $bool_flag;
    }

    /**
     * 根据条件获取course count
     * @param $arr_where
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_course_count($arr_where){
        $int_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'course';
            $str_result_type = 'count';
            $str_fields = 'count(1) as count';
            if(array_key_exists('group_id',$arr_where)){
                $arr_where[TABLE_ADMIN_PERMISSION_RELATION.'.group_id'] = $arr_where['group_id'];
            }
            if(array_key_exists('course_id',$arr_where)){
                $arr_where[TABLE_ADMIN_PERMISSION_RELATION.'.course_id'] = $arr_where['course_id'];
            }
            if(array_key_exists('username',$arr_where)){
                $arr_where['like'][TABLE_ADMIN.'.username'] = $arr_where['username'];
                unset($arr_where['username']);
            }
            $int_return = $this->model_course->get_course_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $int_return;
    }

    /**
     * 根据条件获取course count
     * @param $arr_where
     * @param $int_start
     * @param $int_limit
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_course_list($arr_where,$int_start,$int_limit){
        $arr_return = array();
        if(is_array($arr_where)){
            $str_table_range = 'course_group_permission';
            $str_result_type = 'list';
            $str_fields = TABLE_ADMIN.'.id,username,phone,email,realname,'.TABLE_ADMIN.'.status,'.TABLE_ADMIN_GROUP.'.name as group_name';
            if(array_key_exists('group_id',$arr_where)){
                $arr_where[TABLE_ADMIN_PERMISSION_RELATION.'.group_id'] = $arr_where['group_id'];
            }
            if(array_key_exists('course_id',$arr_where)){
                $arr_where[TABLE_ADMIN_PERMISSION_RELATION.'.course_id'] = $arr_where['course_id'];
            }
            if(array_key_exists('username',$arr_where)){
                $arr_where['like'][TABLE_ADMIN.'.username'] = $arr_where['username'];
                unset($arr_where['username']);
            }
            $arr_limit = array(
                'start'=>$int_start,
                'limit' => $int_limit
            );
            $arr_return = $this->model_course->get_course_by_param($str_table_range, $str_result_type, $str_fields, $arr_where, array(), array(),$arr_limit);
        }
        return $arr_return;
    }

    /**
     * 根据id取course
     * @param $int_course_id
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_course_by_id($int_course_id)
    {
        $arr_return = array();
        if($int_course_id){
            $str_table_range = 'course';
            $str_result_type = 'one';
            $str_fields = '*';
            $arr_where = array(
                'id' => $int_course_id
            );
            $arr_return = $this->model_course->get_course_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }

    /**
     * 根据username取course
     * @param string $str_username
     * @return array
     * @author yanrui@tizi.com
     */
    public function get_course_by_username($str_username)
    {
        $arr_return = array();
        if($str_username){
            $str_table_range = 'course';
            $str_result_type = 'one';
            $str_fields = 'id,username,phone,email,salt,password,realname,status';
            $arr_where = array(
                'username' => $str_username
            );
//            echo $str_table_range.'--'.$str_result_type.'--'.$str_fields."\n";echo "where : \n";var_dump($arr_where);;exit;
            $arr_return = $this->model_course->get_course_by_param($str_table_range, $str_result_type, $str_fields, $arr_where);
        }
        return $arr_return;
    }


}